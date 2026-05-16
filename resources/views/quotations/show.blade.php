<x-app-layout>
    <x-slot:title>{{ $quotation->subject }} | Carpintec</x-slot:title>

    {{-- ESTADO GLOBAL Y BLOQUEO DE SCROLL PARA LIGHTBOX --}}
    <div class="bg-gray-50/30 min-h-screen py-10 font-sans" x-data="{ 
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

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('quotations.index') }}" class="text-gray-500 hover:text-amber-800 transition-colors">Mis Cotizaciones</a></li>
                    <li class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        <span class="text-gray-900 font-medium truncate max-w-[200px]">{{ $quotation->subject }}</span>
                    </li>
                </ol>
            </nav>

            {{-- SEPARACIÓN EXACTA DE ARCHIVOS POR COLECCIÓN --}}
            @php
                $customerFiles = $quotation->getMedia('quotation_files');
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
                {{-- COLUMNA IZQUIERDA: EXPEDIENTE Y CHECKOUT --}}
                {{-- ========================================== --}}
                <div class="lg:col-span-5 space-y-6">

                    {{-- Tarjeta Principal --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 border-t-4 border-t-amber-900">
                        <div class="flex justify-between items-start mb-6">
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest border border-current {{ $quotation->status->color() }}">
                                {{ $quotation->status->label() }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">{{ $quotation->created_at->format('d M, Y') }}</span>
                        </div>

                        <h1 class="font-serif text-2xl text-gray-900 mb-2 leading-tight">{{ $quotation->subject }}</h1>

                        @if ($quotation->estimated_price)
                            <div class="mt-8 pt-6 border-t border-gray-100">
                                <span class="block text-[10px] text-amber-800 font-bold uppercase tracking-widest mb-1">Presupuesto Aprobado</span>
                                <span class="font-serif text-4xl text-gray-900">${{ number_format($quotation->estimated_price, 2) }}
                                    <span class="text-sm font-sans text-gray-500 font-normal">MXN</span>
                                </span>

                                @if ($quotation->status->value === 'quoted')
                                    <a href="{{ route('quotations.checkout', $quotation) }}" class="mt-6 flex w-full justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-8 py-4 transition-colors shadow-sm focus:outline-none">
                                        Proceder al Pago
                                    </a>
                                @elseif($quotation->status->value === 'approved')
                                    <div class="mt-6 px-4 py-3 bg-green-50 border border-green-200 rounded-xl flex items-center justify-center gap-2 text-green-700 shadow-sm">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                        <span class="font-bold uppercase tracking-widest text-[10px]">Proyecto en Producción</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Material Oficial Entregado por Carpintec (NUEVO PARA EL CLIENTE) --}}
                    @if($adminImages->count() > 0 || $adminDocs->count() > 0)
                        <div class="bg-amber-50/50 rounded-2xl shadow-sm border border-amber-200 p-8">
                            <h3 class="text-sm font-bold text-amber-900 mb-4 border-b border-amber-200/60 pb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                Propuesta Oficial de Carpintec
                            </h3>
                            
                            @if($adminImages->count() > 0)
                                <div class="grid grid-cols-3 gap-2 mb-4">
                                    @foreach($adminImages as $img)
                                        <div class="relative group rounded-lg overflow-hidden border border-amber-200/60 aspect-square cursor-zoom-in" @click="lightboxImage = '{{ $img->getUrl() }}'; lightboxOpen = true">
                                            <img src="{{ $img->getUrl() }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($adminDocs->count() > 0)
                                <ul class="space-y-2">
                                    @foreach($adminDocs as $doc)
                                        <li>
                                            <a href="{{ route('quotations.download', [$quotation, $doc->id]) }}" target="_blank" class="flex items-center p-3 border border-amber-200/60 bg-white rounded-lg hover:bg-amber-50 transition-colors shadow-sm">
                                                <svg class="w-5 h-5 text-amber-700 mr-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                                <span class="text-sm font-medium text-amber-900 truncate">{{ $doc->file_name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    {{-- Solicitud Original del Cliente --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Tu Solicitud Original</h3>
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed text-sm mb-6">{{ $quotation->description }}</p>

                        @if ($customerImages->count() > 0)
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Tus Imágenes</h3>
                            <div class="grid grid-cols-3 gap-3 mb-6">
                                @foreach ($customerImages as $img)
                                    <div class="relative group rounded-lg overflow-hidden border border-gray-200 aspect-square cursor-zoom-in" @click="lightboxImage = '{{ $img->getUrl() }}'; lightboxOpen = true">
                                        <img src="{{ $img->getUrl() }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($customerDocs->count() > 0)
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Tus Documentos</h3>
                            <ul class="space-y-2">
                                @foreach ($customerDocs as $doc)
                                    <li>
                                        <a href="{{ route('quotations.download', [$quotation, $doc->id]) }}" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                            <span class="text-sm font-medium text-gray-700 truncate">{{ $doc->file_name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                {{-- ========================================== --}}
                {{-- COLUMNA DERECHA: CHAT DE NEGOCIACIÓN --}}
                {{-- ========================================== --}}
                <div class="lg:col-span-7 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-[800px]">

                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 shrink-0">
                        <h2 class="text-lg font-bold text-gray-900 font-serif flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"></path></svg>
                            Mensajes con el Taller
                        </h2>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50/30" id="chat-container">

                        <div class="flex justify-center">
                            <span class="bg-gray-100 text-gray-500 text-[10px] uppercase tracking-widest font-bold py-1 px-3 rounded-full">
                                Proyecto Iniciado - {{ $quotation->created_at->format('d M, Y') }}
                            </span>
                        </div>

                        {{-- Legacy Response --}}
                        @if ($quotation->response)
                            <div class="flex justify-start">
                                <div class="bg-amber-100 border border-amber-200 rounded-2xl rounded-tl-sm p-4 max-w-[85%] sm:max-w-[75%] shadow-sm">
                                    <p class="text-[10px] font-bold text-amber-900 uppercase tracking-widest mb-1">Carpintec</p>
                                    <p class="text-sm text-gray-800 whitespace-pre-line">{{ $quotation->response }}</p>
                                </div>
                            </div>
                        @endif

                        @forelse($quotation->messages ?? [] as $msg)
                            @if ($msg->sender_type === 'admin')
                                <div class="flex justify-start">
                                    <div class="bg-amber-100 border border-amber-200 rounded-2xl rounded-tl-sm p-4 max-w-[85%] sm:max-w-[75%] shadow-sm">
                                        <div class="flex justify-between items-baseline mb-1 gap-4">
                                            <p class="text-[10px] font-bold text-amber-900 uppercase tracking-widest">Carpintec</p>
                                            <span class="text-[9px] text-amber-700">{{ $msg->created_at->format('H:i') }}</span>
                                        </div>
                                        @if ($msg->hasMedia('chat_images'))
                                            <div class="mb-2 mt-1 cursor-zoom-in overflow-hidden rounded-lg border border-amber-200/50" @click="lightboxImage = '{{ $msg->getFirstMediaUrl('chat_images') }}'; lightboxOpen = true">
                                                <img src="{{ $msg->getFirstMediaUrl('chat_images') }}" class="w-full h-auto max-h-48 object-cover hover:scale-105 transition-transform duration-500">
                                            </div>
                                        @endif
                                        <p class="text-sm text-gray-800 whitespace-pre-line">{{ $msg->message }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex justify-end">
                                    <div class="bg-gray-800 text-white rounded-2xl rounded-tr-sm p-4 max-w-[85%] sm:max-w-[75%] shadow-sm">
                                        <div class="flex justify-between items-baseline mb-1 gap-4">
                                            <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Tú</p>
                                            <span class="text-[9px] text-gray-400">{{ $msg->created_at->format('H:i') }}</span>
                                        </div>
                                        @if ($msg->hasMedia('chat_images'))
                                            <div class="mb-2 mt-1 cursor-zoom-in overflow-hidden rounded-lg border border-gray-600" @click="lightboxImage = '{{ $msg->getFirstMediaUrl('chat_images') }}'; lightboxOpen = true">
                                                <img src="{{ $msg->getFirstMediaUrl('chat_images') }}" class="w-full h-auto max-h-48 object-cover hover:scale-105 transition-transform duration-500">
                                            </div>
                                        @endif
                                        <p class="text-sm whitespace-pre-line">{{ $msg->message }}</p>
                                    </div>
                                </div>
                            @endif
                        @empty
                        @endforelse
                    </div>

                    @if ($quotation->status->value !== 'approved' && $quotation->status->value !== 'rejected')
                        <div class="p-4 border-t border-gray-100 bg-white shrink-0" x-data="{ imagePreview: null, fileName: '', isSubmitting: false }">
                            <form action="{{ route('quotations.message', $quotation) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2" @submit="isSubmitting = true">
                                @csrf

                                <div x-show="imagePreview" style="display: none;" class="relative inline-block w-24 h-24 mb-2" x-cloak>
                                    <img :src="imagePreview" class="w-full h-full object-cover rounded-xl border border-gray-200 shadow-sm">
                                    <button type="button" @click="imagePreview = null; fileName = ''; $refs.chatImage.value = null;" class="absolute -top-2 -right-2 bg-white text-red-500 hover:text-red-700 rounded-full p-1 shadow-md border border-gray-100 focus:outline-none transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <div class="flex items-end gap-3">
                                    <div class="flex-1 relative flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-amber-800 focus-within:ring-1 focus-within:ring-amber-800 transition-colors shadow-sm overflow-hidden">
                                        <button type="button" @click="$refs.chatImage.click()" class="p-3 text-gray-400 hover:text-amber-800 transition-colors focus:outline-none shrink-0" title="Adjuntar foto">
                                            <svg class="w-5 h-5 transform -rotate-45" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"></path></svg>
                                        </button>
                                        <input type="file" name="chat_image" x-ref="chatImage" accept="image/*" class="hidden" @change="if($event.target.files.length) { imagePreview = URL.createObjectURL($event.target.files[0]); fileName = $event.target.files[0].name; }">
                                        <textarea name="message" rows="1" required maxlength="2000" class="w-full bg-transparent border-none py-3.5 pr-4 focus:ring-0 resize-none text-sm font-sans" placeholder="Escribe un mensaje al taller..."></textarea>
                                    </div>

                                    <button type="submit" :disabled="isSubmitting" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="bg-amber-900 text-white hover:bg-amber-800 rounded-xl p-3.5 h-[50px] w-[50px] flex items-center justify-center transition-colors shadow-sm shrink-0" title="Enviar">
                                        <svg x-show="!isSubmitting" class="w-5 h-5 transform rotate-45 -mt-0.5 -ml-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        <svg x-show="isSubmitting" x-cloak class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </button>
                                </div>
                            </form>
                            @error('chat_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chatBox = document.getElementById('chat-container');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    </script>
</x-app-layout>