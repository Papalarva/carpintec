<?php

namespace App\Http\Controllers;

use App\Enums\QuotationStatus;
use App\Http\Requests\StoreQuotationRequest;
use App\Models\Product;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
// use App\Events\QuotationRequested; <-- Para implementar en el futuro

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(?Product $product = null)
    {
        // Validación: Solo los clientes pueden crear cotizaciones
        if (!Auth::user()->customer) {
            return redirect()->route('dashboard')->with('error', 'Debes completar tu perfil de cliente para solicitar una cotización.');
        }

        $subject = request('subject', '');
        return view('quotations.create', compact('product', 'subject'));
    }

    public function store(StoreQuotationRequest $request)
    {
        $customer = Auth::user()->customer;
        
        // Protección extra
        if (!$customer) {
            return redirect()->route('dashboard')->with('error', 'Perfil de cliente no encontrado.');
        }

        $data = $request->validated();
        
        $data['customer_id'] = $customer->id;
        
        // Regla 2: Usamos el Enum estricto en lugar de texto plano
        $data['status'] = QuotationStatus::PENDING;

        // Limpiamos los attachments del request para que no intente guardarlos en el JSONB
        unset($data['attachments']);

        $quotation = Quotation::create($data);

        // Regla 1: ALMACENAMIENTO SEGURO CON SPATIE MEDIALIBRARY
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $quotation->addMedia($file)->toMediaCollection('quotation_files');
            }
        }

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

        // SOLUCIÓN AL ERROR: Evitamos que intente buscar cotizaciones si no es cliente
        if (!$customer) {
            return redirect()->route('dashboard')->with('error', 'Solo los perfiles de cliente tienen historial de cotizaciones.');
        }

        // Agregamos paginación en lugar de get() para que no se rompa si el cliente tiene 500 cotizaciones
        $quotations = $customer->quotations()->latest()->paginate(15);

        return view('quotations.index', compact('quotations'));
    }

    // Método para convertir cotización en pedido (será llamado desde Chat 3)
    public function convertToOrder(Quotation $quotation)
    {
        // Lógica de conversión (a implementar en Chat 3)
        return redirect()->back()->with('info', 'Funcionalidad en desarrollo.');
    }

    /**
     * Muestra el detalle de una cotización.
     */
    public function show(Quotation $quotation)
    {
        if ($this->isNotOwner($quotation)) {
            return redirect()->route('quotations.index')
                ->with('error', 'Acceso denegado. Esta cotización no pertenece a tu cuenta.');
        }

        return view('quotations.show', compact('quotation'));
    }

    /**
     * Nuevo método para descargar archivos usando Spatie
     */
    public function downloadAttachment(Quotation $quotation, Media $media)
    {
        if ($this->isNotOwner($quotation)) {
            abort(403, 'No tienes permiso para descargar este archivo.');
        }

        // Validamos que el archivo realmente le pertenezca a esta cotización
        abort_unless(
            $media->model_id === $quotation->id && 
            $media->model_type === Quotation::class && 
            $media->collection_name === 'quotation_files',
            404
        );

        return $media; // Spatie maneja la descarga automáticamente al retornar el modelo
    }

    /**
     * Verifica si la cotización NO pertenece al cliente autenticado.
     */
    private function isNotOwner(Quotation $quotation): bool
    {
        $customer = Auth::user()->customer;
        
        // Si no hay perfil de cliente, o el ID no coincide, bloqueamos el acceso
        return !$customer || $quotation->customer_id !== $customer->id;
    }
}