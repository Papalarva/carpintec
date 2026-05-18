@extends('layouts.admin')
@section('title', 'Nueva Colección')
@section('header', 'Crear Nueva Colección')

@section('content')
    <form action="{{ route('admin.collections.store') }}" method="POST" class="max-w-5xl mx-auto">
        @csrf

        {{-- Tarjeta: Información Básica --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">
                Información Básica
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-x-8 gap-y-7">
                {{-- Nombre (8 columnas) --}}
                <div class="md:col-span-8">
                    <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Nombre de la Colección</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans">
                    @error('name') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p> @enderror
                </div>

                {{-- Checkbox Activo (4 columnas) --}}
                <div class="md:col-span-4 flex items-center md:mt-6 bg-gray-50/30 rounded-xl px-4 border border-transparent hover:border-gray-100 transition-colors">
                    <label class="flex items-center cursor-pointer group w-full py-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-900 bg-white transition-colors cursor-pointer shadow-sm">
                        <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-gray-900 font-sans transition-colors">Visible al público</span>
                    </label>
                </div>

                {{-- Descripción (12 columnas) --}}
                <div class="md:col-span-12">
                    <label for="description" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Descripción Corta</label>
                    <textarea name="description" id="description" rows="3"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans resize-none">{{ old('description') }}</textarea>
                    @error('description') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        @php
            $selectedIds = collect(old('products', []))->map(fn($id) => (string) $id)->toArray();
        @endphp

        {{-- Tarjeta: Selección de Productos --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8" x-data="{ selectedProducts: @js($selectedIds) }">
            <h3 class="text-xl font-bold text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">
                Productos de la Colección
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-5 font-sans">
                @foreach ($products as $product)
                    <label class="relative border p-5 rounded-2xl cursor-pointer transition-all duration-300 flex flex-col items-center text-center h-full hover:-translate-y-0.5"
                        :class="selectedProducts.includes('{{ $product->id }}') ? 'border-amber-900 bg-amber-50/50 shadow-md' : 'border-gray-200 hover:border-amber-900/30 bg-white hover:shadow-sm'">

                        <input type="checkbox" name="products[]" value="{{ $product->id }}" class="hidden" x-model="selectedProducts">

                        <div class="mb-3 transform transition-transform duration-200" :class="selectedProducts.includes('{{ $product->id }}') ? 'scale-110' : 'scale-100'">
                            <svg x-cloak x-show="selectedProducts.includes('{{ $product->id }}')" class="w-7 h-7 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <svg x-cloak x-show="!selectedProducts.includes('{{ $product->id }}')" class="w-7 h-7 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>

                        <span class="text-sm font-bold text-gray-900 leading-tight">{{ $product->name }}</span>
                        <span class="text-[10px] text-gray-500 mt-2 font-mono uppercase tracking-widest">SKU: {{ $product->sku }}</span>
                    </label>
                @endforeach
            </div>
            @error('products') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-4 font-sans">{{ $message }}</p> @enderror
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 mb-8 pt-4">
            <a href="{{ route('admin.collections.index') }}" class="w-full sm:w-auto text-center bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-colors duration-200 shadow-sm font-sans focus:outline-none">
                Cancelar
            </a>
            <button type="submit" class="w-full sm:w-auto bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-all duration-200 shadow-sm hover:shadow-md font-sans text-center focus:outline-none">
                Guardar Colección
            </button>
        </div>
    </form>
@endsection