@extends('layouts.admin')

@section('title', 'Historial de Movimientos - ' . $product->name)
@section('header', 'Hub de Inventario')

@section('content')
<div x-data="{}" class="max-w-6xl mx-auto">
    
    {{-- Tarjeta Superior: Hub de Inventario (Estructura Espejo de Adjust) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-8 bg-gray-50/50">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-3xl font-serif font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                    <span class="px-3 py-1 rounded-xl text-[10px] font-bold uppercase tracking-widest bg-white border border-gray-200 text-gray-600 font-sans shadow-sm">SKU: {{ $product->sku }}</span>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 font-sans">Stock Actual</p>
                    <p class="text-4xl font-sans font-bold text-gray-900">{{ $product->inventory?->quantity ?? 0 }}</p>
                </div>
            </div>
            
            <div class="flex gap-6 border-b border-gray-200">
                <a href="{{ route('admin.inventory.adjust', $product) }}" class="pb-3 border-b-2 border-transparent text-gray-400 hover:text-gray-700 text-[11px] font-bold uppercase tracking-widest font-sans transition-colors">Ajuste de Ficha</a>
                <a href="#" class="pb-3 border-b-2 border-amber-900 text-amber-900 text-[11px] font-bold uppercase tracking-widest font-sans">Kardex de Movimientos</a>
            </div>
        </div>
    </div>

    {{-- Tarjeta de Contenido: Timeline Gemelo --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Cabecera gemela a las de "Ajustes" --}}
        <div class="border-b border-gray-100 px-8 py-5 bg-gray-50/50">
            <h2 class="text-lg font-medium text-gray-900 font-serif flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Historial de Entradas y Salidas
            </h2>
        </div>

        {{-- Cuerpo del Timeline --}}
        <div class="p-8 md:p-10">
            <div class="relative border-l border-gray-200 ml-4">
                @forelse($movements as $mov)
                    <div class="mb-10 ml-10 relative">
                        {{-- Icono de la Línea de Tiempo (Emerald para ingresos, Rose para salidas) --}}
                        <span class="absolute -left-14 flex h-8 w-8 items-center justify-center rounded-full border-2 bg-white ring-8 ring-white 
                            {{ $mov->quantity > 0 ? 'border-emerald-700 text-emerald-700' : ($mov->quantity < 0 ? 'border-rose-700 text-rose-700' : 'border-gray-400 text-gray-400') }}">
                            @if($mov->quantity > 0)
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75" /></svg>
                            @elseif($mov->quantity < 0)
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" /></svg>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                            @endif
                        </span>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-gray-50/50 p-5 rounded-2xl border border-gray-100 transition-colors hover:bg-white hover:shadow-sm">
                            <div class="mb-2 sm:mb-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="text-lg font-sans font-bold {{ $mov->quantity > 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $mov->quantity > 0 ? '+' : '' }}{{ $mov->quantity }} piezas
                                    </span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500 font-sans bg-white px-2.5 py-1 rounded-xl border border-gray-200 shadow-sm">
                                        Resultante: {{ $mov->resulting_quantity }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 font-sans mt-2 font-medium">{{ $mov->reference ?? 'Sin motivo registrado' }}</p>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-xs font-bold text-gray-900 font-sans tracking-wide">{{ $mov->created_at->format('d M, Y') }}</p>
                                <p class="text-xs text-gray-500 font-sans mb-2 font-medium">{{ $mov->created_at->format('h:i A') }}</p>
                                
                                <div class="flex items-center sm:justify-end gap-1.5 text-[11px] font-bold text-gray-400 uppercase tracking-widest font-sans">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    {{ $mov->user ? $mov->user->first_name : 'Sistema' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="ml-10 text-center py-10 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                        <span class="text-sm font-medium text-gray-500 font-sans tracking-wide">Aún no hay movimientos registrados para esta pieza.</span>
                    </div>
                @endforelse
            </div>

            <div class="mt-8 px-8 pb-8 font-sans">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection