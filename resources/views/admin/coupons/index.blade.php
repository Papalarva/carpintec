@extends('layouts.admin')
@section('title', 'Cupones')
@section('header', 'Cupones')
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.coupons.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">Nuevo Cupón</a>
</div>
<x-admin.table :headers="['Código', 'Descuento', 'Usos', 'Expira', 'Acciones']" :rows="$coupons">
    @foreach($coupons as $coupon)
        <tr>
            <td class="px-6 py-4 font-mono">{{ $coupon->code }}</td>
            <td class="px-6 py-4">{{ $coupon->discount->name }}</td>
            <td class="px-6 py-4">{{ $coupon->used_count }}/{{ $coupon->max_uses ?? '∞' }}</td>
            <td class="px-6 py-4">{{ $coupon->expires_at?->format('d/m/Y') ?? '—' }}</td>
            <td class="px-6 py-4 text-sm">
                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-indigo-600 hover:underline">Editar</a>
                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="inline">
                    @csrf @method('DELETE')
                    <button class="text-red-600 hover:underline ml-2">Eliminar</button>
                </form>
            </td>
        </tr>
    @endforeach
</x-admin.table>
@endsection