@extends('layouts.admin')

@section('title', 'Nueva Categoría')
@section('header', 'Crear Categoría')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-4xl">
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        
        <div class="p-6 md:p-8">
            @include('admin.categories.form', ['category' => null])
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl flex items-center gap-3">
            <button type="submit" class="px-5 py-2 bg-[#C15C3D] border border-transparent rounded-lg font-medium text-sm text-white hover:bg-[#a34b30] focus:outline-none transition-colors">
                Guardar Categoría
            </button>
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection