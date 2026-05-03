@extends('layouts.admin')

@section('title', 'Inventario')
@section('header', 'Inventario')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar producto..."
               class="rounded border-gray-300">
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Buscar</button>
    </form>
</div>

<x-admin.table :headers="['SKU', 'Producto', 'Stock actual', 'Mínimo', 'Ubicación', 'Acciones']" :rows="$products">
    @forelse($products as $product)
        @php $inv = $product->inventory; @endphp
        <tr>
            <td class="px-6 py-4">{{ $product->sku }}</td>
            <td class="px-6 py-4">{{ $product->name }}</td>
            <td class="px-6 py-4">
                <span class="font-mono {{ $inv && $inv->isLowStock() ? 'text-red-600 font-bold' : '' }}">
                    {{ $inv?->quantity ?? 0 }}
                </span>
            </td>
            <td class="px-6 py-4">{{ $inv?->min_quantity ?? 0 }}</td>
            <td class="px-6 py-4">{{ $inv?->location ?? '-' }}</td>
            <td class="px-6 py-4 text-sm space-x-2">
                <a href="{{ route('admin.inventory.movements', $product) }}" class="text-indigo-600 hover:underline">Movimientos</a>
                <a href="{{ route('admin.inventory.adjust', $product) }}" class="text-yellow-600 hover:underline">Ajustar</a>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Sin productos controlados.</td></tr>
    @endforelse
</x-admin.table>

<div class="mt-4">
    {{ $products->links() }}
</div>

@endsection
