<x-app-layout>
    <x-slot:title>Mis Cotizaciones | Carpintec</x-slot:title>

    <div class="bg-gray-50/30 min-h-screen py-12 pt-32 font-sans">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10 flex flex-col sm:flex-row sm:items-end justify-between border-b border-gray-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl sm:text-4xl text-gray-900 tracking-tight">Mis Cotizaciones</h1>
                    <p class="mt-2 text-sm text-gray-600">Historial de tus solicitudes de diseño y presupuestos.</p>
                </div>
                <div class="mt-4 sm:mt-0 flex gap-4">
                    <a href="{{ route('quotations.create') }}" class="inline-flex items-center justify-center bg-white border border-gray-200 text-gray-900 hover:bg-gray-50 uppercase tracking-widest text-xs font-bold rounded-lg px-6 py-3 transition-colors duration-200 shadow-sm">
                        + Nueva Cotización
                    </a>
                </div>
            </div>

            @if ($quotations->isEmpty())
                <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-sm">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                    <h3 class="font-serif text-2xl text-gray-900 mb-2">Sin proyectos activos</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">No has solicitado ninguna cotización aún. Permítenos materializar tus ideas.</p>
                    <a href="{{ route('quotations.create') }}" class="inline-flex items-center justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors">
                        Iniciar Proyecto
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($quotations as $quotation)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border border-current {{ $quotation->status->color() }}">
                                        {{ $quotation->status->label() }}
                                    </span>
                                    <span class="text-xs text-gray-400 font-medium">{{ $quotation->created_at->format('d M, Y') }}</span>
                                </div>
                                <h3 class="font-serif text-xl text-gray-900 mb-2 line-clamp-1">{{ $quotation->subject }}</h3>
                                <p class="text-sm text-gray-500 line-clamp-2 mb-6">{{ $quotation->description }}</p>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                                <div class="text-sm">
                                    @if($quotation->estimated_price)
                                        <span class="text-gray-500 block text-[10px] uppercase tracking-widest font-bold">Precio Estimado</span>
                                        <span class="font-medium text-gray-900">${{ number_format($quotation->estimated_price, 2) }}</span>
                                    @else
                                        <span class="text-gray-400 italic text-xs">Calculando...</span>
                                    @endif
                                </div>
                                <a href="{{ route('quotations.show', $quotation) }}" class="text-sm font-bold text-amber-800 hover:text-amber-900 uppercase tracking-widest flex items-center gap-1">
                                    Ver Detalle
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8">
                    {{ $quotations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>