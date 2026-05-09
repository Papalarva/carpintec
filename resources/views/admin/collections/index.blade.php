@extends('layouts.admin')
@section('title', 'Colecciones')
@section('header', 'Gestión de Colecciones')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="w-1/3">
        </div>
    <a href="{{ route('admin.collections.create') }}" class="bg-[#C15C3D] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#a64e32] transition-colors shadow-sm font-inter inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Nueva Colección
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <x-admin.table :headers="['Nombre', 'Productos', 'Estado', 'Acciones']">
        @foreach($collections as $collection)
            <tr class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-6 py-4 font-playfair font-medium text-gray-900 text-lg">
                    {{ $collection->name }}
                </td>
                <td class="px-6 py-4 font-inter text-sm text-gray-600">
                    {{ $collection->products_count }} artículos
                </td>
                <td class="px-6 py-4">
                    <x-admin.badge 
                        :color="$collection->is_active ? 'green' : 'gray'" 
                        :label="$collection->is_active ? 'Activa' : 'Inactiva'" 
                    />
                </td>
                <td class="px-6 py-4 flex items-center gap-4 font-inter text-sm">
                    <a href="{{ route('admin.collections.edit', $collection) }}" class="text-[#C15C3D] hover:underline font-medium">
                        Editar
                    </a>
                    <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta colección? Esto no eliminará los productos, solo la agrupación.')">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 hover:underline font-medium">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </x-admin.table>
</div>
@endsection