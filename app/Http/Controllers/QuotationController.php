<?php

namespace App\Http\Controllers;

use App\Enums\QuotationStatus;
use App\Http\Requests\StoreQuotationRequest;
use App\Models\Product;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

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
        
        // Evitamos intentar guardar el arreglo de archivos en la base de datos relacional
        unset($data['attachments']);

        $quotation = Quotation::create($data);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $quotation->addMedia($file)->toMediaCollection('quotation_files', 'public');
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

        // Solo se puede pagar si ya tiene precio y está cotizada o aprobada
        if (!in_array($quotation->status->value, ['quoted', 'approved']) || !$quotation->estimated_price) {
            return redirect()->route('quotations.show', $quotation)
                ->with('error', 'Esta cotización aún no está lista para pago.');
        }

        $customer = Auth::user()->customer;
        $addresses = $customer->addresses()->latest()->get();

        // Si no tiene direcciones, lo mandamos a crear una y lo devolvemos aquí
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

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'notes'      => 'nullable|string|max:1000',
        ]);

        // Aseguramos que la dirección pertenezca al cliente
        $address = \App\Models\Address::where('id', $request->address_id)
            ->where('customer_id', Auth::user()->customer->id)
            ->firstOrFail();

        // AQUÍ SE CREA EL PEDIDO (Order)
        // Como el módulo de Orders lo haremos después, dejamos la estructura base preparada:
        /*
        $order = Order::create([
            'customer_id' => Auth::user()->customer->id,
            'address_id'  => $address->id,
            'total'       => $quotation->estimated_price,
            'notes'       => $request->notes,
            'status'      => 'pending_payment',
            'is_custom'   => true, // Bandera para saber que viene de cotización
        ]);
        */

        // Por ahora, solo cambiamos el estado de la cotización a Aprobada/Procesando
        $quotation->update(['status' => 'approved']);

        return redirect()->route('quotations.index')
            ->with('success', '¡Pedido confirmado! Hemos recibido tu solicitud de fabricación. Te enviaremos los detalles de pago a tu correo.');
    }
    
    public function show(Quotation $quotation)
    {
        if ($this->isNotOwner($quotation)) {
            return redirect()->route('quotations.index')
                ->with('error', 'Acceso denegado. Esta cotización no pertenece a tu cuenta.');
        }

        // Cargamos la relación de mensajes y sus imágenes (media)
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