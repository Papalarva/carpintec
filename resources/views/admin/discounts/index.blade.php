@extends('layouts.admin')

@section('title', 'Descuentos')
@section('header', 'Gestión de Descuentos')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.discounts.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">Nuevo Descuento</a>
</div>

<x-admin.table :headers="['Nombre', 'Tipo', 'Valor', 'Aplica a', 'Vigencia', 'Activo', 'Acciones']" :rows="$discounts">
    @foreach($discounts as $discount)
        <tr>
            <td class="px-6 py-4">{{ $discount->name }}</td>
            <td class="px-6 py-4">
                <x-admin.badge :color="$discount->type->color()" :label="$discount->type->label()" />
            </td>
            <td class="px-6 py-4">
                @if($discount->type === \App\Enums\DiscountType::PERCENTAGE)
                    {{ $discount->value }}%
                @else
                    ${{ number_format($discount->value, 2) }}
                @endif
            </td>
            <td class="px-6 py-4">{{ $discount->applies_to }}</td>
            <td class="px-6 py-4 text-sm">
                {{ $discount->starts_at?->format('d/m/Y') ?? '—' }}
                a {{ $discount->ends_at?->format('d/m/Y') ?? '—' }}
            </td>
            <td class="px-6 py-4">
                <x-admin.badge :color="$discount->is_active ? 'green' : 'red'" :label="$discount->is_active ? 'Sí' : 'No'" />
            </td>
            <td class="px-6 py-4 text-sm">
                <a href="{{ route('admin.discounts.edit', $discount) }}" class="text-indigo-600 hover:underline">Editar</a>
                <form method="POST" action="{{ route('admin.discounts.destroy', $discount) }}" class="inline" onsubmit="return confirm('¿Eliminar descuento?')">
                    @csrf @method('DELETE')
                    <button class="text-red-600 hover:underline ml-2">Eliminar</button>
                </form>
            </td>
        </tr>
    @endforeach
</x-admin.table>
@endsection