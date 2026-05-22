@extends('layouts.admin')

@section('title', 'Nuevo Producto')
@section('header', 'Añadir Nueva Pieza')

@section('content')
    <div x-data="productForm()" class="max-w-7xl mx-auto">
        <form x-ref="form" @submit="attemptSubmit($event)" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            
            @include('admin.products.form', ['product' => null])
            
            <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center gap-4">
                <a href="{{ route('admin.products.index') }}" class="w-full sm:w-auto text-center bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-colors duration-200 shadow-sm font-sans focus:outline-none" :class="{ 'opacity-50 pointer-events-none': isSubmitting }">
                    Cancelar
                </a>
                <button type="submit" :disabled="isSubmitting" class="w-full sm:w-auto bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-all duration-200 shadow-sm hover:shadow-md font-sans focus:outline-none disabled:opacity-75 disabled:cursor-not-allowed">
                    <span x-show="!isSubmitting">Crear Pieza</span>
                    <span x-show="isSubmitting" x-cloak class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Procesando...
                    </span>
                </button>
            </div>
        </form>
    </div>
@endsection