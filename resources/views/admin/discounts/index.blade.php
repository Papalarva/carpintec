@extends('layouts.admin')

@section('title', 'Descuentos')
@section('header', 'Gestión de Descuentos')

@section('content')

    @if (session('success'))
        <div class="mb-8 bg-green-50 border-l-4 border-green-700 p-4 rounded-r-xl shadow-sm">
            <p class="font-bold text-green-800 font-serif">¡Éxito!</p>
            <p class="text-green-700 font-sans text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-8 bg-red-50 border-l-4 border-red-700 p-4 rounded-r-xl shadow-sm">
            <p class="font-bold text-red-800 font-serif">Aviso</p>
            <p class="text-red-700 font-sans text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <div x-data="{ actionUrl: '' }">
        <div
            class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <form method="GET" class="flex flex-1 w-full md:w-auto items-center gap-4">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Buscar descuento por nombre..."
                        class="block w-full pl-10 rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors font-sans">
                </div>
            </form>

            <a href="{{ route('admin.discounts.create') }}"
                class="bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm font-sans whitespace-nowrap">
                Nuevo Descuento
            </a>
        </div>

        <x-admin.table :headers="[
            'Nombre' => 'name',
            'Tipo' => 'type',
            'Valor' => 'value',
            'Aplica a' => 'applies_to',
            'Vigencia' => null,
            'Estado' => 'is_active',
            'Acciones' => null,
        ]">
            @forelse($discounts as $discount)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 font-sans">
                        {{ $discount->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-admin.badge :color="$discount->type->color()" :label="$discount->type->label()" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 font-sans">
                        @if ($discount->type === \App\Enums\DiscountType::PERCENTAGE)
                            {{ rtrim(rtrim($discount->value, '0'), '.') }}%
                        @else
                            ${{ number_format($discount->value, 2) }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-sans capitalize">
                        @php
                            // Mapeo de términos técnicos a etiquetas boutique en español
                            $appliesLabels = [
                                'all' => 'Toda la tienda',
                                'products' => 'Productos específicos',
                                'categories' => 'Categorías específicas',
                                'customers' => 'Clientes seleccionados',
                            ];

                            $label = $appliesLabels[$discount->applies_to] ?? 'No definido';
                        @endphp

                        <span>
                            {{ $label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-sans">
                        @if ($discount->starts_at || $discount->ends_at)
                            <div class="flex flex-col">
                                <span>Desde: {{ $discount->starts_at?->format('d/m/Y') ?? 'Siempre' }}</span>
                                <span>Hasta: {{ $discount->ends_at?->format('d/m/Y') ?? 'Indefinido' }}</span>
                            </div>
                        @else
                            <span class="italic">Permanente</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-admin.badge :color="$discount->is_active ? 'green' : 'gray'" :label="$discount->is_active ? 'Activo' : 'Inactivo'" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center gap-2">
                        <a href="{{ route('admin.discounts.edit', $discount) }}"
                            class="p-2 text-gray-400 hover:text-amber-900 hover:bg-amber-50 rounded-lg transition-colors"
                            title="Editar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.113l-3.53 1.08 1.08-3.53a4.5 4.5 0 011.113-1.89l3.4-1.341z">
                                </path>
                            </svg>
                        </a>
                        <button
                            @click="actionUrl = '{{ route('admin.discounts.destroy', $discount) }}'; $dispatch('open-modal', 'delete-modal')"
                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                            title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0">
                                </path>
                            </svg>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium text-gray-500 font-sans">No se encontraron descuentos.</span>
                    </td>
                </tr>
            @endforelse
        </x-admin.table>

        <div class="mt-6 font-sans">
            {{ $discounts->links() }}
        </div>

        <x-admin.modal id="delete-modal" title="Eliminar Descuento">
            <p class="text-gray-600 font-sans">¿Estás seguro de que deseas eliminar este descuento? Si ya tiene cupones
                asociados no podrá ser borrado.</p>
            <x-slot name="footer">
                <div class="flex items-center justify-end gap-3 w-full">
                    <button @click="$dispatch('close-modal')" type="button"
                        class="min-w-[140px] px-6 py-3 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 rounded-xl font-bold text-sm uppercase tracking-widest transition-colors font-sans shadow-sm">
                        Cancelar
                    </button>
                    <form :action="actionUrl" method="POST" class="m-0">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="min-w-[140px] px-6 py-3 bg-red-800 text-white hover:bg-red-900 rounded-xl font-bold text-sm uppercase tracking-widest transition-colors font-sans shadow-sm">
                            Eliminar
                        </button>
                    </form>
                </div>
            </x-slot>
        </x-admin.modal>
    </div>
@endsection
