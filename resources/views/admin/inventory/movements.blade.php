@extends('layouts.admin')

@section('title', 'Movimientos de ' . $product->name)
@section('header', 'Movimientos de ' . $product->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.inventory.index') }}" class="text-indigo-600 hover:underline">← Volver al inventario</a>
</div>

<x-admin.table :headers="['Tipo', 'Cantidad', 'Resultante', 'Referencia', 'Usuario', 'Fecha']" :rows="$movements">
    @forelse($movements as $mov)
        <tr>
            <td class="px-6 py-4">
                @switch($mov->movement_type)
                    @case('sale') <x-admin.badge color="red" label="Venta" /> @break
                    @case('restock') <x-admin.badge color="green" label="Entrada" /> @break
                    @case('adjustment') <x-admin.badge color="blue" label="Ajuste" /> @break
                    @case('return') <x-admin.badge color="yellow" label="Devolución" /> @break
                    @default <span>{{ $mov->movement_type }}</span>
                @endswitch
            </td>
            <td class="px-6 py-4 text-right font-mono {{ $mov->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $mov->quantity > 0 ? '+' : '' }}{{ $mov->quantity }}
            </td>
            <td class="px-6 py-4 text-right font-mono">{{ $mov->resulting_quantity }}</td>
            <td class="px-6 py-4">{{ $mov->reference }}</td>
            <td class="px-6 py-4">{{ $mov->user?->first_name ?? 'Sistema' }}</td>
            <td class="px-6 py-4 text-sm">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    @empty
        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Sin movimientos registrados.</td></tr>
    @endforelse
</x-admin.table>
@endsection