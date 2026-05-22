@extends('layouts.admin')

@section('title', 'Editar Categoría')
@section('header', 'Modificar Categoría')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">Detalles de la Categoría</h3>
        
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf @method('PUT')
            
            @include('admin.categories.form', ['category' => $category])
            
            <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center gap-4">
                <button type="submit" class="w-full sm:w-auto bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-8 py-4 transition-all duration-200 shadow-sm hover:shadow-md font-sans text-center focus:outline-none">
                    Actualizar Cambios
                </button>
                <a href="{{ route('admin.categories.index') }}" class="w-full sm:w-auto bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-xs font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm font-sans text-center focus:outline-none">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection