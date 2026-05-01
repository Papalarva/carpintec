@extends('layouts.admin')

@section('title', 'Productos')
@section('header', 'Productos')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar producto..."
               class="rounded border-gray-300">
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Buscar</button>
        <label class="inline-flex items-center">
            <input type="checkbox" name="trashed" value="1" {{ $showTrashed ? 'checked' : '' }} onchange="this.form.submit()">
            <span class="ml-2 text-sm">Papelera</span>
        </label>
    </form>
    <a href="{{ route('admin.products.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">Nuevo Producto</a>
</div>

<x-admin.table :headers="['SKU', 'Nombre', 'Categoría', 'Precio', 'Stock', 'Activo', 'Acciones']" :rows="$products">
    @forelse($products as $product)
        <tr class="{{ $product->trashed() ? 'opacity-60' : '' }}">
            <td class="px-6 py-4">{{ $product->sku }}</td>
            <td class="px-6 py-4">
                {{ $product->name }}
                @if($product->trashed()) <span class="text-xs text-red-500">(eliminado)</span> @endif
            </td>
            <td class="px-6 py-4">{{ $product->category?->name }}</td>
            <td class="px-6 py-4">${{ number_format($product->price, 2) }}</td>
            <td class="px-6 py-4">
                @if($product->track_inventory)
                    {{ $product->inventory?->quantity ?? 0 }}
                    @if($product->inventory && $product->inventory->isLowStock())
                        <x-admin.badge color="red" label="Bajo" />
                    @endif
                @else
                    <span class="text-gray-400">N/A</span>
                @endif
            </td>
            <td class="px-6 py-4">
                <x-admin.badge :color="$product->is_active ? 'green' : 'red'" :label="$product->is_active ? 'Sí' : 'No'" />
            </td>
            <td class="px-6 py-4 text-sm">
                @if(!$product->trashed())
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:underline">Editar</a>
                    <button onclick="confirmDelete('{{ $product->id }}')" class="text-red-600 hover:underline ml-2">Eliminar</button>
                @else
                    <form method="POST" action="{{ route('admin.products.restore', $product->id) }}" class="inline">
                        @csrf
                        <button class="text-green-600 hover:underline">Restaurar</button>
                    </form>
                    <span class="mx-1">|</span>
                    <button onclick="confirmForceDelete('{{ $product->id }}')" class="text-red-600 hover:underline">Eliminar def.</button>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay productos.</td></tr>
    @endforelse
</x-admin.table>

<x-admin.modal id="delete-modal" title="Eliminar Producto">
    <p>¿Mover este producto a la papelera?</p>
    <x-slot name="footer">
        <form id="delete-form" method="POST">
            @csrf @method('DELETE')
            <button type="button" onclick="closeModal()" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded ml-2">Eliminar</button>
        </form>
    </x-slot>
</x-admin.modal>

<x-admin.modal id="force-delete-modal" title="Eliminación Definitiva">
    <p>Esta acción no se puede deshacer. ¿Continuar?</p>
    <x-slot name="footer">
        <form id="force-delete-form" method="POST">
            @csrf @method('DELETE')
            <button type="button" onclick="closeModal()" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded ml-2">Eliminar permanentemente</button>
        </form>
    </x-slot>
</x-admin.modal>

<script>
function confirmDelete(id) {
    document.getElementById('delete-form').action = '/admin/products/' + id;
    document.getElementById('delete-modal').classList.remove('hidden');
}
function confirmForceDelete(id) {
    document.getElementById('force-delete-form').action = '/admin/products/' + id + '/force-delete';
    document.getElementById('force-delete-modal').classList.remove('hidden');
}
function closeModal() {
    document.querySelectorAll('[id$="-modal"]').forEach(m => m.classList.add('hidden'));
}
</script>
@endsection