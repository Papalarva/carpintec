@extends('layouts.admin')

@section('title', 'Gestionar Ficha de Inventario')
@section('header', 'Ficha de Almacén: ' . $product->name)

@section('content')
<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-8">
    
    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
        <div>
            <h3 class="text-lg font-playfair font-semibold text-gray-900">{{ $product->name }}</h3>
            <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">Stock Actual</p>
            <p class="text-3xl font-bold text-[#C15C3D]">{{ $product->inventory?->quantity ?? 0 }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.inventory.store-adjustment', $product) }}">
        @csrf
        
        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">1. Ajuste de Piezas</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 border-b border-gray-100 pb-6">
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                    Cantidad a sumar o restar
                </label>
                <input type="number" name="quantity" id="quantity" value="0" required
                       class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors"
                       placeholder="Ej: 5 o -2">
                <p class="text-xs text-gray-500 mt-1">Usa números negativos para reportar mermas o salidas.</p>
                @error('quantity') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Motivo / Referencia</label>
                <input type="text" name="reference" id="reference" value="{{ old('reference') }}"
                       class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors"
                       placeholder="Ej: Llegada de proveedor, Merma...">
            </div>
        </div>

        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">2. Configuración Logística</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label for="min_quantity" class="block text-sm font-medium text-gray-700 mb-1">Stock Mínimo (Alerta)</label>
                <input type="number" name="min_quantity" id="min_quantity" value="{{ old('min_quantity', $product->inventory?->min_quantity ?? 0) }}" required min="0"
                       class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
            </div>
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Ubicación Física</label>
                <input type="text" name="location" id="location" value="{{ old('location', $product->inventory?->location ?? '') }}"
                       class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors"
                       placeholder="Ej: Pasillo 3, Anaquel B">
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.inventory.index') }}" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancelar</a>
            <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-[#C15C3D] rounded-lg hover:bg-[#a64e32] transition-colors shadow-sm">Guardar Ficha</button>
        </div>
    </form>
</div>
@endsection