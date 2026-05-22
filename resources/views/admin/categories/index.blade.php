@extends('layouts.admin')

@section('title', 'Categorías')
@section('header', 'Gestión de Categorías')

@section('content')
<div x-data="{ actionUrl: '' }">
    
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form method="GET" class="flex flex-1 w-full md:w-auto items-center gap-4">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nombre o descripción..."
                       class="block w-full pl-10 rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans">
            </div>
        </form>
        
        <a href="{{ route('admin.categories.create') }}" class="bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-8 py-4 transition-all duration-200 shadow-sm hover:shadow-md font-sans whitespace-nowrap">
            Nueva Categoría
        </a>
    </div>

    <x-admin.table :headers="[
        'Nombre' => 'name', 
        'Categoría Padre' => 'parent_name', 
        'Orden' => 'sort_order', 
        'Estado' => 'is_active', 
        'Acciones' => null
    ]">
        @forelse($categories as $category)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 font-sans">
                    {{ $category->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-sans">
                    @if($category->parent)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-100/70 shadow-sm">
                            {{ $category->parent->name }}
                        </span>
                    @else
                        <span class="text-gray-400 italic text-xs">Principal</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-sans">
                    {{ $category->sort_order }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap font-sans">
                    @if($category->is_active)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                            Activa
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                            Inactiva
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center gap-1 font-sans">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="p-2 text-gray-400 hover:text-amber-900 hover:bg-amber-50 rounded-xl transition-colors" title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.113l-3.53 1.08 1.08-3.53a4.5 4.5 0 011.113-1.89l3.4-1.341z"></path>
                        </svg>
                    </a>
                    <button @click="actionUrl = '{{ route('admin.categories.destroy', $category) }}'; $dispatch('open-modal', 'delete-modal')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors" title="Eliminar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                        </svg>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-16 text-center bg-white">
                    <svg class="mx-auto h-14 w-14 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    <span class="text-base font-medium text-gray-400 font-sans">No se encontraron categorías registradas.</span>
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    <div class="mt-6 font-sans">
        {{ $categories->links() }}
    </div>

    <x-admin.modal id="delete-modal" title="Eliminar Categoría">
        <p class="text-sm text-gray-600 leading-relaxed font-sans">¿Estás seguro de que deseas eliminar esta categoría? Si contiene productos vinculados, asegúrate de reasignarlos primero a otra colección. Esta acción es irreversible.</p>
        <x-slot name="footer">
            <div class="flex items-center justify-end gap-3 w-full font-sans">
                <button @click="$dispatch('close-modal')" type="button" class="min-w-[140px] px-6 py-3.5 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 rounded-xl font-bold流 text-xs uppercase tracking-widest transition-colors shadow-sm focus:outline-none">
                    Cancelar
                </button>
                <form :action="actionUrl" method="POST" class="m-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="min-w-[140px] px-6 py-3.5 bg-red-700 text-white hover:bg-red-800 rounded-xl font-bold text-xs uppercase tracking-widest transition-all duration-200 shadow-sm focus:outline-none">
                        Confirmar
                    </button>
                </form>
            </div>
        </x-slot>
    </x-admin.modal>
</div>
@endsection