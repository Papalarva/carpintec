<?php

namespace App\Http\Controllers\Admin;

use App\Enums\QuotationStatus;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'desc'); // Por defecto, las más recientes

        $query = Quotation::query()
            ->with(['customer.user', 'product']);

        // 1. Filtro de Búsqueda
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%")
                  ->orWhereHas('customer.user', function ($q) use ($search) {
                      $q->where('first_name', 'ilike', "%{$search}%")
                        ->orWhere('last_name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%");
                  });
            });
        }

        // 2. Filtro de Estado
        if ($status && in_array($status, ['pending','reviewing','quoted','approved','rejected'])) {
            $query->where('status', $status);
        }

        // 3. Ordenamiento Dinámico (Whitelist)
        $allowedSorts = ['subject', 'status', 'estimated_price', 'created_at'];
        
        if ($sort && in_array($sort, $allowedSorts)) {
            $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        // 4. Paginación preservando URL
        $quotations = $query->paginate(15)->appends(['search' => $search, 'status' => $status, 'sort' => $sort, 'direction' => $direction]);

        return view('admin.quotations.index', compact('quotations', 'search', 'status'));
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status'          => ['required', Rule::enum(QuotationStatus::class)],
            'estimated_price' => 'nullable|numeric|min:0',
            'response'        => 'nullable|string|max:5000',
            'files.*'         => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx',
        ]);

        $quotation->update([
            'status'          => $validated['status'],
            'estimated_price' => $validated['estimated_price'] ?? $quotation->estimated_price, 
            'response'        => $validated['response'] ?? $quotation->response,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $quotation->addMedia($file)->toMediaCollection('admin_quotation_files', 'public');
            }
        }

        return redirect()->route('admin.quotations.show', $quotation)
                         ->with('success', 'Cotización actualizada correctamente.');
    }

    public function convertToOrder(Quotation $quotation)
    {
        return back()->with('info', 'La funcionalidad de conversión a pedido estará disponible próximamente.');
    }

    public function downloadFile(Quotation $quotation, $mediaId)
    {
        $media = \App\Models\Media::findOrFail($mediaId);

        if ((string) $media->model_id !== (string) $quotation->id) {
            abort(403, 'Permiso denegado: Este archivo no pertenece a la cotización actual.');
        }

        $path = $media->getPath();

        if (!file_exists($path)) {
            return back()->with('error', 'El archivo físico no se encuentra en el servidor.');
        }

        return response()->download($path, $media->file_name);
    } 

    public function show(Quotation $quotation)
    {
        $quotation->load([
            'customer.user' => function($query) {
                $query->withTrashed();
            }, 
            'product', 
            'media',
            'messages.media' // Cargamos las imágenes del chat
        ]);
        
        return view('admin.quotations.show', compact('quotation'));
    }

    public function sendMessage(Request $request, Quotation $quotation)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'chat_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $message = $quotation->messages()->create([
            'sender_type' => 'admin',
            'message'     => strip_tags($request->message),
        ]);

        if ($request->hasFile('chat_image')) {
            $message->addMediaFromRequest('chat_image')->toMediaCollection('chat_images', 'public');
        }

        return back()->with('success', 'Mensaje enviado al cliente.');
    }
}