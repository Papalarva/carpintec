@extends('layouts.admin')

@section('title', 'Cotizaciones')
@section('header', 'Cotizaciones')

@section('content')
    <div class="mb-4 flex flex-wrap gap-2 items-center justify-between">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por asunto o cliente..."
                class="rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select name="status" class="rounded border-gray-300" onchange="this.form.submit()">
                <option value="">Todos los estados</option>
                <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="reviewing" {{ ($status ?? '') == 'reviewing' ? 'selected' : '' }}>En revisión</option>
                <option value="approved" {{ ($status ?? '') == 'approved' ? 'selected' : '' }}>Aprobada</option>
                <option value="rejected" {{ ($status ?? '') == 'rejected' ? 'selected' : '' }}>Rechazada</option>
            </select>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Filtrar</button>
        </form>
    </div>

    <x-admin.table :headers="['Asunto', 'Cliente', 'Producto', 'Precio est.', 'Estado', 'Acciones']" :rows="$quotations">
        @forelse($quotations as $quotation)
            <tr>
                <td class="px-6 py-4">{{ Str::limit($quotation->subject, 40) }}</td>
                <td class="px-6 py-4">
                    {{ $quotation->customer->user?->first_name ?? 'Usuario' }}
                    {{ $quotation->customer->user?->last_name ?? 'Eliminado' }}
                </td>
                <td class="px-6 py-4">{{ $quotation->product?->name ?? 'Proyecto a medida' }}</td>
                <td class="px-6 py-4">
                    @if ($quotation->estimated_price)
                        ${{ number_format($quotation->estimated_price, 2) }}
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <x-admin.badge :color="$quotation->status->color()" :label="$quotation->status->label()" />
                </td>
                <td class="px-6 py-4 text-sm">
                    <a href="{{ route('admin.quotations.show', $quotation) }}"
                        class="text-indigo-600 hover:text-indigo-900">Ver</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay cotizaciones.</td>
            </tr>
        @endforelse
    </x-admin.table>
@endsection
