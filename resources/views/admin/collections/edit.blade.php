@extends('layouts.admin')
@section('title', 'Editar Colección')
@section('header', 'Editar Colección: ' . $collection->name)

@section('content')
    @if($errors->any())
        <div class="mb-8 bg-red-50 border-l-4 border-red-700 p-4 rounded-r-xl shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 font-serif">Por favor, revisa los siguientes errores:</h3>
                    <div class="mt-2 text-sm text-red-700 font-sans">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.collections.update', $collection) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <h3 class="text-xl font-serif font-semibold text-gray-900 mb-6 border-b border-gray-100 pb-4">
                Información Básica
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 font-sans">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Colección</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $collection->name) }}"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3.5 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm">
                </div>

                <div class="flex items-center md:mt-8">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $collection->is_active) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-800 bg-gray-50 transition-colors">
                        <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900">Colección Activa (Visible al público)</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 font-sans">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción Corta</label>
                <textarea name="description" id="description" rows="3"
                    class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3.5 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm">{{ old('description', $collection->description) }}</textarea>
            </div>
        </div>

        @php
            $selectedIds = collect(old('products', $collectionProducts ?? []))->map(fn($id) => (string) $id)->toArray();
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8" x-data="{ selectedProducts: @js($selectedIds) }">
            <h3 class="text-xl font-serif font-semibold text-gray-900 mb-6 border-b border-gray-100 pb-4">
                Productos de la Colección
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 font-sans">
                @foreach ($products as $product)
                    <label class="relative border p-4 rounded-xl cursor-pointer transition-all duration-200 flex flex-col items-center text-center h-full"
                        :class="selectedProducts.includes('{{ $product->id }}') ? 'border-amber-900 bg-amber-50 shadow-sm' : 'border-gray-200 hover:border-amber-900/30'">

                        <input type="checkbox" name="products[]" value="{{ $product->id }}" class="hidden" x-model="selectedProducts">

                        <div class="mb-3">
                            <svg x-cloak x-show="selectedProducts.includes('{{ $product->id }}')" class="w-6 h-6 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <svg x-cloak x-show="!selectedProducts.includes('{{ $product->id }}')" class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>

                        <span class="text-sm font-medium text-gray-900">{{ $product->name }}</span>
                        <span class="text-xs text-gray-500 mt-1 font-mono">SKU: {{ $product->sku }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 mb-8">
            <a href="{{ route('admin.collections.index') }}" class="w-full sm:w-auto bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm font-sans text-center">
                Cancelar
            </a>
            <button type="submit" class="w-full sm:w-auto bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm font-sans text-center">
                Actualizar Colección
            </button>
        </div>
    </form>
@endsection