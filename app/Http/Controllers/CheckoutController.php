<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Services\CartManager;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $cart;
    protected $discountService;

    public function __construct(CartManager $cart, DiscountService $discountService)
    {
        $this->cart = $cart;
        $this->discountService = $discountService;
        $this->middleware('auth');
    }

    public function index()
    {
        $customer = request()->user()->customer;

        if (!$customer->addresses()->exists()) {
            return redirect()->route('addresses.create')
                ->with('warning', 'Registra una dirección de envío para continuar.');
        }

        $items = $this->cart->getItems();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', 'Tu carrito está vacío.');
        }

        $addresses = $customer->addresses;
        $shippingMethods = config('shipping.methods');
        $subtotal = $this->cart->getSubtotal();

        $appliedCoupon = session('checkout.coupon_code');
        $discountAmount = 0;
        $couponError = null;

        if ($appliedCoupon) {
            try {
                $result = $this->discountService->applyCoupon($appliedCoupon, $items, $customer);
                $discountAmount = $result['amount'];
            } catch (\Exception $e) {
                $couponError = $e->getMessage();
                session()->forget('checkout.coupon_code');
            }
        }

        $selectedShipping = session('checkout.shipping_method', 'standard');
        $shippingCost = $shippingMethods[$selectedShipping]['cost'] ?? 0;
        $total = max(0, $subtotal - $discountAmount) + $shippingCost;

        return view('checkout.index', compact(
            'items',
            'addresses',
            'shippingMethods',
            'subtotal',
            'discountAmount',
            'shippingCost',
            'total',
            'selectedShipping',
            'appliedCoupon',
            'couponError'
        ));
    }

    public function store(Request $request)
    {
        $customer = request()->user()->customer;
        $items = $this->cart->getItems();

        if ($items->isEmpty()) {
            return back()->with('error', 'El carrito está vacío.');
        }

        $validated = $request->validate([
            'address_id'      => 'required|exists:addresses,id,customer_id,' . $customer->id,
            'shipping_method' => 'required|in:standard,express',
            'notes'           => 'nullable|string|max:500',
        ]);

        $addressId = $validated['address_id'];
        $shippingMethod = $validated['shipping_method'];
        $shippingCost = config("shipping.methods.{$shippingMethod}.cost", 0);
        $subtotal = $this->cart->getSubtotal();

        $discountAmount = 0;
        $coupon = null;
        $couponCode = session('checkout.coupon_code');

        if ($couponCode) {
            try {
                $result = $this->discountService->applyCoupon($couponCode, $items, $customer);
                $discountAmount = $result['amount'];
                $coupon = $result['coupon'];
            } catch (\Exception $e) {
                session()->forget('checkout.coupon_code');
            }
        }

        $total = max(0, $subtotal - $discountAmount) + $shippingCost;

        try {
            $order = DB::transaction(function () use (
                $customer,
                $addressId,
                $shippingMethod,
                $shippingCost,
                $subtotal,
                $discountAmount,
                $total,
                $items,
                $coupon,
                $validated
            ) {
                $shipment = Shipment::create([
                    'address_id'      => $addressId,
                    'shipping_method' => $shippingMethod,
                    'cost'            => $shippingCost,
                    'status'          => 'pending',
                ]);

                $order = Order::create([
                    'customer_id'         => $customer->id,
                    'shipping_address_id' => $addressId,
                    'shipment_id'         => $shipment->id,
                    'coupon_id'           => $coupon?->id,
                    'status_id'           => 1, // pending
                    'subtotal'            => $subtotal,
                    'discount_total'      => $discountAmount,
                    'shipping_cost'       => $shippingCost,
                    'total'               => $total,
                    'notes'               => $validated['notes'] ?? null,
                ]);

                foreach ($items as $item) {
                    $product = $item->product;
                    $quantity = $item->quantity;
                    $unitPrice = $product->price;

                    $orderItem = $order->items()->create([
                        'product_id'    => $product->id,
                        'quantity'      => $quantity,
                        'unit_price'    => $unitPrice,
                        'unit_discount' => 0,
                    ]);

                    if ($product->track_inventory) {
                        // Bloqueo estricto para evitar Race Conditions (Inventario Negativo)
                        $inventory = Inventory::where('product_id', $product->id)->lockForUpdate()->firstOrFail();

                        if ($inventory->quantity < $quantity) {
                            throw new \Exception("Stock insuficiente para el producto: {$product->name}");
                        }

                        $inventory->decrement('quantity', $quantity);

                        $movement = InventoryMovement::create([
                            'product_id'         => $product->id,
                            'movement_type'      => 'salida',
                            'quantity'           => $quantity,
                            'resulting_quantity' => $inventory->quantity, // Valor fresco
                            'reference'          => "Pedido #{$order->id}",
                            'user_id'            => request()->user()?->id,
                        ]);

                        $orderItem->update(['inventory_movement_id' => $movement->id]);
                    }
                }

                if ($coupon) {
                    $coupon->increment('used_count');
                }

                Payment::create([
                    'order_id'  => $order->id,
                    'status_id' => 1,
                    'amount'    => $total,
                ]);

                return $order;
            });

            if ($customer->cart) {
                $customer->cart->delete();
            }
            $this->cart->clearSession();
            session()->forget(['checkout.coupon_code', 'checkout.shipping_method']);

            return redirect()->route('orders.confirmation', $order)
                ->with('success', 'Pedido creado exitosamente. Está pendiente de pago.');
        } catch (\Exception $e) {
            Log::error('Error en Checkout: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un problema: ' . $e->getMessage());
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $customer = request()->user()->customer;
        $items = $this->cart->getItems();

        try {
            $result = $this->discountService->applyCoupon($request->code, $items, $customer);
            session()->put('checkout.coupon_code', $request->code);

            return response()->json([
                'success'  => true,
                'discount' => $result['amount'],
                'message'  => 'Cupón aplicado.',
            ]);
        } catch (\Exception $e) {
            session()->forget('checkout.coupon_code');
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function confirmation(\App\Models\Order $order, \Illuminate\Http\Request $request)
    {
        if ($order->customer_id !== $request->user()->customer?->id) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }
        return view('orders.confirmation', compact('order'));
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('checkout.coupon_code');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cupón eliminado.'
            ]);
        }

        return back()->with('success', 'Cupón eliminado.');
    }
}
