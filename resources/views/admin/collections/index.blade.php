@extends('layouts.admin')

@section('title', 'Colecciones')
@section('header', 'Gestión de Colecciones')

@section('content')
<div x-data="{ actionUrl: '' }">
    
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form method="GET" class="flex flex-1 w-full md:w-auto items-center gap-4">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar colección..."
                       class="block w-full pl-10 rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors font-sans">
            </div>
        </form>
        
        <a href="{{ route('admin.collections.create') }}" class="bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm font-sans whitespace-nowrap">
            Nueva Colección
        </a>
    </div>

    <x-admin.table :headers="[
        'Nombre' => 'name', 
        'Productos' => 'products_count', 
        'Estado' => 'is_active', 
        'Acciones' => null
    ]">
        @forelse($collections as $collection)
            <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                <td class="px-6 py-4 font-sans font-medium text-gray-900 text-base">
                    {{ $collection->name }}
                </td>
                <td class="px-6 py-4 font-sans text-sm text-gray-600">
                    {{ $collection->products_count }} artículos
                </td>
                <td class="px-6 py-4">
                    <x-admin.badge 
                        :color="$collection->is_active ? 'green' : 'gray'" 
                        :label="$collection->is_active ? 'Activa' : 'Inactiva'" 
                    />
                </td>
                <td class="px-6 py-4 flex items-center gap-2 font-sans text-sm">
                    <a href="{{ route('admin.collections.edit', $collection) }}" class="p-2 text-gray-400 hover:text-amber-900 hover:bg-amber-50 rounded-lg transition-colors" title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.113l-3.53 1.08 1.08-3.53a4.5 4.5 0 011.113-1.89l3.4-1.341z"></path></svg>
                    </a>
                    <button @click="actionUrl = '{{ route('admin.collections.destroy', $collection) }}'; $dispatch('open-modal', 'delete-modal')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500 font-sans">
                    No se encontraron colecciones.
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    <div class="mt-6 font-sans">
        {{ $collections->links() }}
    </div>

    <x-admin.modal id="delete-modal" title="Eliminar Colección">
        <p class="text-gray-600 font-sans">¿Estás seguro de que deseas eliminar esta colección? Los productos que contiene no se eliminarán, únicamente se desvincularán de esta agrupación.</p>
        <x-slot name="footer">
            <div class="flex items-center justify-end gap-3 w-full">
                <button @click="$dispatch('close-modal')" type="button" class="min-w-[140px] px-6 py-3 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 rounded-xl font-bold text-sm uppercase tracking-widest transition-colors font-sans shadow-sm">
                    Cancelar
                </button>
                <form :action="actionUrl" method="POST" class="m-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="min-w-[140px] px-6 py-3 bg-red-800 text-white hover:bg-red-900 rounded-xl font-bold text-sm uppercase tracking-widest transition-colors font-sans shadow-sm">
                        Eliminar
                    </button>
                </form>
            </div>
        </x-slot>
    </x-admin.modal>
</div>
@endsection