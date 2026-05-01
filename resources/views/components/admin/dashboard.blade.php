@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    {{-- Tarjetas de resumen --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-admin.stat-card
            label="Órdenes Totales"
            :value="$totalOrders"
            bgColorClass="bg-blue-100"
            textColorClass="text-blue-600"
        />
        <x-admin.stat-card
            label="Ingresos Totales"
            value="${{ number_format($totalRevenue, 2) }}"
            bgColorClass="bg-green-100"
            textColorClass="text-green-600"
        />
        <x-admin.stat-card
            label="Nuevos Clientes (Mes)"
            :value="$newCustomersThisMonth"
            bgColorClass="bg-yellow-100"
            textColorClass="text-yellow-600"
        />
        <x-admin.stat-card
            label="Cotizaciones Pendientes"
            :value="$pendingQuotations"
            bgColorClass="bg-red-100"
            textColorClass="text-red-600"
        />
    </div>

    {{-- Gráfico y resumen adicional --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <x-admin.chart :config="$salesChartConfig" currencyY />

        <div class="bg-white rounded-lg shadow p-5">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Resumen Rápido</h3>
            <ul class="space-y-3">
                <li class="flex justify-between">
                    <span class="text-gray-500">Productos con bajo stock:</span>
                    <span class="font-semibold text-red-600">{{ $lowStockProducts }}</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-500">Cotizaciones pendientes:</span>
                    <span class="font-semibold">{{ $pendingQuotations }}</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-gray-500">Nuevos clientes este mes:</span>
                    <span class="font-semibold">{{ $newCustomersThisMonth }}</span>
                </li>
            </ul>
        </div>
    </div>
@endsection