<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\InventoryMovement;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $orders = Order::with(['customer.user'])
            ->when($search, function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('customer.user', function ($q) use ($search) {
                        $q->where('first_name', 'ilike', "%{$search}%")
                            ->orWhere('last_name', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%");
                    });
            })
            ->when($status && OrderStatus::tryFrom((int)$status), function ($q) use ($status) {
                $q->where('status_id', $status);
            })
            ->latest()
            ->paginate(15)
            ->appends(compact('search', 'status'));

        return view('admin.orders.index', compact('orders', 'search', 'status'));
    }

    public function show(Order $order)
    {
        $order->load([
            'customer.user',
            'shippingAddress',
            'shipment',
            'items.product',
            'payments',
            'statusHistory.user',
            'quotation',
        ]);

        $statuses = OrderStatus::cases();
        $paymentStatuses = PaymentStatus::cases();

        return view('admin.orders.show', compact('order', 'statuses', 'paymentStatuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status_id' => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\OrderStatus::class)],
            'comment'   => 'nullable|string|max:500',
        ]);

        $newStatus = \App\Enums\OrderStatus::from($validated['status_id']);
        $oldStatus = $order->status_id; // OrderStatus actual
        $this->handleInventoryOnStatusChange($order, $newStatus, $oldStatus);
        $order->loadMissing('items.product.inventory');

        // OPCIÓN A PRUEBA DE BALAS: Usar el modelo directamente en lugar de la relación
        \App\Models\OrderStatusHistory::create([
            'order_id'   => $order->id,
            'status_id'  => $newStatus->value,
            'comment'    => $validated['comment'],
            'user_id'    => \Illuminate\Support\Facades\Auth::id(),
            'changed_at' => now(),
        ]);

        $order->update(['status_id' => $newStatus->value]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Estado actualizado correctamente.');
    }

    public function updateShipment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'shipping_method'         => 'required|string',
            'carrier'                 => 'nullable|string',
            'cost'                    => 'nullable|numeric|min:0',
            'tracking_number'         => 'nullable|string',
            'label_url'               => 'nullable|url',
            'estimated_delivery_date' => 'nullable|date',
        ]);

        if ($order->shipment) {
            $order->shipment->update($validated);
        } else {
            // 👇 AQUÍ ESTÁ EL ARREGLO: Le heredamos la dirección del pedido al envío
            $validated['address_id'] = $order->shipping_address_id;

            $shipment = Shipment::create($validated);
            $order->update(['shipment_id' => $shipment->id]);
        }

        return back()->with('success', 'Envío actualizado.');
    }

    public function approvePayment(Order $order, Payment $payment)
    {
        abort_if($payment->order_id !== $order->id, 404);

        $payment->update([
            'status_id' => PaymentStatus::PAID->value,
            'paid_at'   => now(),
        ]);

        return back()->with('success', 'Pago aprobado.');
    }

    private function handleInventoryOnStatusChange(Order $order, OrderStatus $newStatus, OrderStatus $oldStatus): void
    {
        // No hacemos nada si el estado no cambia realmente
        if ($newStatus === $oldStatus) {
            return;
        }

        $consumingStates = [
            OrderStatus::PROCESSING,
            OrderStatus::SHIPPED,
            OrderStatus::DELIVERED,
        ];

        $isNewStateConsuming = in_array($newStatus, $consumingStates, true);
        $wasOldStateConsuming = in_array($oldStatus, $consumingStates, true);

        // CASO 1: El pedido pasa a un estado que consume stock y no tenía movimientos de salida previos
        if ($isNewStateConsuming && !$wasOldStateConsuming) {
            foreach ($order->items as $item) {
                // Solo descontamos si aún no tiene movimiento de inventario asociado
                if ($item->inventory_movement_id) {
                    continue;
                }

                $product = $item->product;

                // Solo si el producto tiene control de inventario activado
                if (!$product->track_inventory) {
                    continue;
                }

                $inventory = $product->inventory;

                if (!$inventory || $inventory->quantity < $item->quantity) {
                    throw new \Exception("Stock insuficiente para el producto {$product->name}.");
                }

                $newQuantity = $inventory->quantity - $item->quantity;

                $movement = InventoryMovement::create([
                    'product_id'        => $product->id,
                    'movement_type'     => InventoryMovement::TYPE_SALE,
                    'quantity'          => -$item->quantity, // negativo = salida
                    'resulting_quantity' => $newQuantity,
                    'reference'         => "Pedido #{$order->id}",
                    'user_id'           => auth()->id(),
                ]);

                // Actualizar stock
                $inventory->update(['quantity' => $newQuantity]);

                // Asociar el movimiento al item del pedido
                $item->update(['inventory_movement_id' => $movement->id]);
            }
        }

        // CASO 2: El pedido se cancela y tenía movimientos de salida (restock)
        if ($newStatus === OrderStatus::CANCELLED && $wasOldStateConsuming) {
            foreach ($order->items as $item) {
                // Solo restock si tiene movimiento de salida y el producto aún existe
                if (!$item->inventory_movement_id) {
                    continue;
                }

                $product = $item->product;
                if (!$product->track_inventory) {
                    continue;
                }

                $inventory = $product->inventory;
                if (!$inventory) {
                    continue;
                }

                $newQuantity = $inventory->quantity + $item->quantity;

                // Registramos movimiento de entrada (restock por cancelación)
                InventoryMovement::create([
                    'product_id'        => $product->id,
                    'movement_type'     => InventoryMovement::TYPE_RETURN,
                    'quantity'          => $item->quantity,
                    'resulting_quantity' => $newQuantity,
                    'reference'         => "Cancelación Pedido #{$order->id}",
                    'user_id'           => auth()->id(),
                ]);

                $inventory->update(['quantity' => $newQuantity]);

                // Opcional: conservamos el inventory_movement_id original; no lo borramos.
            }
        }
    }
}
