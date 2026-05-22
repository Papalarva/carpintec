@extends('layouts.admin')

@section('title', 'Cupones')
@section('header', 'Gestión de Cupones')

@section('content')

<div x-data="{ actionUrl: '' }">
    
    {{-- Panel de Filtros Superior --}}
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form method="GET" class="flex flex-1 w-full md:w-auto items-center gap-4">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por código o descuento..."
                       class="block w-full pl-11 pr-4 rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans bg-gray-50/50">
            </div>
            <button type="submit" class="hidden md:block bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl px-6 py-3.5 text-xs font-bold uppercase tracking-widest transition-colors font-sans shadow-sm focus:outline-none">
                Buscar
            </button>
        </form>

        <a href="{{ route('admin.coupons.create') }}" class="w-full md:w-auto text-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-8 py-4 transition-all duration-200 shadow-sm hover:shadow-md font-sans whitespace-nowrap">
            Nuevo Cupón
        </a>
    </div>

    {{-- Tabla Inteligente (Sin envoltura extra para evitar doble bordeado) --}}
    <x-admin.table :headers="[
        'Código' => 'code', 
        'Descuento Asociado' => null, 
        'Usos' => 'used_count', 
        'Expiración' => 'expires_at', 
        'Acciones' => null
    ]">
        @forelse($coupons as $coupon)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    {{-- Diseño estilo Ticket Premium --}}
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold font-mono tracking-widest text-amber-900 bg-amber-50 border-2 border-dashed border-amber-200/80 shadow-sm">
                        {{ $coupon->code }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-gray-900 font-sans">{{ $coupon->discount->name }}</span>
                        <span class="text-xs font-medium text-gray-500 font-sans mt-0.5">
                            {{ $coupon->discount->type === \App\Enums\DiscountType::PERCENTAGE ? rtrim(rtrim($coupon->discount->value, '0'), '.') . '%' : '$' . number_format($coupon->discount->value, 2) }}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-sans">
                    @php $isMaxed = $coupon->max_uses && $coupon->used_count >= $coupon->max_uses; @endphp
                    <span class="{{ $isMaxed ? 'text-rose-700 font-bold' : 'text-gray-900 font-medium' }}">
                        {{ $coupon->used_count }}
                    </span> 
                    <span class="text-gray-400 mx-1">/</span> 
                    <span class="text-gray-500 font-medium">{{ $coupon->max_uses ?? '∞' }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-sans">
                    @if($coupon->expires_at)
                        @if($coupon->expires_at->isPast())
                            <span class="inline-flex items-center gap-1.5 text-rose-700 font-bold bg-rose-50 border border-rose-100 px-2.5 py-1 rounded-xl text-xs tracking-wide">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Expirado
                            </span>
                        @else
                            <span class="text-gray-600 font-medium">{{ $coupon->expires_at->format('d/m/Y H:i') }}</span>
                        @endif
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200">
                            Sin caducidad
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center gap-1">
                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-2 text-gray-400 hover:text-amber-900 hover:bg-amber-50 rounded-xl transition-colors" title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.113l-3.53 1.08 1.08-3.53a4.5 4.5 0 011.113-1.89l3.4-1.341z"></path>
                        </svg>
                    </a>
                    <button @click="actionUrl = '{{ route('admin.coupons.destroy', $coupon) }}'; $dispatch('open-modal', 'delete-modal')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors" title="Eliminar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                        </svg>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-16 text-center bg-white">
                    <svg class="mx-auto h-14 w-14 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-base font-medium text-gray-400 font-sans">No se encontraron cupones promocionales.</span>
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    <div class="mt-8 font-sans">
        {{ $coupons->links() }}
    </div>

    <x-admin.modal id="delete-modal" title="Eliminar Cupón">
        <p class="text-sm text-gray-600 leading-relaxed font-sans">
            ¿Estás seguro de que deseas eliminar este cupón? Los clientes ya no podrán utilizar este código para obtener descuentos en su carrito de compras.
        </p>
        <x-slot name="footer">
            <div class="flex items-center justify-end gap-3 w-full font-sans">
                <button @click="$dispatch('close-modal')" type="button" class="min-w-[140px] px-6 py-3.5 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 rounded-xl font-bold text-xs uppercase tracking-widest transition-colors shadow-sm focus:outline-none">
                    Cancelar
                </button>
                <form :action="actionUrl" method="POST" class="m-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="min-w-[140px] px-6 py-3.5 bg-red-700 text-white hover:bg-red-800 rounded-xl font-bold text-xs uppercase tracking-widest transition-all duration-200 shadow-sm focus:outline-none">
                        Eliminar
                    </button>
                </form>
            </div>
        </x-slot>
    </x-admin.modal>

</div>
@endsection