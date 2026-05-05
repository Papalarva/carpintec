@extends('layouts.admin')

@section('title', 'Cupones')
@section('header', 'Gestión de Cupones')

@section('content')
<!-- Contenedor Tarjeta Ejecutiva -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    
    <!-- Barra superior -->
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <h2 class="text-lg font-medium text-gray-800 font-playfair">Listado de Promociones</h2>
        
        <a href="{{ route('admin.coupons.create') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-[#C15C3D] border border-transparent rounded-lg font-medium text-sm text-white hover:bg-[#a34b30] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C15C3D] transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Cupón
        </a>
    </div>

    <!-- La Tabla -->
    <x-admin.table :headers="['Código', 'Descuento Asociado', 'Usos', 'Expiración', 'Acciones']">
        @forelse($coupons as $coupon)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <!-- Código estilo Ticket -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="font-mono text-xs font-semibold tracking-wider text-[#C15C3D] bg-[#C15C3D]/10 px-2.5 py-1 rounded-md border border-[#C15C3D]/20">
                        {{ strtoupper($coupon->code) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                    {{ $coupon->discount->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <span class="{{ $coupon->used_count >= ($coupon->max_uses ?? 999999) ? 'text-rose-600 font-semibold' : '' }}">
                        {{ $coupon->used_count }}
                    </span> 
                    <span class="text-gray-400">/ {{ $coupon->max_uses ?? '∞' }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($coupon->expires_at)
                        <span class="{{ $coupon->expires_at->isPast() ? 'text-rose-500 font-medium' : 'text-gray-500' }}">
                            {{ $coupon->expires_at->format('d M, Y') }}
                        </span>
                    @else
                        <span class="text-gray-400 italic">Sin caducidad</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-gray-400 hover:text-[#C15C3D] transition-colors mr-3">Editar</a>
                    <button onclick="confirmDelete('{{ $coupon->id }}')" class="text-gray-400 hover:text-rose-600 transition-colors">Eliminar</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No se encontraron cupones registrados.</td>
            </tr>
        @endforelse
    </x-admin.table>
</div>

<!-- Modal de Eliminación Seguro -->
<x-admin.modal id="delete-modal" title="Eliminar Cupón">
    <p>¿Estás seguro de que deseas eliminar este cupón? Los clientes ya no podrán utilizar este código para obtener descuentos.</p>
    <x-slot name="footer">
        <form id="delete-form" method="POST">
            @csrf @method('DELETE')
            <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none transition-colors">Cancelar</button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-rose-600 border border-transparent rounded-lg hover:bg-rose-700 focus:outline-none transition-colors">Sí, eliminar</button>
        </form>
    </x-slot>
</x-admin.modal>

<script>
function confirmDelete(couponId) {
    const form = document.getElementById('delete-form');
    form.action = '/admin/coupons/' + couponId;
    document.getElementById('delete-modal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}
</script>
@endsection