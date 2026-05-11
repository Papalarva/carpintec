@extends('layouts.admin')

@section('title', 'Editar Producto')
@section('header', 'Modificar Detalles de Pieza')

@section('content')
    @if($errors->any())
        <div class="mb-8 bg-red-50 border-l-4 border-red-700 p-4 rounded-r-xl shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 font-serif">Por favor, revisa los siguientes errores:</h3>
                    <div class="mt-2 text-sm text-red-700 font-sans">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf 
        @method('PUT')
        @include('admin.products.form', ['product' => $product])
        
        <div class="mt-8 flex items-center gap-4">
            <button type="submit" class="bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm font-sans">
                Guardar Cambios
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm font-sans">
                Cancelar
            </a>
        </div>
    </form>
@endsection