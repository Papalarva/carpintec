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
     * Descarga archivos adjuntos de forma segura (Compatible con UUID).
     */
    public function downloadAttachment(Quotation $quotation, $mediaId)
    {
        // 1. Seguridad: Verificar que el cliente logueado sea el dueño de la cotización
        if ($this->isNotOwner($quotation)) {
            abort(403, 'No tienes permiso para descargar los archivos de esta cotización.');
        }

        // 2. Usar el modelo de Media personalizado que entiende UUIDs
        $media = \App\Models\Media::findOrFail($mediaId);

        // 3. Validación estricta casteando a string para evitar falsos negativos
        if ((string) $media->model_id !== (string) $quotation->id) {
            abort(403, 'Permiso denegado: Este archivo no pertenece a la cotización actual.');
        }

        // 4. Obtener la ruta física del archivo
        $path = $media->getPath();

        // 5. Manejo de error silencioso con Toast si el archivo no existe físicamente
        if (!file_exists($path)) {
            return back()->with('error', 'El archivo físico ya no se encuentra disponible en el servidor.');
        }

        // 6. Forzar la descarga con su nombre original
        return response()->download($path, $media->file_name);
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