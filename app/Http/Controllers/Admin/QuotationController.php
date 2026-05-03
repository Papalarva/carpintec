<?php

namespace App\Http\Controllers\Admin;

use App\Enums\QuotationStatus;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $quotations = Quotation::query()
            ->with(['customer.user', 'product'])
            ->when($search, function ($q) use ($search) {
                $q->where('subject', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%")
                  ->orWhereHas('customer.user', function ($q) use ($search) {
                      $q->where('first_name', 'ilike', "%{$search}%")
                        ->orWhere('last_name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%");
                  });
            })
            ->when($status && in_array($status, ['pending','reviewing','quoted','approved','rejected']), function ($q) use ($status) {
            })
            ->latest()
            ->paginate(15)
            ->appends(['search' => $search, 'status' => $status]);

        return view('admin.quotations.index', compact('quotations', 'search', 'status'));
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['customer.user', 'product', 'media']);
        return view('admin.quotations.show', compact('quotation'));
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status'          => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\QuotationStatus::class)],
            'estimated_price' => 'nullable|numeric|min:0', // <-- Agregamos validación del precio
            'response'        => 'nullable|string|max:5000',
            'files.*'         => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx',
        ]);

        $quotation->update([
            'status'          => $validated['status'],
            // Guardamos el precio si se envió, si no, mantenemos el que estaba
            'estimated_price' => $validated['estimated_price'] ?? $quotation->estimated_price, 
            'response'        => $validated['response'] ?? $quotation->response,
        ]);

        // Adjuntar archivos de respuesta usando Spatie
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $quotation->addMedia($file)->toMediaCollection('quotation_files');
            }
        }

        return redirect()->route('admin.quotations.show', $quotation)
                         ->with('success', 'Cotización actualizada correctamente.');
    }

    // Método preparado para el futuro flujo de conversión a orden
    public function convertToOrder(Quotation $quotation)
    {
        // Aquí se implementará la lógica de creación de orden (viene en la Tarea 6)
        // Por ahora, solo devolvemos un mensaje informativo
        return back()->with('info', 'La funcionalidad de conversión a pedido estará disponible próximamente.');
    }

    // Quitamos la inyección "Media $media" y recibimos solo el ID "$mediaId"
    public function downloadFile(Quotation $quotation, $mediaId)
    {
        // AHORA USAMOS TU NUEVO MODELO QUE ENTIENDE UUIDs
        $media = \App\Models\Media::findOrFail($mediaId);

        if ((string) $media->model_id !== (string) $quotation->id) {
            abort(403, 'Permiso denegado: Este archivo no pertenece a la cotización actual.');
        }

        $path = $media->getPath();

        if (!file_exists($path)) {
            return back()->with('error', 'El archivo físico no se encuentra en el servidor en la ruta: ' . $path);
        }

        return response()->download($path, $media->file_name);
    }
}