@extends('layouts.admin')

@section('title', 'Nuevo Cupón')
@section('header', 'Crear Cupón')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-3xl mx-auto">
    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        
        <div class="p-6 md:p-8">
            <h3 class="text-lg font-playfair font-semibold text-gray-900 mb-6 border-b border-gray-100 pb-2">Configuración del Cupón</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Descuento -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descuento Asociado <span class="text-rose-500">*</span></label>
                    <select name="discount_id" required class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
                        <option value="">Selecciona la regla de descuento a aplicar</option>
                        @foreach($discounts as $id => $name)
                            <option value="{{ $id }}" {{ old('discount_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('discount_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Código -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código Promocional</label>
                    <input type="text" name="code" value="{{ old('code') }}"
                           placeholder="Ej. BUENFIN2026 (Dejar vacío para autogenerar)"
                           class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm font-mono uppercase focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
                    <p class="text-xs text-gray-400 mt-1">Los clientes deberán ingresar este código exacto en su carrito.</p>
                    @error('code') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Usos Máximos -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Límite de Usos</label>
                    <input type="number" name="max_uses" min="1" value="{{ old('max_uses') }}"
                           placeholder="Ej. 100"
                           class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
                    <p class="text-xs text-gray-400 mt-1">Dejar vacío para usos ilimitados.</p>
                    @error('max_uses') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Expiración -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha y Hora de Expiración</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                           class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
                    @error('expires_at') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        
        <!-- Pie del formulario -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl flex items-center justify-end gap-3">
            <a href="{{ route('admin.coupons.index') }}" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-5 py-2 bg-[#C15C3D] border border-transparent rounded-lg font-medium text-sm text-white hover:bg-[#a34b30] focus:outline-none transition-colors">
                Crear Cupón
            </button>
        </div>
    </form>
</div>
@endsection