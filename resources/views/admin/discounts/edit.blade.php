@extends('layouts.admin')
@section('title', 'Editar Descuento')
@section('header', 'Editar Descuento')
@section('content')
<form action="{{ route('admin.discounts.update', $discount) }}" method="POST">
    @csrf @method('PUT')
    @include('admin.discounts._form', ['discount' => $discount])
    <button class="bg-indigo-600 text-white px-4 py-2 rounded">Actualizar</button>
</form>
@endsection