@extends('layouts.admin')

@section('title', 'Nueva Categoría')
@section('header', 'Nueva Categoría')

@section('content')
<form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    @include('admin.categories.form', ['category' => null])
    <div class="mt-6">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Crear</button>
        <a href="{{ route('admin.categories.index') }}" class="ml-2 text-gray-600 hover:underline">Cancelar</a>
    </div>
</form>
@endsection