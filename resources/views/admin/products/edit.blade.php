@extends('layouts.admin')

@section('title', 'Editar Producto')
@section('header', 'Editar Producto')

@section('content')
@if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <strong>¡Ups! Hay problemas con tu formulario:</strong>
            <ul class="list-disc ml-5 mt-2 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf 
    @method('PUT')
    @include('admin.products.form', ['product' => $product])
    <div class="mt-6">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Actualizar</button>
        <a href="{{ route('admin.products.index') }}" class="ml-2 text-gray-600 hover:underline">Cancelar</a>
    </div>
</form>
@endsection