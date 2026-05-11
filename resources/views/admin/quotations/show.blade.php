@extends('layouts.admin')

@section('title', 'Cotización #' . substr($quotation->id, 0, 8))
@section('header', 'Detalle de Cotización')

@section('content') 

@if (session('error'))
    <div class="mb-8 bg-red-50 border-l-4 border-red-700 p-4 rounded-r-xl shadow-sm">
        <p class="font-bold text-red-800 font-serif">Error</p>
        <p class="text-red-700 font-sans text-sm">{{ session('error') }}</p>
    </div>
@endif

@if (session('success'))
    <div class="mb-8 bg-green-50 border-l-4 border-green-700 p-4 rounded-r-xl shadow-sm">
        <p class="font-bold text-green-800 font-serif">¡Éxito!</p>
        <p class="text-green-700 font-sans text-sm">{{ session('success') }}</p>
    </div>
@endif

{{-- Envolvemos todo en un estado de Alpine para el Lightbox --}}
<div x-data="{ lightboxOpen: false, lightboxImage: '' }">

    {{-- EL VISOR DE IMÁGENES (LIGHTBOX) --}}
    <div x-show="lightboxOpen" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/90 backdrop-blur-md" 
         x-cloak>
        
        <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-gray-400 hover:text-white transition-colors bg-white/10 hover:bg-white/20 p-2 rounded-full backdrop-blur-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <img :src="lightboxImage" @click.away="lightboxOpen = false" class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg shadow-2xl transition-transform duration-500 scale-100">
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Columna Principal --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Ficha Técnica --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 border-t-4 border-t-amber-900">
                <h2 class="text-2xl font-semibold text-gray-900 font-serif">{{ $quotation->subject }}</h2>
                <div class="mt-4 bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <p class="text-gray-700 font-sans leading-relaxed whitespace-pre-line">{{ $quotation->description }}</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mt-8">
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Producto Base</span>
                        @if($quotation->product)
                            <a href="{{ route('admin.products.edit', $quotation->product) }}" target="_blank" class="text-amber-900 hover:text-amber-800 font-medium flex items-center gap-1 transition-colors font-sans text-sm">
                                {{ $quotation->product->name }}
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path></svg>
                            </a>
                        @else
                            <span class="text-gray-900 font-medium font-sans text-sm">Proyecto a medida</span>
                        @endif
                    </div>
                    
                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Estado</span>
                        <x-admin.badge :color="$quotation->status->color()" :label="$quotation->status->label()" />
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Actualizado</span>
                        <span class="text-gray-900 font-medium font-sans text-sm">{{ $quotation->updated_at->diffForHumans() }}</span>
                    </div>
                </div>

                {{-- Sistema de Archivos Inteligente --}}
                @php
                    $allMedia = $quotation->getMedia('quotation_files');
                    
                    $customerFiles = $allMedia->filter(fn($m) => $m->created_at->diffInMinutes($quotation->created_at) < 5);
                    $adminFiles = $allMedia->reject(fn($m) => $m->created_at->diffInMinutes($quotation->created_at) < 5);

                    $splitMedia = function($mediaCollection) {
                        return [
                            'images' => $mediaCollection->filter(fn($m) => str_starts_with($m->mime_type, 'image/')),
                            'docs' => $mediaCollection->reject(fn($m) => str_starts_with($m->mime_type, 'image/')),
                        ];
                    };

                    $customerMedia = $splitMedia($customerFiles);
                    $adminMedia = $splitMedia($adminFiles);
                @endphp

                @if($customerFiles->count() > 0)
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h4 class="font-medium text-gray-900 mb-4 font-serif flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            Archivos enviados por el Cliente
                        </h4>
                        
                        {{-- Galería Elegante de Imágenes del Cliente --}}
                        @if($customerMedia['images']->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                @foreach($customerMedia['images'] as $img)
                                    <div class="relative group rounded-xl overflow-hidden shadow-sm border border-gray-200 block bg-gray-50">
                                        <img src="{{ $img->getUrl() }}" class="w-full h-32 object-cover transition-transform duration-500 group-hover:scale-110" alt="Imagen adjunta">
                                        
                                        {{-- Capa interactiva --}}
                                        <div class="absolute inset-0 bg-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3 backdrop-blur-[2px]">
                                            {{-- Botón Visualizar --}}
                                            <button type="button" @click="lightboxImage = '{{ $img->getUrl() }}'; lightboxOpen = true" class="p-2 bg-white/20 hover:bg-white text-white hover:text-amber-900 rounded-full transition-colors shadow-sm" title="Pantalla Completa">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"></path></svg>
                                            </button>
                                            {{-- Botón Descargar --}}
                                            <a href="{{ route('admin.quotations.download-file', [$quotation, $img->id]) }}" class="p-2 bg-white/20 hover:bg-white text-white hover:text-amber-900 rounded-full transition-colors shadow-sm" title="Descargar Imagen">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Lista de Documentos del Cliente --}}
                        @if($customerMedia['docs']->count() > 0)
                            <ul class="flex flex-col gap-2">
                                @foreach($customerMedia['docs'] as $doc)
                                    <li>
                                        <a href="{{ route('admin.quotations.download-file', [$quotation, $doc->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition-colors font-sans w-full md:w-auto">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                            {{ $doc->file_name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                @if($adminFiles->count() > 0)
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h4 class="font-medium text-gray-900 mb-4 font-serif flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Archivos enviados por Carpintec (Respuestas)
                        </h4>

                        {{-- Galería Elegante de Imágenes Admin --}}
                        @if($adminMedia['images']->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                @foreach($adminMedia['images'] as $img)
                                    <div class="relative group rounded-xl overflow-hidden shadow-sm border border-amber-200 block bg-amber-50">
                                        <img src="{{ $img->getUrl() }}" class="w-full h-32 object-cover transition-transform duration-500 group-hover:scale-110" alt="Imagen adjunta">
                                        
                                        {{-- Capa interactiva --}}
                                        <div class="absolute inset-0 bg-amber-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3 backdrop-blur-[2px]">
                                            {{-- Botón Visualizar --}}
                                            <button type="button" @click="lightboxImage = '{{ $img->getUrl() }}'; lightboxOpen = true" class="p-2 bg-white/20 hover:bg-white text-white hover:text-amber-900 rounded-full transition-colors shadow-sm" title="Pantalla Completa">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"></path></svg>
                                            </button>
                                            {{-- Botón Descargar --}}
                                            <a href="{{ route('admin.quotations.download-file', [$quotation, $img->id]) }}" class="p-2 bg-white/20 hover:bg-white text-white hover:text-amber-900 rounded-full transition-colors shadow-sm" title="Descargar Imagen">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Lista Documentos Admin --}}
                        @if($adminMedia['docs']->count() > 0)
                            <ul class="flex flex-col gap-2">
                                @foreach($adminMedia['docs'] as $doc)
                                    <li>
                                        <a href="{{ route('admin.quotations.download-file', [$quotation, $doc->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-100 rounded-lg text-sm text-amber-900 hover:bg-amber-100 transition-colors font-sans w-full md:w-auto">
                                            <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                            {{ $doc->file_name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Panel de Negociación --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8" x-data="{ currentStatus: '{{ $quotation->status->value }}' }">
                <h3 class="text-xl font-medium text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">Panel de Negociación</h3>

                <form method="POST" action="{{ route('admin.quotations.update-status', $quotation) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Actualizar Estado</label>
                            <select name="status" id="status" x-model="currentStatus" required class="block w-full rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors font-sans bg-gray-50">
                                @foreach(\App\Enums\QuotationStatus::cases() as $status)
                                    <option value="{{ $status->value }}">
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Dinámico de Precio --}}
                        <div x-show="currentStatus === 'quoted' || currentStatus === 'approved'" x-transition.opacity x-cloak>
                            <label for="estimated_price" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Precio Estimado Final (MXN)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="estimated_price" id="estimated_price" 
                                       :required="currentStatus === 'quoted' || currentStatus === 'approved' ? true : null"
                                       value="{{ old('estimated_price', $quotation->estimated_price) }}" 
                                       class="block w-full pl-8 rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans" 
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="response" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Respuesta (Visible para el cliente en su panel)</label>
                        <textarea name="response" id="response" rows="5"
                                  class="block w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans"
                                  placeholder="Describe aquí los detalles técnicos del presupuesto, materiales, y tiempos estimados de producción..."
                        >{{ old('response', $quotation->response) }}</textarea>
                        @error('response') <p class="text-red-600 text-xs mt-2 font-sans">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-8" x-data="{ files: [] }">
                        <label class="block text-sm font-medium text-gray-700 mb-2 font-sans">Adjuntar propuesta técnica o render</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-2xl bg-gray-50 hover:bg-gray-100 transition-colors relative group">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-amber-800 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true" stroke-width="1.5">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center font-sans">
                                    <label for="files" class="relative cursor-pointer bg-transparent rounded-md font-medium text-amber-900 hover:text-amber-800 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-amber-800">
                                        <span>Selecciona archivos</span>
                                        <input id="files" name="files[]" type="file" multiple class="sr-only" @change="files = Array.from($event.target.files)">
                                    </label>
                                    <p class="pl-1">o arrastra y suelta aquí</p>
                                </div>
                                <p class="text-xs text-gray-500 font-sans">PDF, JPG, PNG hasta 10MB</p>
                            </div>
                        </div>
                        
                        {{-- Previsualización lista de archivos --}}
                        <template x-if="files.length > 0">
                            <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-100">
                                <p class="text-sm font-medium text-amber-900 mb-2 font-sans">Archivos listos para adjuntar:</p>
                                <ul class="text-sm text-amber-800 space-y-1 font-sans list-disc pl-5">
                                    <template x-for="file in files" :key="file.name">
                                        <li x-text="file.name"></li>
                                    </template>
                                </ul>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="w-full md:w-auto bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm font-sans">
                            Guardar Cotización
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Columna Lateral --}}
        <div class="space-y-8">
            
            @if($quotation->status === \App\Enums\QuotationStatus::APPROVED)
                <div class="bg-green-50 border-2 border-green-500 rounded-2xl shadow-sm p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-green-900 font-serif font-bold text-lg mb-2">¡Cotización Aprobada!</h3>
                    <p class="text-green-700 text-sm mb-6 font-sans">El cliente ha dado luz verde. Es momento de pasar esta cotización a producción.</p>
                    <form method="POST" action="{{ route('admin.quotations.convert-to-order', $quotation) }}">
                        @csrf
                        <button type="submit" class="w-full bg-green-700 text-white font-bold px-6 py-4 rounded-xl hover:bg-green-800 shadow-sm flex items-center justify-center gap-2 transition-colors uppercase tracking-widest text-sm font-sans">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Generar Pedido
                        </button>
                    </form>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-8 py-5 bg-gray-50/50">
                    <h3 class="text-lg font-medium text-gray-900 font-serif flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                        Datos del Cliente
                    </h3>
                </div>
                <div class="p-8 space-y-6">
                    @php $user = $quotation->customer->user; @endphp
                    
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Nombre Completo</p>
                        <p class="text-sm text-gray-900 font-medium font-sans">
                            {{ $user?->first_name ?? 'Usuario' }} {{ $user?->last_name ?? 'Eliminado' }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Correo Electrónico</p>
                        <p class="text-sm text-gray-900 font-sans">{{ $user?->email ?? 'No disponible' }}</p>
                    </div>
                    
                    @if($user?->phone)
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Teléfono Directo</p>
                        <p class="text-sm text-gray-900 font-sans">{{ $user->phone }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection