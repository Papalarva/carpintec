@extends('layouts.admin')

@section('title', 'Kardex - ' . $product->name)

@section('header')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-serif font-bold text-gray-900">Kardex de Movimientos</h1>
            <p class="text-sm text-gray-500 font-sans mt-1">
                {{ $product->name }} <span class="mx-2">|</span> SKU: {{ $product->sku }}
            </p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest font-sans mb-0.5">Stock Actual</p>
                <p class="text-xl font-sans font-bold text-gray-900">{{ $product->inventory?->quantity ?? 0 }}</p>
            </div>
            <a href="{{ route('admin.inventory.adjust', $product) }}" class="inline-flex items-center gap-2 bg-amber-900 hover:bg-amber-800 text-white uppercase tracking-widest text-xs font-bold rounded-xl px-4 py-2.5 transition-colors shadow-sm font-sans">
                <svg class="w-4 h-4 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"></path></svg>
                Realizar Ajuste
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    
    <div class="border-b border-gray-100 px-8 py-5 bg-gray-50/50">
        <h2 class="text-lg font-medium text-gray-900 font-serif flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Historial de Entradas y Salidas
        </h2>
    </div>

    <div class="p-8 md:p-10">
        <div class="relative border-l border-gray-200 ml-4">
            @forelse($movements as $mov)
                <div class="mb-10 ml-8 relative">
                    {{-- Punto indicador --}}
                    <span class="absolute -left-12 flex h-8 w-8 items-center justify-center rounded-full border-2 bg-white ring-4 ring-white 
                        {{ $mov->quantity > 0 ? 'border-amber-600 text-amber-600' : ($mov->quantity < 0 ? 'border-red-700 text-red-700' : 'border-gray-400 text-gray-400') }}">
                        @if($mov->quantity > 0)
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75"></path></svg>
                        @elseif($mov->quantity < 0)
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75"></path></svg>
                        @else
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path></svg>
                        @endif
                    </span>

                    <div class="flex flex-col md:flex-row md:items-center justify-between bg-gray-50/80 p-5 rounded-2xl border border-gray-100 hover:shadow-sm transition-shadow">
                        <div class="mb-3 md:mb-0">
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-lg font-sans font-bold {{ $mov->quantity > 0 ? 'text-amber-900' : 'text-red-800' }}">
                                    {{ $mov->quantity > 0 ? '+' : '' }}{{ $mov->quantity }} piezas
                                </span>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500 font-sans bg-white px-2.5 py-1 rounded-md border border-gray-200 shadow-sm">
                                    Resultante: {{ $mov->resulting_quantity }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 font-sans mt-2 italic">"{{ $mov->reference ?? 'Sin motivo registrado' }}"</p>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-xs font-bold text-gray-900 font-sans">{{ $mov->created_at->format('d de F, Y') }}</p>
                            <p class="text-xs text-gray-500 font-sans mb-2">{{ $mov->created_at->format('H:i') }} hrs</p>
                            
                            <div class="flex items-center md:justify-end gap-1.5 text-xs font-medium text-gray-600 font-sans">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                                {{ $mov->user ? $mov->user->first_name . ' ' . $mov->user->last_name : 'Sistema Automático' }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <span class="text-sm font-medium text-gray-500 font-sans tracking-wide">Aún no hay movimientos registrados en el Kardex.</span>
                </div>
            @endforelse
        </div>

        <div class="mt-8 font-sans">
            {{ $movements->links() }}
        </div>
    </div>
</div>
@endsection