<x-app-layout>
    <x-slot:title>{{ $quotation->subject }} | Carpintec</x-slot:title>

    <div class="bg-gray-50/30 min-h-screen py-12 pt-32 font-sans">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Breadcrumbs --}}
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('quotations.index') }}" class="text-gray-500 hover:text-amber-800 transition-colors">Mis Cotizaciones</a></li>
                    <li class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        <span class="text-gray-900 font-medium truncate max-w-[200px]">{{ $quotation->subject }}</span>
                    </li>
                </ol>
            </nav>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                {{-- Encabezado del Detalle --}}
                <div class="p-8 md:p-10 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                        <div>
                            <h1 class="font-serif text-3xl text-gray-900 mb-2">{{ $quotation->subject }}</h1>
                            <p class="text-sm text-gray-500">Solicitada el {{ $quotation->created_at->format('d \d\e F, Y') }}</p>
                        </div>
                        <div class="shrink-0">
                            <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest border border-current {{ $quotation->status->color() }}">
                                {{ $quotation->status->label() }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10 space-y-12">
                    
                    {{-- Bloque: Respuesta del Taller (Destacado) --}}
                    @if ($quotation->status->value === 'quoted' || $quotation->status->value === 'approved' || $quotation->response)
                        <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-8 relative">
                            <h3 class="text-xs font-bold text-amber-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.612a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.612a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"></path></svg>
                                Respuesta de Carpintec
                            </h3>
                            
                            @if($quotation->response)
                                <p class="text-gray-700 whitespace-pre-line leading-relaxed mb-6">{{ $quotation->response }}</p>
                            @endif

                            @if ($quotation->estimated_price)
                                <div class="pt-4 border-t border-amber-200/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <span class="block text-xs text-amber-800 font-bold uppercase tracking-widest mb-1">Presupuesto Estimado</span>
                                        <span class="font-serif text-3xl text-gray-900">${{ number_format($quotation->estimated_price, 2) }} <span class="text-sm font-sans text-gray-500 font-normal">MXN</span></span>
                                    </div>
                                    @if($quotation->status->value === 'quoted')
                                        <form action="{{ route('quotations.convert-to-order', $quotation) }}" method="POST">
                                            @csrf
                                            <button class="bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-lg px-6 py-3 transition-colors shadow-sm">
                                                Aceptar y Comprar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Bloque: Solicitud Original del Cliente --}}
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-100 pb-2">Tu Solicitud Original</h3>
                        <p class="text-gray-600 whitespace-pre-line leading-relaxed">{{ $quotation->description }}</p>
                    </div>

                    {{-- Archivos Adjuntos --}}
                    @php
                        $attachments = $quotation->getMedia('quotation_files');
                    @endphp
                    
                    @if ($attachments->count() > 0)
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-100 pb-2">Documentos y Referencias</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($attachments as $attachment)
                                    <a href="{{ route('quotations.download', [$quotation, $attachment]) }}" target="_blank"
                                       class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors group">
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-amber-100 group-hover:text-amber-800 transition-colors">
                                            <svg class="w-5 h-5 text-gray-500 group-hover:text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment->file_name }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ number_format($attachment->size / 1024 / 1024, 2) }} MB</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>