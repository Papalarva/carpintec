@extends('layouts.admin')

@section('title', 'Categorías')
@section('header', 'Gestión de Categorías')

@section('content')
<!-- Contenedor Tarjeta Ejecutiva -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    
    <!-- Barra de herramientas (Buscador y Botón) -->
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <form method="GET" class="w-full sm:w-96 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nombre..."
                   class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
        </form>
        
        <a href="{{ route('admin.categories.create') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-[#C15C3D] border border-transparent rounded-lg font-medium text-sm text-white hover:bg-[#a34b30] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C15C3D] transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Categoría
        </a>
    </div>

    <!-- La Tabla -->
    <x-admin.table :headers="['Nombre', 'Categoría Padre', 'Orden', 'Estado', 'Acciones']">
        @forelse($categories as $category)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->parent?->name ?? '—' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->sort_order }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-admin.badge :color="$category->is_active ? 'green' : 'gray'" :label="$category->is_active ? 'Activo' : 'Inactivo'" />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-gray-400 hover:text-[#C15C3D] transition-colors mr-3">Editar</a>
                    <button onclick="confirmDelete('{{ $category->id }}')" class="text-gray-400 hover:text-rose-600 transition-colors">Eliminar</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No se encontraron categorías.</td>
            </tr>
        @endforelse
    </x-admin.table>
</div>

<!-- Modal de Eliminación -->
<x-admin.modal id="delete-modal" title="Eliminar Categoría">
    <p>¿Estás seguro de que deseas eliminar esta categoría? Esta acción no se puede deshacer.</p>
    <x-slot name="footer">
        <form id="delete-form" method="POST">
            @csrf @method('DELETE')
            <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none transition-colors">Cancelar</button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-rose-600 border border-transparent rounded-lg hover:bg-rose-700 focus:outline-none transition-colors">Sí, eliminar</button>
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