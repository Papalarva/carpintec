@extends('layouts.admin')

@section('title', 'Control de Inventario')
@section('header', 'Control de Inventario')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-3 rounded-full bg-indigo-50 text-indigo-600 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Productos Rasteados</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalTracked }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-rose-100 p-6 flex items-center">
        <div class="p-3 rounded-full bg-rose-50 text-rose-600 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-rose-600">Alerta: Bajo Stock</p>
            <p class="text-2xl font-bold text-gray-900">{{ $lowStockCount }}</p>
        </div>
    </div>
</div>

<div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
    <div class="flex gap-2">
        <a href="{{ route('admin.inventory.index') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ empty($filter) ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:bg-gray-50' }}">Todos</a>
        <a href="{{ route('admin.inventory.index', ['filter' => 'low_stock']) }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ $filter === 'low_stock' ? 'bg-rose-100 text-rose-700' : 'text-gray-500 hover:bg-gray-50' }}">Bajo Stock</a>
    </div>

    <form method="GET" class="flex gap-2 w-full sm:w-auto">
        @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nombre o SKU..."
               class="rounded-lg border-gray-200 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] w-full sm:w-64">
        <button class="bg-[#C15C3D] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#a64e32] transition-colors">Buscar</button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <x-admin.table :headers="['SKU', 'Producto', 'Stock actual', 'Mínimo', 'Ubicación', 'Acciones']" :rows="$products">
        @forelse($products as $product)
            @php $inv = $product->inventory; @endphp
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->sku }}</td>
                <td class="px-6 py-4 text-sm">{{ $product->name }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $inv && $inv->quantity <= $inv->min_quantity ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $inv?->quantity ?? 0 }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $inv?->min_quantity ?? 0 }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $inv?->location ?? 'No asignada' }}</td>
                <td class="px-6 py-4 text-sm font-medium space-x-3">
                    <a href="{{ route('admin.inventory.adjust', $product) }}" class="text-[#C15C3D] hover:text-[#9c4a31]">Gestionar</a>
                    <a href="{{ route('admin.inventory.movements', $product) }}" class="text-gray-500 hover:text-gray-700">Historial</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">No se encontraron productos bajo control de inventario.</td></tr>
        @endforelse
    </x-admin.table>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection