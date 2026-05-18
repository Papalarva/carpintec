@extends('layouts.admin')

@section('title', 'Cotización #' . substr($quotation->id, 0, 8))
@section('header', 'Gestión de Proyecto')

@section('content') 

{{-- ESTADO GLOBAL Y CONTROLADOR DE LIGHTBOX CON BLOQUEO DE SCROLL --}}
<div x-data="{ 
    lightboxOpen: false, 
    lightboxImage: '',
    init() {
        this.$watch('lightboxOpen', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    }
}">

    {{-- LIGHTBOX GLOBAL --}}
    <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-10" x-cloak>
        <div x-show="lightboxOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/95 backdrop-blur-sm" @click="lightboxOpen = false"></div>
        <button @click="lightboxOpen = false" class="absolute top-6 right-6 z-10 text-gray-400 hover:text-white transition-colors bg-white/10 hover:bg-white/20 p-3 rounded-full backdrop-blur-sm focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <img x-show="lightboxOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100" :src="lightboxImage" class="relative z-10 max-w-full max-h-full object-contain rounded-xl shadow-2xl">
    </div>

    {{-- SEPARACIÓN EXACTA DE ARCHIVOS POR COLECCIÓN --}}
    @php
        // Archivos del cliente (su bolsa)
        $customerFiles = $quotation->getMedia('quotation_files');
        // Archivos oficiales de Carpintec (su bolsa)
        $adminFiles = $quotation->getMedia('admin_quotation_files');

        $getImages = fn($c) => $c->filter(fn($m) => str_starts_with($m->mime_type, 'image/'));
        $getDocs = fn($c) => $c->reject(fn($m) => str_starts_with($m->mime_type, 'image/'));

        $customerImages = $getImages($customerFiles);
        $customerDocs = $getDocs($customerFiles);
        $adminImages = $getImages($adminFiles);
        $adminDocs = $getDocs($adminFiles);
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- ========================================== --}}
        {{-- COLUMNA IZQUIERDA: EXPEDIENTE Y VEREDICTO --}}
        {{-- ========================================== --}}
        <div class="lg:col-span-5 space-y-6">
            
            {{-- Encabezado y Estado --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 border-t-4 border-t-amber-900">
                <div class="flex justify-between items-start mb-4">
                    <x-admin.badge :color="$quotation->status->color()" :label="$quotation->status->label()" />
                    <span class="text-xs text-gray-400 font-medium">{{ $quotation->created_at->format('d M, Y') }}</span>
                </div>
                <h2 class="text-xl font-bold text-gray-900 font-serif leading-tight">{{ $quotation->subject }}</h2>
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-sm font-sans">
                    <span class="text-gray-500">Producto Base:</span>
                    @if($quotation->product)
                        <a href="{{ route('admin.products.edit', $quotation->product) }}" target="_blank" class="text-amber-900 hover:text-amber-700 font-bold flex items-center gap-1 transition-colors">
                            {{ $quotation->product->name }}
                        </a>
                    @else
                        <span class="text-gray-900 font-bold">A Medida</span>
                    @endif
                </div>
            </div>

            {{-- Datos del Cliente --}}
            @php $user = $quotation->customer->user; @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 font-sans">
                <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2 border-b border-gray-100 pb-3">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                    Cliente
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Nombre:</span>
                        <span class="font-medium text-gray-900">{{ $user?->first_name ?? 'Usuario' }} {{ $user?->last_name ?? '' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Email:</span>
                        <span class="font-medium text-gray-900">{{ $user?->email ?? 'N/A' }}</span>
                    </div>
                    @if($user?->phone)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Teléfono:</span>
                        <span class="font-medium text-gray-900">{{ $user->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Petición Original y Archivos del Cliente --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4 border-b border-gray-100 pb-3">Solicitud Original</h3>
                <p class="text-gray-700 font-sans text-sm leading-relaxed whitespace-pre-line mb-4">{{ $quotation->description }}</p>
                
                @if($customerImages->count() > 0)
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        @foreach($customerImages as $img)
                            <div class="relative group rounded-lg overflow-hidden border border-gray-200 aspect-square cursor-zoom-in shadow-sm" @click="lightboxImage = '{{ $img->getUrl() }}'; lightboxOpen = true">
                                <img src="{{ $img->getUrl() }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                            </div>
                        @endforeach
                    </div>
                @endif
                
                @if($customerDocs->count() > 0)
                    <ul class="space-y-2">
                        @foreach($customerDocs as $doc)
                            <li>
                                <a href="{{ route('admin.quotations.download-file', [$quotation, $doc->id]) }}" target="_blank" class="flex items-center p-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                    <span class="text-xs font-medium text-gray-700 truncate">{{ $doc->file_name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Material Oficial de Carpintec --}}
            @if($adminImages->count() > 0 || $adminDocs->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 border-b border-gray-100 pb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                        Material Oficial Entregado
                    </h3>
                    
                    @if($adminImages->count() > 0)
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            @foreach($adminImages as $img)
                                <div class="relative group rounded-lg overflow-hidden border border-amber-200/60 aspect-square cursor-zoom-in shadow-sm" @click="lightboxImage = '{{ $img->getUrl() }}'; lightboxOpen = true">
                                    <img src="{{ $img->getUrl() }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    @if($adminDocs->count() > 0)
                        <ul class="space-y-2">
                            @foreach($adminDocs as $doc)
                                <li>
                                    <a href="{{ route('admin.quotations.download-file', [$quotation, $doc->id]) }}" target="_blank" class="flex items-center p-2.5 border border-amber-200/60 rounded-lg hover:bg-amber-50 transition-colors shadow-sm">
                                        <svg class="w-4 h-4 text-amber-700 mr-2 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                        <span class="text-xs font-medium text-amber-900 truncate">{{ $doc->file_name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            {{-- RESOLUCIÓN OFICIAL (Veredicto) --}}
            <div class="bg-amber-50/50 rounded-2xl shadow-sm border border-amber-200 p-6" x-data="{ currentStatus: '{{ $quotation->status->value }}', isSubmitting: false }">
                <h3 class="text-lg font-bold text-amber-900 mb-6 font-serif border-b border-amber-200/60 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Veredicto Oficial
                </h3>

                <form method="POST" action="{{ route('admin.quotations.update-status', $quotation) }}" enctype="multipart/form-data" class="space-y-5" @submit="isSubmitting = true">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-amber-800 uppercase tracking-widest mb-2 font-sans">Estado del Proyecto</label>
                        <select name="status" x-model="currentStatus" required class="block w-full rounded-xl border-amber-200 py-3 text-sm focus:border-amber-800 focus:ring-amber-800 shadow-sm font-sans bg-white">
                            @foreach(\App\Enums\QuotationStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="currentStatus === 'quoted' || currentStatus === 'approved'" x-transition x-cloak>
                        <label class="block text-xs font-bold text-amber-800 uppercase tracking-widest mb-2 font-sans">Presupuesto Final (MXN)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-medium">$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="estimated_price" 
                                   :required="currentStatus === 'quoted' || currentStatus === 'approved' ? true : null"
                                   value="{{ old('estimated_price', $quotation->estimated_price) }}" 
                                   class="block w-full pl-8 rounded-xl border-amber-200 bg-white text-base py-3 focus:ring-amber-800 focus:border-amber-800 shadow-sm font-sans font-bold text-gray-900" 
                                   placeholder="0.00">
                        </div>
                    </div>

                    <div x-data="{ files: [] }">
                        <label class="block text-xs font-bold text-amber-800 uppercase tracking-widest mb-2 font-sans">Adjuntar Cotización Formal / Renders</label>
                        <div class="mt-1 flex justify-center px-4 py-4 border-2 border-amber-200 border-dashed rounded-xl bg-white hover:bg-amber-50 transition-colors relative group cursor-pointer">
                            <div class="text-center">
                                <label for="official_files" class="relative cursor-pointer text-sm font-medium text-amber-900 hover:text-amber-800 focus-within:outline-none">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                                        Subir Archivos Oficiales
                                    </span>
                                    <input id="official_files" name="files[]" type="file" multiple class="sr-only" @change="files = Array.from($event.target.files)">
                                </label>
                            </div>
                        </div>
                        <template x-if="files.length > 0">
                            <ul class="mt-2 text-xs text-amber-800 space-y-1 font-sans bg-white p-3 rounded-xl border border-amber-100 shadow-sm">
                                <template x-for="file in files" :key="file.name"><li x-text="file.name" class="truncate font-medium"></li></template>
                            </ul>
                        </template>
                    </div>

                    <button type="submit" :disabled="isSubmitting" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="w-full bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl py-4 transition-colors shadow-sm mt-4 flex items-center justify-center font-sans">
                        <span x-show="!isSubmitting">Actualizar y Notificar</span>
                        <span x-show="isSubmitting" x-cloak class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Procesando...
                        </span>
                    </button>
                </form>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- COLUMNA DERECHA: CHAT DE NEGOCIACIÓN --}}
        {{-- ========================================== --}}
        <div class="lg:col-span-7 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-[800px]">
            
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"></path></svg>
                    <h3 class="text-lg font-bold text-gray-900 font-sans">Comunicación Activa</h3>
                </div>
            </div>

            {{-- Historial de Chat --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50/50" id="admin-chat-container">
                
                {{-- Legacy Response --}}
                @if($quotation->response)
                    <div class="flex justify-end">
                        <div class="bg-gray-100 border border-gray-200 rounded-2xl rounded-tr-sm p-4 max-w-[85%] shadow-sm">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 font-sans">Nota Histórica</p>
                            <p class="text-sm text-gray-800 whitespace-pre-line font-sans">{{ $quotation->response }}</p>
                        </div>
                    </div>
                @endif

                @forelse($quotation->messages ?? [] as $msg)
                        @if($msg->sender_type === 'customer')
                            <div class="flex justify-start">
                                <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-sm p-4 max-w-[85%] shadow-sm">
                                    <div class="flex justify-between items-baseline mb-1 gap-4">
                                        <p class="text-[10px] font-bold text-slate-700 uppercase tracking-widest font-sans">Cliente</p>
                                        <span class="text-[9px] text-gray-400 font-sans">{{ $msg->created_at->format('d/m H:i') }}</span>
                                    </div>
                                    @if($msg->hasMedia('chat_images'))
                                        <div class="mb-2 mt-1 cursor-zoom-in overflow-hidden rounded-xl border border-gray-100" @click="lightboxImage = '{{ $msg->getFirstMediaUrl('chat_images') }}'; lightboxOpen = true">
                                            <img src="{{ $msg->getFirstMediaUrl('chat_images') }}" class="w-full h-auto max-h-48 object-cover hover:scale-105 transition-transform duration-500">
                                        </div>
                                    @endif
                                    <p class="text-sm text-gray-800 whitespace-pre-line font-sans">{{ $msg->message }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-end">
                                <div class="bg-gray-900 text-white rounded-2xl rounded-tr-sm p-4 max-w-[85%] shadow-sm">
                                    <div class="flex justify-between items-baseline mb-1 gap-4">
                                        <p class="text-[10px] font-bold text-amber-400 uppercase tracking-widest font-sans">Taller (Carpintec)</p>
                                        <span class="text-[9px] text-gray-400 font-sans">{{ $msg->created_at->format('d/m H:i') }}</span>
                                    </div>
                                    @if($msg->hasMedia('chat_images'))
                                        <div class="mb-2 mt-1 cursor-zoom-in overflow-hidden rounded-xl border border-gray-700" @click="lightboxImage = '{{ $msg->getFirstMediaUrl('chat_images') }}'; lightboxOpen = true">
                                            <img src="{{ $msg->getFirstMediaUrl('chat_images') }}" class="w-full h-auto max-h-48 object-cover hover:scale-105 transition-transform duration-500">
                                        </div>
                                    @endif
                                    <p class="text-sm whitespace-pre-line font-sans">{{ $msg->message }}</p>
                                </div>
                            </div>
                        @endif
                @empty
                    <div class="text-center text-gray-400 text-sm py-4 italic mt-10 font-sans">
                        La comunicación iniciará cuando envíes el primer mensaje.
                    </div>
                @endforelse
            </div>

            {{-- Input de Chat con Prevención de Doble Envío --}}
            <div class="p-4 border-t border-gray-100 bg-white shrink-0" x-data="{ imagePreview: null, isSubmitting: false }">
                <form action="{{ route('admin.quotations.message', $quotation) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2" @submit="isSubmitting = true">
                    @csrf
                    
                    <div x-show="imagePreview" style="display: none;" class="relative inline-block w-24 h-24 mb-2" x-cloak>
                        <img :src="imagePreview" class="w-full h-full object-cover rounded-xl border border-gray-200 shadow-sm">
                        <button type="button" @click="imagePreview = null; $refs.chatImage.value = null;" class="absolute -top-2 -right-2 bg-white text-red-500 hover:text-red-700 rounded-full p-1.5 shadow-md border border-gray-100 focus:outline-none transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="flex items-end gap-3">
                        <div class="flex-1 relative flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-gray-800 focus-within:ring-1 focus-within:ring-gray-800 transition-colors shadow-sm overflow-hidden">
                            <button type="button" @click="$refs.chatImage.click()" class="p-3 text-gray-400 hover:text-amber-800 transition-colors focus:outline-none shrink-0" title="Adjuntar render o foto">
                                <svg class="w-5 h-5 transform -rotate-45" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"></path></svg>
                            </button>
                            <input type="file" name="chat_image" x-ref="chatImage" accept="image/*" class="hidden" 
                                   @change="if($event.target.files.length) { imagePreview = URL.createObjectURL($event.target.files[0]); }">
                            
                            <textarea name="message" rows="1" required maxlength="2000"
                                      class="w-full bg-transparent border-none py-3.5 pr-4 focus:ring-0 resize-none text-sm font-sans" 
                                      placeholder="Escribe una respuesta al cliente..."></textarea>
                        </div>
                        <button type="submit" :disabled="isSubmitting" :class="{'opacity-50 cursor-not-allowed': isSubmitting}" class="bg-gray-900 text-white hover:bg-gray-800 rounded-xl p-3.5 h-[50px] w-[50px] flex items-center justify-center transition-colors shadow-sm shrink-0 focus:outline-none">
                            <svg x-show="!isSubmitting" class="w-5 h-5 transform rotate-45 -mt-0.5 -ml-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            <svg x-show="isSubmitting" x-cloak class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>
                </form>
                @error('chat_image') <p class="mt-1 text-xs text-red-600 font-sans">{{ $message }}</p> @enderror
                @error('message') <p class="mt-1 text-xs text-red-600 font-sans">{{ $message }}</p> @enderror
            </div>
            
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chatBox = document.getElementById('admin-chat-container');
        if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>
@endpush