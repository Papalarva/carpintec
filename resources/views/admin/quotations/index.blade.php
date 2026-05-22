@extends('layouts.admin')

@section('title', 'Cotizaciones')
@section('header', 'Gestión de Cotizaciones')

@section('content')
    <div class="mb-8 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <form method="GET" class="w-full flex flex-col md:flex-row gap-4">
            
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por asunto, cliente o email..."
                       class="block w-full pl-10 rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans">
            </div>
            
            <div class="w-full md:w-64">
                <select name="status" onchange="this.form.submit()" class="block w-full rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans bg-gray-50/50 cursor-pointer">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="reviewing" {{ ($status ?? '') == 'reviewing' ? 'selected' : '' }}>En revisión</option>
                    <option value="quoted" {{ ($status ?? '') == 'quoted' ? 'selected' : '' }}>Cotizada (Esperando respuesta)</option>
                    <option value="approved" {{ ($status ?? '') == 'approved' ? 'selected' : '' }}>Aprobada</option>
                    <option value="rejected" {{ ($status ?? '') == 'rejected' ? 'selected' : '' }}>Rechazada</option>
                </select>
            </div>
            
        </form>
    </div>

    <x-admin.table :headers="[
        'Fecha' => 'created_at',
        'Asunto' => 'subject', 
        'Cliente' => null, 
        'Producto' => null, 
        'Precio Est.' => 'estimated_price', 
        'Estado' => 'status', 
        'Acciones' => null
    ]">
        @forelse($quotations as $quotation)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium font-sans">
                    {{ $quotation->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 text-sm font-semibold text-gray-900 font-sans">
                    {{ Str::limit($quotation->subject, 40) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-sans">
                    {{ $quotation->customer->user?->first_name ?? 'Usuario' }} {{ $quotation->customer->user?->last_name ?? 'Eliminado' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-sans">
                    @if($quotation->product)
                        {{ $quotation->product->name }}
                    @else
                        <span class="italic text-gray-400">Proyecto a medida</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 font-sans">
                    {{ $quotation->estimated_price ? '$' . number_format($quotation->estimated_price, 2) : '—' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-admin.badge :color="$quotation->status->color()" :label="$quotation->status->label()" />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center font-sans">
                    <a href="{{ route('admin.quotations.show', $quotation) }}" class="p-2 text-gray-400 hover:text-amber-900 hover:bg-amber-50 rounded-xl transition-colors" title="Ver Detalles">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-16 text-center bg-white">
                    <svg class="mx-auto h-14 w-14 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-base font-medium text-gray-400 font-sans">No se encontraron cotizaciones.</span>
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    <div class="mt-6 font-sans">
        {{ $quotations->links() }}
    </div>
@endsection