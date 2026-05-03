@extends('layouts.admin')

@section('title', 'Nuevo Cupón')
@section('header', 'Nuevo Cupón')

@section('content')
<form action="{{ route('admin.coupons.store') }}" method="POST">
    @csrf
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Descuento asociado</label>
            <select name="discount_id" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                <option value="">Selecciona un descuento</option>
                @foreach($discounts as $id => $name)
                    <option value="{{ $id }}" {{ old('discount_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            @error('discount_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Código del cupón</label>
            <input type="text" name="code" value="{{ old('code') }}"
                   placeholder="Dejar vacío para generar uno aleatorio"
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm">
            @error('code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Máximo de usos</label>
            <input type="number" name="max_uses" min="1" value="{{ old('max_uses') }}"
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm">
            @error('max_uses') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Fecha de expiración</label>
            <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm">
            @error('expires_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.coupons.index') }}" class="bg-gray-200 px-4 py-2 rounded text-sm">Cancelar</a>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Crear cupón</button>
        </div>
    </div>
</form>
@endsection