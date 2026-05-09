@extends('layouts.admin')
@section('title', 'Nueva Colección')
@section('header', 'Crear Nueva Colección')

@section('content')
    <form action="{{ route('admin.collections.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-lg font-playfair font-semibold text-gray-900 mb-4 border-b border-gray-100 pb-2">
                Información Básica
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 font-inter">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Colección</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
                    @error('name')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center mt-6 md:mt-7">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-[#C15C3D] shadow-sm focus:border-[#C15C3D] focus:ring focus:ring-[#C15C3D] focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900 font-inter">
                        Colección Activa (Visible al público)
                    </label>
                </div>
            </div>

            <div class="mt-6 font-inter">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción Corta</label>
                <textarea name="description" id="description" rows="3"
                    class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        @php
            $selectedIds = collect(old('products', $collectionProducts ?? []))
                ->map(fn($id) => (string) $id)
                ->toArray();
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6" x-data="{ selectedProducts: @js($selectedIds) }">
            <h3 class="text-lg font-playfair font-semibold text-gray-900 mb-4 border-b border-gray-100 pb-2">
                Productos de la Colección
            </h3>

            @error('products')
                <p class="text-rose-600 text-xs mt-1 mb-4">{{ $message }}</p>
            @enderror

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 font-inter">
                @foreach ($products as $product)
                    <label
                        class="relative border p-4 rounded-lg cursor-pointer transition flex flex-col items-center text-center h-full"
                        :class="selectedProducts.includes('{{ $product->id }}') ? 'border-[#C15C3D] bg-[#C15C3D]/5 shadow-sm' :
                            'border-gray-200 hover:border-[#C15C3D]/30'">

                        <input type="checkbox" name="products[]" value="{{ $product->id }}" class="hidden"
                            x-model="selectedProducts">

                        <div class="mb-2">
                            <svg x-cloak x-show="selectedProducts.includes('{{ $product->id }}')"
                                class="w-6 h-6 text-[#C15C3D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <svg x-cloak x-show="!selectedProducts.includes('{{ $product->id }}')"
                                class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>

                        <span class="text-sm text-gray-700">{{ $product->name }}</span>
                        <span class="text-xs text-gray-500 mt-1">SKU: {{ $product->sku }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-4 mb-8 font-inter">
            <a href="{{ route('admin.collections.index') }}"
                class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                class="bg-[#C15C3D] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#a64e32] transition-colors shadow-sm">
                Guardar Colección
            </button>
        </div>
    </form>
@endsection
