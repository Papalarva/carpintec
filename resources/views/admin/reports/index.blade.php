@extends('layouts.admin')

@section('title', 'Inteligencia de Negocios')
@section('header', 'Reportes y Analítica')

@section('content')

@php
    $chartId = 'revenueChart_' . Str::random(8);
    // Calculamos totales rápidos para las tarjetas
    $totalHistoricalRevenue = $salesByMonth->sum('revenue');
    $totalHistoricalOrders = $salesByMonth->sum('count');
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Ingresos Históricos Válidos</p>
            <p class="text-3xl font-bold text-[#C15C3D]">${{ number_format($totalHistoricalRevenue, 2) }}</p>
        </div>
        <div class="p-4 bg-orange-50 rounded-full text-[#C15C3D]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total de Pedidos Completados</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalHistoricalOrders) }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-full text-gray-400">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="text-lg font-playfair font-semibold text-gray-900 mb-4 border-b border-gray-100 pb-2">Evolución de Ingresos</h3>
    @if(count($chartData['labels']) > 0)
        <div class="relative h-80 w-full">
            <canvas id="{{ $chartId }}"></canvas>
        </div>
    @else
        <div class="flex items-center justify-center h-48 bg-gray-50 rounded-lg border border-dashed border-gray-200">
            <span class="text-sm text-gray-500">Aún no hay datos de ventas registrados para graficar.</span>
        </div>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-base font-semibold text-gray-900">Desglose Mensual</h3>
        </div>
        <x-admin.table :headers="['Mes', 'Pedidos', 'Ingresos generados']" :rows="$salesByMonth">
            @forelse($salesByMonth as $data)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 capitalize">{{ $data['month'] }}</td>
                    <td class="px-6 py-4 text-sm text-center font-mono">{{ $data['count'] }}</td>
                    <td class="px-6 py-4 text-sm text-right font-medium text-[#C15C3D]">${{ number_format($data['revenue'], 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">Sin datos registrados.</td></tr>
            @endforelse
        </x-admin.table>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-base font-semibold text-gray-900">Top 5 Productos Más Rentables</h3>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Basado en ingresos</span>
        </div>
        <x-admin.table :headers="['Producto', 'Uds. Vendidas', 'Ingreso Total']" :rows="$topProducts">
            @forelse($topProducts as $top)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $top->name }}</div>
                        <div class="text-xs text-gray-500">{{ $top->sku }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-center font-mono">{{ $top->total_sold }}</td>
                    <td class="px-6 py-4 text-sm text-right font-medium text-gray-900">${{ number_format($top->total_revenue, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">Aún no hay productos vendidos.</td></tr>
            @endforelse
        </x-admin.table>
    </div>

</div>
@endsection

@push('scripts')
    @if(count($chartData['labels']) > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
                
                const labels = @json($chartData['labels']);
                const data = @json($chartData['revenues']);

                new Chart(ctx, {
                    type: 'bar', // 'bar' es excelente para ver ingresos por mes, pero puedes cambiarlo a 'line'
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Ingresos Netos ($)',
                            data: data,
                            backgroundColor: '#C15C3D', // Terracota sólido para barras
                            hoverBackgroundColor: '#a64e32',
                            borderRadius: 6, // Bordes redondeados en las barras
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let value = context.raw;
                                        return '$' + value.toLocaleString('en-US', {minimumFractionDigits: 2});
                                    }
                                }
                            }
                        },
                        scales: {
                            x: { grid: { display: false } },
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: '#f3f4f6' },
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value.toLocaleString('en-US');
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endif
@endpush