@extends('layouts.admin')
@section('title', 'Nuevo Descuento')
@section('header', 'Nuevo Descuento')
@section('content')
<form action="{{ route('admin.discounts.store') }}" method="POST">
    @csrf
    @include('admin.discounts._form', ['discount' => null])
    <button class="bg-indigo-600 text-white px-4 py-2 rounded">Crear</button>
</form>
@endsection