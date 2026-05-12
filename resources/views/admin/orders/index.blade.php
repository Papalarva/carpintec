@extends('layouts.admin')

@section('title', 'Pedidos')
@section('header', 'Gestión de Pedidos')

@section('content')
    <div class="mb-8 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <form method="GET" class="w-full flex flex-col md:flex-row gap-4">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por Folio (ID), nombre o email..."
                       class="block w-full pl-10 rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors font-sans">
            </div>
            
            <div class="w-full md:w-64">
                <select name="status" onchange="this.form.submit()" class="block w-full rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors font-sans bg-gray-50">
                    <option value="">Todos los estados</option>
                    @foreach (\App\Enums\OrderStatus::cases() as $st)
                        <option value="{{ $st->value }}" {{ ($status ?? '') == $st->value ? 'selected' : '' }}>
                            {{ $st->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <x-admin.table :headers="[
        'Folio' => 'id', 
        'Cliente' => null, 
        'Total' => 'total', 
        'Estado' => 'status_id', 
        'Fecha' => 'created_at', 
        'Acciones' => null
    ]">
        @forelse($orders as $order)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-900 font-sans">#{{ substr($order->id, 0, 8) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-sans">
                    <div class="flex flex-col">
                        <span class="text-gray-900 font-medium">
                            {{ $order->customer->user?->first_name ?? 'Usuario' }} {{ $order->customer->user?->last_name ?? 'Eliminado' }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $order->customer->user?->email ?? '' }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 font-sans">
                    ${{ number_format($order->total, 2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-admin.badge :color="$order->status_id->color()" :label="$order->status_id->label()" />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-sans">
                    {{ $order->created_at->format('d M Y, H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center">
                    <a href="{{ route('admin.orders.show', $order) }}" class="p-2 text-gray-400 hover:text-amber-900 hover:bg-amber-50 rounded-lg transition-colors" title="Ver Detalle">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="text-sm font-medium text-gray-500 font-sans">No se encontraron pedidos.</span>
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    <div class="mt-6 font-sans">
        {{ $orders->links() }}
    </div>
@endsection