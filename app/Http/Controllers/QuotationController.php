<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\QuotationStatus;
use App\Http\Requests\StoreQuotationRequest;
use App\Models\Address;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Quotation;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class QuotationController extends Controller
{
    public function create(?Product $product = null)
    {
        if (!Auth::user()->customer) {
            return redirect()->route('dashboard')->with('error', 'Debes completar tu perfil de cliente para solicitar una cotización.');
        }

        $subject = request('subject', '');
        return view('quotations.create', compact('product', 'subject'));
    }

    public function store(StoreQuotationRequest $request)
    {
        $customer = Auth::user()->customer;
        
        if (!$customer) {
            return redirect()->route('dashboard')->with('error', 'Perfil de cliente no encontrado.');
        }

        $data = $request->validated();
        $data['customer_id'] = $customer->id;
        $data['status'] = QuotationStatus::PENDING;

        unset($data['attachments']);

        $quotation = DB::transaction(function () use ($data, $request) {
            $quotation = Quotation::create($data);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $quotation->addMedia($file)->toMediaCollection('quotation_files', 'public');
                }
            }

            return $quotation;
        });

        $adminEmails = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->pluck('email')->filter()->values();

        if ($adminEmails->isNotEmpty()) {
            $quotation->loadMissing(['customer.user', 'product']);

            try {
                foreach ($adminEmails as $adminEmail) {
                    Mail::send('emails.quotations.new-quotation-admin', ['quotation' => $quotation], function ($message) use ($adminEmail, $quotation) {
                        $message->to($adminEmail)
                            ->subject('Nueva cotización recibida: ' . $quotation->subject);
                    });
                }
            } catch (\Exception $e) {
                logger()->error('No se pudo enviar la notificación de nueva cotización.', [
                    'quotation_id' => $quotation->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('quotations.index')
            ->with('success', 'Tu solicitud de cotización ha sido enviada. Te contactaremos pronto.');
    }

    public function index()
    {
        $customer = Auth::user()->customer;
        if (!$customer) {
            return redirect()->route('dashboard')->with('error', 'Solo los perfiles de cliente tienen historial de cotizaciones.');
        }
        $quotations = $customer->quotations()->latest()->paginate(15);

        return view('quotations.index', compact('quotations'));
    }

    public function convertToOrder(Quotation $quotation)
    {
        return redirect()->back()->with('info', 'Funcionalidad en desarrollo.');
    } 

    public function downloadAttachment(Quotation $quotation, $mediaId)
    {
        if ($this->isNotOwner($quotation)) {
            abort(403, 'No tienes permiso para descargar los archivos de esta cotización.');
        }
        
        $media = \App\Models\Media::findOrFail($mediaId);
        
        if ((string) $media->model_id !== (string) $quotation->id) {
            abort(403, 'Permiso denegado: Este archivo no pertenece a la cotización actual.');
        }
        
        $path = $media->getPath();
        
        if (!file_exists($path)) {
            return back()->with('error', 'El archivo físico ya no se encuentra disponible en el servidor.');
        }
        
        return response()->download($path, $media->file_name);
    }

    private function isNotOwner(Quotation $quotation): bool
    {
        $customer = Auth::user()->customer;
        return !$customer || $quotation->customer_id !== $customer->id;
    } 

    public function checkout(Quotation $quotation)
    {
        if ($this->isNotOwner($quotation)) abort(403);

        if (!in_array($quotation->status->value, ['quoted', 'approved']) || !$quotation->estimated_price) {
            return redirect()->route('quotations.show', $quotation)
                ->with('error', 'Esta cotización aún no está lista para pago.');
        }

        $customer = Auth::user()->customer;
        $addresses = $customer->addresses()->latest()->get();

        if ($addresses->isEmpty()) {
            session(['url.intended.address' => route('quotations.checkout', $quotation)]);
            return redirect()->route('addresses.create')
                ->with('info', 'Por favor, agrega una dirección de envío para continuar.');
        }

        return view('quotations.checkout', compact('quotation', 'addresses'));
    }

    public function processCheckout(Request $request, Quotation $quotation)
    {
        if ($this->isNotOwner($quotation)) abort(403);

        $customer = Auth::user()->customer;

        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id,customer_id,' . $customer->id,
            'notes'      => 'nullable|string|max:1000',
        ]);

        $address = Address::where('id', $validated['address_id'])
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        try {
            $order = DB::transaction(function () use ($customer, $address, $quotation, $validated) {
                $shipment = Shipment::create([
                    'address_id'      => $address->id,
                    'shipping_method' => 'cotizacion',
                    'cost'            => 0,
                    'status'          => 'pending',
                ]);

                $order = Order::create([
                    'customer_id'         => $customer->id,
                    'shipping_address_id' => $address->id,
                    'shipment_id'         => $shipment->id,
                    'quotation_id'        => $quotation->id,
                    'status_id'           => OrderStatus::PENDING->value,
                    'subtotal'            => $quotation->estimated_price,
                    'discount_total'      => 0,
                    'shipping_cost'       => 0,
                    'total'               => $quotation->estimated_price,
                    'notes'               => $validated['notes'] ?? null,
                ]);

                if ($quotation->product_id) {
                    $product = $quotation->product;
                    $orderItem = $order->items()->create([
                        'product_id'    => $product->id,
                        'quantity'      => 1,
                        'unit_price'    => $quotation->estimated_price,
                        'unit_discount' => 0,
                    ]);

                    if ($product->track_inventory) {
                        $inventory = Inventory::where('product_id', $product->id)->lockForUpdate()->firstOrFail();

                        if ($inventory->quantity < 1) {
                            throw new \Exception("Stock insuficiente para el producto: {$product->name}");
                        }

                        $inventory->decrement('quantity', 1);

                        $movement = InventoryMovement::create([
                            'product_id'         => $product->id,
                            'movement_type'      => 'salida',
                            'quantity'           => 1,
                            'resulting_quantity' => $inventory->quantity,
                            'reference'          => "Pedido #{$order->id}",
                            'user_id'            => request()->user()?->id,
                        ]);

                        $orderItem->update(['inventory_movement_id' => $movement->id]);
                    }
                }

                Payment::create([
                    'order_id'  => $order->id,
                    'status_id' => PaymentStatus::PENDING->value,
                    'amount'    => $quotation->estimated_price,
                ]);

                $quotation->update(['status' => QuotationStatus::APPROVED]);

                return $order;
            });

            return redirect()->route('orders.confirmation', $order)
                ->with('success', '¡Pedido confirmado! Hemos recibido tu solicitud de fabricación. Te enviaremos los detalles de pago a tu correo.');
        } catch (\Throwable $e) {
            logger()->error('Error al convertir cotización en pedido.', [
                'quotation_id' => $quotation->id,
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'No pudimos convertir la cotización en pedido. Intenta nuevamente.');
        }
    }
    
    public function show(Quotation $quotation)
    {
        if ($this->isNotOwner($quotation)) {
            return redirect()->route('quotations.index')
                ->with('error', 'Acceso denegado. Esta cotización no pertenece a tu cuenta.');
        }

        $quotation->load(['product', 'media', 'messages.media']);

        return view('quotations.show', compact('quotation'));
    }

    public function sendMessage(Request $request, Quotation $quotation)
    {
        if ($this->isNotOwner($quotation)) abort(403);

        $request->validate([
            'message' => 'required|string|max:2000',
            'chat_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
        ]);

        $message = \App\Models\QuotationMessage::create([
            'quotation_id' => $quotation->id,
            'sender_type'  => 'customer',
            'message'      => strip_tags($request->message),
        ]);

        if ($request->hasFile('chat_image')) {
            $message->addMediaFromRequest('chat_image')->toMediaCollection('chat_images', 'public');
        }

        return back();
    }
}