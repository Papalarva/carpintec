@extends('layouts.admin')

@section('title', 'Nuevo Producto')
@section('header', 'Añadir Nueva Pieza')

@section('content')
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="max-w-7xl mx-auto">
        @csrf
        
        @include('admin.products.form', ['product' => null])
        
        <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center gap-4">
            <button type="submit" class="w-full sm:w-auto bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-all duration-200 shadow-sm hover:shadow-md font-sans focus:outline-none">
                Crear Pieza
            </button>
            <a href="{{ route('admin.products.index') }}" class="w-full sm:w-auto text-center bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-colors duration-200 shadow-sm font-sans focus:outline-none">
                Cancelar
            </a>
        </div>
    </form>
@endsection