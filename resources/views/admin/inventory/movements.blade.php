@extends('layouts.admin')

@section('title', 'Historial de Movimientos - ' . $product->name)
@section('header', 'Kardex de Inventario')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <div class="flex items-center gap-3 mb-1">
            <h3 class="text-xl font-playfair font-semibold text-gray-900">{{ $product->name }}</h3>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 tracking-wider">SKU: {{ $product->sku }}</span>
        </div>
        <p class="text-sm text-gray-500">Historial completo de entradas, salidas y ajustes del sistema.</p>
    </div>
    
    <div class="flex items-center gap-5">
        <div class="text-right">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Stock Actual</p>
            <p class="text-3xl font-bold {{ $product->inventory?->quantity <= $product->inventory?->min_quantity ? 'text-rose-600' : 'text-emerald-600' }}">
                {{ $product->inventory?->quantity ?? 0 }}
            </p>
        </div>
        <div class="h-10 w-px bg-gray-200 hidden sm:block"></div>
        <a href="{{ route('admin.inventory.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            ← Volver al catálogo
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <x-admin.table :headers="['Fecha', 'Tipo de Movimiento', 'Cantidad', 'Stock Resultante', 'Referencia / Motivo', 'Realizado por']" :rows="$movements">
        @forelse($movements as $mov)
            <tr class="hover:bg-gray-50 transition-colors">
                
                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                    <div class="font-medium text-gray-900">{{ $mov->created_at->format('d/m/Y') }}</div>
                    <div class="text-xs">{{ $mov->created_at->format('h:i A') }}</div>
                </td>
                
                <td class="px-6 py-4">
                    @switch($mov->movement_type)
                        @case('sale') 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">Venta en Tienda</span> 
                            @break
                        @case('restock') 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Entrada (Compra)</span> 
                            @break
                        @case('adjustment') 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">Ajuste Manual</span> 
                            @break
                        @case('return') 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-800 border border-yellow-200">Devolución</span> 
                            @break
                        @default 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">{{ ucfirst($mov->movement_type) }}</span>
                    @endswitch
                </td>

                <td class="px-6 py-4 text-right whitespace-nowrap">
                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded text-sm font-mono font-bold {{ $mov->quantity > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ $mov->quantity > 0 ? '+' : '' }}{{ $mov->quantity }}
                    </span>
                </td>

                <td class="px-6 py-4 text-right">
                    <span class="font-mono text-gray-900 font-medium">{{ $mov->resulting_quantity }}</span>
                </td>

                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $mov->reference }}">
                    {{ $mov->reference ?? '—' }}
                </td>

                <td class="px-6 py-4 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        @if($mov->user)
                            <div class="w-6 h-6 rounded-full bg-[#C15C3D] text-white flex items-center justify-center text-xs font-bold">
                                {{ substr($mov->user->first_name, 0, 1) }}
                            </div>
                            <span>{{ $mov->user->first_name }} {{ $mov->user->last_name }}</span>
                        @else
                            <div class="w-6 h-6 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="font-medium text-gray-700">Sistema Automático</span>
                        @endif
                    </div>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="text-sm font-medium text-gray-900">Sin Movimientos</h3>
                    <p class="mt-1 text-sm text-gray-500">Este producto aún no tiene historial de entradas o salidas en el almacén.</p>
                </td>
            </tr>
        @endforelse
    </x-admin.table>
</div>

<div class="mt-6">
    {{ $movements->links() }}
</div>
@endsection