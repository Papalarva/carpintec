@extends('layouts.admin')

@section('title', 'Cotización #' . substr($quotation->id, 0, 8))
@section('header', 'Cotización de ' . ($quotation->customer->user?->first_name ?? 'Usuario Eliminado'))
@section('content') 

{{-- ALERTAS DE SISTEMA --}}
@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
        <p class="font-bold">Error</p>
        <p>{{ session('error') }}</p>
    </div>
@endif

@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
        <p class="font-bold">¡Éxito!</p>
        <p>{{ session('success') }}</p>
    </div>
@endif
 
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Columna Principal: Información y Respuestas --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Ficha Técnica de la Cotización --}}
        <div class="bg-white rounded-lg shadow p-6 border-t-4 border-indigo-500">
            <h2 class="text-xl font-bold text-gray-900">{{ $quotation->subject }}</h2>
            <p class="text-gray-600 mt-2 bg-gray-50 p-4 rounded-md border border-gray-100 whitespace-pre-line">{{ $quotation->description }}</p>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mt-6 text-sm">
                <div>
                    <span class="block font-semibold text-gray-500 uppercase tracking-wider text-xs mb-1">Producto Base</span>
                    <span>
                        {{-- Sugerencia 3: Enlace directo al producto si existe --}}
                        @if($quotation->product)
                            <a href="{{ route('admin.products.edit', $quotation->product) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-medium flex items-center gap-1">
                                {{ $quotation->product->name }}
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @else
                            <span class="text-gray-700 font-medium">Proyecto a medida</span>
                        @endif
                    </span>
                </div>
                
                <div>
                    <span class="block font-semibold text-gray-500 uppercase tracking-wider text-xs mb-1">Estado</span>
                    <x-admin.badge :color="$quotation->status->color()" :label="$quotation->status->label()" />
                </div>

                <div>
                    {{-- Sugerencia 5: Historial visual y última actualización --}}
                    <span class="block font-semibold text-gray-500 uppercase tracking-wider text-xs mb-1">Última actualización</span>
                    <span class="text-gray-700">{{ $quotation->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            {{-- Sugerencia 2: Separación inteligente de archivos --}}
            @php
                $allMedia = $quotation->getMedia('quotation_files');
                // Asumimos que los archivos subidos en los primeros 5 mins son del cliente
                $customerFiles = $allMedia->filter(fn($m) => $m->created_at->diffInMinutes($quotation->created_at) < 5);
                $adminFiles = $allMedia->reject(fn($m) => $m->created_at->diffInMinutes($quotation->created_at) < 5);
            @endphp

            @if($customerFiles->count() > 0)
                <div class="mt-6 border-t pt-4">
                    <h4 class="font-medium text-gray-700 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        Archivos del Cliente
                    </h4>
                    <ul class="flex flex-wrap gap-2 text-sm text-gray-600">
                        @foreach($customerFiles as $media)
                            <li class="bg-gray-100 border rounded-md px-3 py-2 flex items-center hover:bg-gray-200 transition">
                                <a href="{{ route('admin.quotations.download-file', [$quotation, $media]) }}" class="text-indigo-600 font-medium">
                                    {{ $media->file_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($adminFiles->count() > 0)
                <div class="mt-4 border-t pt-4">
                    <h4 class="font-medium text-gray-700 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Tus Archivos (Respuestas)
                    </h4>
                    <ul class="flex flex-wrap gap-2 text-sm text-gray-600">
                        @foreach($adminFiles as $media)
                            <li class="bg-indigo-50 border border-indigo-100 rounded-md px-3 py-2 flex items-center">
                                <a href="{{ route('admin.quotations.download-file', [$quotation, $media]) }}" class="text-indigo-700 font-medium">
                                    {{ $media->file_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Formulario de Trabajo --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Panel de Negociación</h3>

            <form method="POST" action="{{ route('admin.quotations.update-status', $quotation) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Actualizar Estado</label>
                        <select name="status" id="status" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(\App\Enums\QuotationStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ $quotation->status === $status ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sugerencia 1: Input Dinámico de Precio --}}
                    <div id="price-container">
                        <label for="estimated_price" class="block text-sm font-medium text-gray-700">Precio Estimado (MXN)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="estimated_price" id="estimated_price" 
                                   value="{{ old('estimated_price', $quotation->estimated_price) }}" 
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 rounded-md" 
                                   placeholder="0.00">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="response" class="block text-sm font-medium text-gray-700">Respuesta (Visible para el cliente en su panel)</label>
                    <textarea name="response" id="response" rows="5"
                              class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Escribe aquí los detalles del presupuesto, tiempos de entrega, etc..."
                    >{{ old('response', $quotation->response) }}</textarea>
                    @error('response') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4 bg-gray-50 p-4 rounded border border-gray-200">
                    <label for="files" class="block text-sm font-medium text-gray-700">
                        Subir nuevos archivos a la cotización
                    </label>
                    <input type="file" name="files[]" id="files" multiple
                           class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                    <p class="text-xs text-gray-500 mt-2">Puedes enviar PDFs con presupuestos formales o imágenes de render. Máx 10MB.</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-medium shadow-sm transition">
                        Guardar y Actualizar Cotización
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Columna Lateral: Acciones y Cliente --}}
    <div class="space-y-6">
        
        {{-- Botón de Generar Orden (Destacado) --}}
        @if($quotation->status === \App\Enums\QuotationStatus::APPROVED)
            <div class="bg-green-50 border-2 border-green-500 rounded-lg shadow-sm p-4 text-center">
                <h3 class="text-green-800 font-bold mb-2">¡Cotización Aprobada!</h3>
                <p class="text-green-700 text-sm mb-4">El cliente aceptó el precio. Convierte esta cotización en un pedido formal.</p>
                <form method="POST" action="{{ route('admin.quotations.convert-to-order', $quotation) }}">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 text-white font-bold px-4 py-3 rounded hover:bg-green-700 shadow flex items-center justify-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Generar Pedido
                    </button>
                </form>
            </div>
        @endif

        {{-- Sugerencia 4: Datos del Cliente (Solo lectura, estilo profesional) --}}
        <div class="bg-white rounded-lg shadow">
            <div class="border-b px-6 py-4">
                <h3 class="text-md font-medium text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Contacto del Cliente
                </h3>
            </div>
            <div class="p-6 space-y-4">
                @php $user = $quotation->customer->user; @endphp
                
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre Completo</p>
                    <p class="text-sm text-gray-900 font-medium mt-1">
                        {{ $user?->first_name ?? 'Usuario' }} {{ $user?->last_name ?? 'Eliminado' }}
                    </p>
                </div>
                
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Correo Electrónico</p>
                    <p class="text-sm text-gray-900 mt-1">{{ $user?->email ?? 'No disponible' }}</p>
                </div>
                
                @if($user?->phone)
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</p>
                    <p class="text-sm text-gray-900 mt-1">{{ $user->phone }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Sugerencia 1: JS para mostrar/ocultar el precio según el estado --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const priceContainer = document.getElementById('price-container');
        const priceInput = document.getElementById('estimated_price');

        function togglePriceVisibility() {
            const status = statusSelect.value;
            // Mostrar precio si el estado es 'Cotizada' o 'Aprobada'
            if (status === 'quoted' || status === 'approved') {
                priceContainer.style.display = 'block';
                // Si cambiamos a cotizada, obligamos al administrador a poner un precio mayor a 0
                priceInput.setAttribute('required', 'required');
            } else {
                priceContainer.style.display = 'none';
                priceInput.removeAttribute('required');
            }
        }

        // Ejecutar al cargar la página
        togglePriceVisibility();

        // Ejecutar cada vez que el usuario cambie el select
        statusSelect.addEventListener('change', togglePriceVisibility);
    });
</script>
@endsection