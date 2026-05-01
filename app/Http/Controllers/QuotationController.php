<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuotationRequest;
use App\Models\Product;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// use App\Events\QuotationRequested; <-- Para implementar en el futuro

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(?Product $product = null)
    {
        $subject = request('subject', '');
        return view('quotations.create', compact('product', 'subject'));
    }

    public function store(StoreQuotationRequest $request)
    {
        $customer = Auth::user()->customer;
        $data = $request->validated();
        
        $data['customer_id'] = $customer->id;
        $data['status'] = 'pending';

        // ALMACENAMIENTO SEGURO: Usamos disco 'local' (privado), no 'public'
        if ($request->hasFile('attachments')) {
            $paths = [];
            foreach ($request->file('attachments') as $file) {
                // Se guarda en storage/app/quotations (inaccesible desde URL pública)
                $paths[] = $file->store('quotations', 'local'); 
            }
            $data['attachments'] = $paths;
        }

        $quotation = Quotation::create($data);

        // Disparamos un evento para enviar correos sin bloquear el render de la página
        // event(new QuotationRequested($quotation)); 

        return redirect()->route('quotations.index')
            ->with('success', 'Tu solicitud de cotización ha sido enviada. Te contactaremos pronto.');
    }

    /**
     * Lista las cotizaciones del cliente autenticado.
     */
    public function index()
    {
        $customer = Auth::user()->customer;
        $quotations = $customer->quotations()->latest()->get();

        return view('quotations.index', compact('quotations'));
    }

    /**
     * Muestra el detalle de una cotización.
     */
    public function show(Quotation $quotation)
    {
        $this->authorize('view', $quotation);

        return view('quotations.show', compact('quotation'));
    }

    // Método para convertir cotización en pedido (será llamado desde Chat 3)
    public function convertToOrder(Quotation $quotation)
    {
        // Lógica de conversión (a implementar en Chat 3)
        return redirect()->back()->with('info', 'Funcionalidad en desarrollo.');
    }

    /**
     * Nuevo método para descargar archivos de forma segura
     */
    public function downloadAttachment(Quotation $quotation, string $filename)
    {
        $this->authorize('view', $quotation);

        $path = 'quotations/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return response()->download(Storage::disk('local')->path($path));
    }
}