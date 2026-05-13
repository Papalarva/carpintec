@extends('layouts.admin')

@section('title', 'Control de Inventario')
@section('header', 'Control de Inventario')

@section('content')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-6 transition-shadow hover:shadow-md">
            <div class="p-4 rounded-full bg-amber-50 text-amber-900 border border-amber-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest font-sans">Productos Rastreados</p>
                <p class="text-3xl font-bold text-gray-900 font-serif mt-1">{{ $totalTracked }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6 flex items-center gap-6 transition-shadow hover:shadow-md">
            <div class="p-4 rounded-full bg-red-50 text-red-800 border border-red-100 relative">
                @if($lowStockCount > 0)
                    <span class="absolute top-0 right-0 flex h-3 w-3 -mt-1 -mr-1">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                    </span>
                @endif
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-bold text-red-800 uppercase tracking-widest font-sans">Alerta: Bajo Stock</p>
                <p class="text-3xl font-bold text-red-900 font-serif mt-1">{{ $lowStockCount }}</p>
            </div>
        </div>
    </div>

    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <form method="GET" class="flex gap-4 w-full sm:w-auto items-center">
            @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif
            <div class="relative w-full sm:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o SKU..."
                       class="block w-full pl-10 rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors font-sans bg-gray-50">
            </div>
            <button type="submit" class="hidden sm:block bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl px-6 py-3.5 text-sm font-bold uppercase tracking-widest transition-colors font-sans shadow-sm">
                Buscar
            </button>
        </form>

        <div class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-xl border border-gray-100 w-full sm:w-auto">
            <a href="{{ route('admin.inventory.index') }}" 
               class="flex-1 text-center sm:flex-none px-6 py-2.5 text-sm font-bold uppercase tracking-widest rounded-lg transition-colors font-sans {{ empty(request('filter')) ? 'bg-white text-amber-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                Todos
            </a>
            <a href="{{ route('admin.inventory.index', ['filter' => 'low_stock']) }}" 
               class="flex-1 text-center sm:flex-none px-6 py-2.5 text-sm font-bold uppercase tracking-widest rounded-lg transition-colors font-sans {{ request('filter') === 'low_stock' ? 'bg-red-50 text-red-800 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                Bajo Stock
            </a>
        </div>
    </div>

    <x-admin.table :headers="[
        'SKU' => 'sku', 
        'Producto' => 'name', 
        'Stock actual' => 'quantity', 
        'Mínimo' => 'min_quantity', 
        'Ubicación' => 'location', 
        'Acciones' => null
    ]">
        @forelse($products as $product)
            @php 
                $inv = $product->inventory; 
                $isLowStock = $inv && $inv->quantity <= $inv->min_quantity;
            @endphp
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 font-sans">
                    <span class="bg-gray-100 border border-gray-200 text-gray-600 px-2.5 py-1 rounded font-mono text-xs tracking-wider">{{ $product->sku }}</span>
                </td>
                <td class="px-6 py-4 text-sm font-bold text-gray-900 font-sans">{{ $product->name }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-bold font-sans {{ $isLowStock ? 'bg-red-50 text-red-800 border border-red-200' : 'bg-gray-100 text-gray-800 border border-gray-200' }}">
                        @if($isLowStock)
                            <svg class="w-3 h-3 mr-1.5 animate-pulse" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"></circle></svg>
                        @endif
                        {{ $inv?->quantity ?? 0 }} u.
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 font-sans">{{ $inv?->min_quantity ?? 0 }}</td>
                <td class="px-6 py-4 text-sm text-gray-500 font-sans">{{ $inv?->location ?? '—' }}</td>
                <td class="px-6 py-4 text-sm font-medium flex items-center gap-2">
                    <a href="{{ route('admin.inventory.adjust', $product) }}" class="p-2 text-gray-400 hover:text-amber-900 hover:bg-amber-50 rounded-lg transition-colors" title="Ajustar Stock">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>
                    </a>
                    <a href="{{ route('admin.inventory.movements', $product) }}" class="p-2 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="Kardex de Movimientos">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="text-sm font-medium text-gray-500 font-sans uppercase tracking-widest">Sin resultados</span>
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    <div class="mt-6 font-sans">
        {{ $products->links() }}
    </div>
@endsection