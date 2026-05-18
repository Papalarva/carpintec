@extends('layouts.admin')

@section('title', 'Productos')
@section('header', 'Catálogo de Productos')

@section('content')
    {{-- Estado global de Alpine para manejar las URLs dinámicas de los modales --}}
    <div x-data="{ actionUrl: '' }">

        {{-- Panel de Control Superior --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <form method="GET" class="flex flex-1 w-full md:w-auto items-center gap-4">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Buscar por nombre o SKU..."
                        class="block w-full pl-10 rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans">
                </div>

                {{-- Toggle Elegante para Papelera (Color corporativo) --}}
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="trashed" value="1" {{ $showTrashed ? 'checked' : '' }}
                        onchange="this.form.submit()" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-900">
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-700 font-sans">Ver inactivos</span>
                </label>
            </form>

            <a href="{{ route('admin.products.create') }}"
                class="bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-8 py-4 transition-all duration-200 shadow-sm hover:shadow-md font-sans whitespace-nowrap">
                Nuevo Producto
            </a>
        </div>

        <x-admin.table :headers="[
            'SKU' => 'sku',
            'Nombre' => 'name',
            'Categoría' => 'category_id',
            'Precio' => 'price',
            'Stock' => 'quantity',
            'Estado' => 'is_active',
            'Acciones' => null,
        ]">
            @forelse($products as $product)
                <tr class="transition-colors hover:bg-gray-50/50 {{ $product->trashed() ? 'opacity-60 grayscale' : '' }}">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-500 font-sans">{{ $product->sku }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-gray-900 font-sans">{{ $product->name }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 font-sans">
                        {{ $product->category?->name ?? 'Sin categoría' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium font-sans">
                        ${{ number_format($product->price, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm font-sans">
                        @if ($product->track_inventory)
                            <span class="text-gray-900 font-medium">{{ $product->inventory?->quantity ?? 0 }} u.</span>
                            @if ($product->inventory && $product->inventory->isLowStock())
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100 uppercase tracking-wide">
                                    Bajo
                                </span>
                            @endif
                        @else
                            <span class="text-gray-400 italic">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-sans">
                        @if ($product->trashed())
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                Eliminado
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                {{ $product->is_active ? 'Público' : 'Oculto' }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm flex items-center gap-1 font-sans">
                        @if (!$product->trashed())
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="p-2 text-gray-400 hover:text-amber-900 hover:bg-amber-50 rounded-xl transition-colors"
                                title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.113l-3.53 1.08 1.08-3.53a4.5 4.5 0 011.113-1.89l3.4-1.341z"></path>
                                </svg>
                            </a>
                            <button @click="actionUrl = '{{ route('admin.products.destroy', $product) }}'; $dispatch('open-modal', 'delete-modal')"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors"
                                title="Ocultar (Papelera)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                </svg>
                            </button>
                        @else
                            <form method="POST" action="{{ route('admin.products.restore', $product->id) }}" class="m-0">
                                @csrf
                                <button class="p-2 text-gray-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-xl transition-colors" title="Restaurar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"></path>
                                    </svg>
                                </button>
                            </form>
                            <button @click="actionUrl = '{{ route('admin.products.force-delete', $product->id) }}'; $dispatch('open-modal', 'force-delete-modal')"
                                class="p-2 text-gray-400 hover:text-red-700 hover:bg-red-100 rounded-xl transition-colors"
                                title="Eliminar Definitivamente">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center bg-white">
                        <svg class="mx-auto h-14 w-14 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="text-base font-medium text-gray-400 font-sans">No se encontraron productos.</span>
                    </td>
                </tr>
            @endforelse
        </x-admin.table> {{-- Paginación Boutique --}}
        <div class="mt-6 font-sans">
            {{ $products->appends(request()->query())->links() }}
        </div>

        {{-- Contenedor de Modales (Separado del DOM de la tabla) --}}
        <div>
            <x-admin.modal id="delete-modal" title="Mover a Papelera">
                <p class="text-sm text-gray-600 leading-relaxed font-sans">¿Deseas ocultar este producto de la tienda y enviarlo a la papelera? Podrás restaurarlo más adelante si cambias de opinión.</p>
                <x-slot name="footer">
                    <div class="flex items-center justify-end gap-3 w-full font-sans">
                        <button @click="$dispatch('close-modal')" type="button"
                            class="min-w-[140px] px-6 py-3.5 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 rounded-xl font-bold text-xs uppercase tracking-widest transition-colors shadow-sm focus:outline-none">
                            Cancelar
                        </button>
                        <form :action="actionUrl" method="POST" class="m-0">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="min-w-[140px] px-6 py-3.5 bg-red-700 text-white hover:bg-red-800 rounded-xl font-bold text-xs uppercase tracking-widest transition-all duration-200 shadow-sm focus:outline-none">
                                Ocultar
                            </button>
                        </form>
                    </div>
                </x-slot>
            </x-admin.modal>

            <x-admin.modal id="force-delete-modal" title="Eliminar Definitivamente">
                <p class="text-sm text-gray-600 leading-relaxed font-sans">Esta acción eliminará el registro de la pieza de forma <span class="font-bold text-red-600">permanente</span>. No será posible recuperar su información ni sus imágenes asociadas una vez confirmada.</p>
                <x-slot name="footer">
                    <div class="flex items-center justify-end gap-3 w-full font-sans">
                        <button @click="$dispatch('close-modal')" type="button"
                            class="min-w-[140px] px-6 py-3.5 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 rounded-xl font-bold text-xs uppercase tracking-widest transition-colors shadow-sm focus:outline-none">
                            Cancelar
                        </button>
                        <form :action="actionUrl" method="POST" class="m-0">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="min-w-[140px] px-6 py-3.5 bg-gray-900 text-white hover:bg-black rounded-xl font-bold text-xs uppercase tracking-widest transition-all duration-200 shadow-sm focus:outline-none">
                                Confirmar
                            </button>
                        </form>
                    </div>
                </x-slot>
            </x-admin.modal>
        </div>

    </div>
@endsection