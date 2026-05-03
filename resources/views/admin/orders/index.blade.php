@extends('layouts.admin')

@section('title', 'Pedidos')
@section('header', 'Pedidos')

@section('content')
<div class="mb-4 flex flex-wrap gap-2 items-center justify-between">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por ID o cliente…"
               class="rounded border-gray-300">
        <select name="status" class="rounded border-gray-300" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            @foreach(\App\Enums\OrderStatus::cases() as $st)
                <option value="{{ $st->value }}" {{ ($status ?? '') == $st->value ? 'selected' : '' }}>
                    {{ $st->label() }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Filtrar</button>
    </form>
</div>

<x-admin.table :headers="['ID', 'Cliente', 'Total', 'Estado', 'Fecha', 'Acciones']" :rows="$orders">
    @forelse($orders as $order)
        <tr>
            <td class="px-6 py-4 font-mono text-sm">{{ $order->id }}</td>
            <td class="px-6 py-4">{{ $order->customer->user->first_name }} {{ $order->customer->user->last_name }}</td>
            <td class="px-6 py-4">${{ number_format($order->total, 2) }}</td>
            <td class="px-6 py-4">
                <x-admin.badge :color="$order->status_id->color()" :label="$order->status_id->label()" />
            </td>
            <td class="px-6 py-4 text-sm">{{ $order->created_at->format('d/m/Y') }}</td>
            <td class="px-6 py-4 text-sm">
                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline">Ver</a>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay pedidos.</td></tr>
    @endforelse
</x-admin.table>
@endsection