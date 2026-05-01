@extends('layouts.admin')

@section('title', 'Editar Categoría')
@section('header', 'Editar Categoría')

@section('content')
<form method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf @method('PUT')
    @include('admin.categories.form', ['category' => $category])
    <div class="mt-6">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Actualizar</button>
        <a href="{{ route('admin.categories.index') }}" class="ml-2 text-gray-600 hover:underline">Cancelar</a>
    </div>
</form>
@endsection