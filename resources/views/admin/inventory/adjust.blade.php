@extends('layouts.admin')

@section('title', 'Ajustar inventario de ' . $product->name)
@section('header', 'Ajustar stock')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-lg shadow p-6">
    <p class="mb-4 text-sm text-gray-600">
        Stock actual: <strong>{{ $product->inventory?->quantity ?? 0 }}</strong>
    </p>
    <form method="POST" action="{{ route('admin.inventory.store-adjustment', $product) }}">
        @csrf
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">
                Cantidad a ajustar (positivo = entrada, negativo = salida)
            </label>
            <input type="number" name="quantity" id="quantity" required
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm">
            @error('quantity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="reference" class="block text-sm font-medium text-gray-700">Motivo (opcional)</label>
            <input type="text" name="reference" id="reference"
                   class="mt-1 block w-full rounded border-gray-300 shadow-sm">
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.inventory.index') }}" class="bg-gray-200 px-4 py-2 rounded text-sm">Cancelar</a>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Ajustar</button>
        </div>
    </form>
</div>
@endsection