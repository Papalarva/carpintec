@extends('layouts.admin')

@section('title', 'Categorías')
@section('header', 'Categorías')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar categoría..."
               class="rounded border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Buscar</button>
    </form>
    <a href="{{ route('admin.categories.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Nueva Categoría</a>
</div>

<x-admin.table :headers="['Nombre', 'Padre', 'Orden', 'Activo', 'Acciones']" :rows="$categories">
    @foreach($categories as $category)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $category->parent?->name ?? '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $category->sort_order }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <x-admin.badge :color="$category->is_active ? 'green' : 'red'" :label="$category->is_active ? 'Sí' : 'No'" />
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
                <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                <button onclick="confirmDelete('{{ $category->id }}')" class="text-red-600 hover:text-red-900 ml-2">Eliminar</button>
            </td>
        </tr>
    @endforeach
</x-admin.table>

<x-admin.modal id="delete-modal" title="Eliminar Categoría">
    <p>¿Estás seguro de eliminar esta categoría?</p>
    <x-slot name="footer">
        <form id="delete-form" method="POST">
            @csrf @method('DELETE')
            <button type="button" onclick="closeModal()" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded ml-2">Eliminar</button>
        </form>
    </x-slot>
</x-admin.modal>

<script>
function confirmDelete(categoryId) {
    const form = document.getElementById('delete-form');
    form.action = '/admin/categories/' + categoryId;
    document.getElementById('delete-modal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}
</script>
@endsection