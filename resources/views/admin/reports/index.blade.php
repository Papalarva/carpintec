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

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-6 transition-all duration-300 hover:shadow-md">
        <div class="p-4 rounded-full bg-amber-50 text-amber-900 border border-amber-100">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest font-sans">Ingresos Históricos Válidos</p>
            <p class="text-3xl font-bold text-amber-900 font-serif mt-1">${{ number_format($totalHistoricalRevenue, 2) }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-6 transition-all duration-300 hover:shadow-md">
        <div class="p-4 rounded-full bg-gray-50 text-gray-500 border border-gray-200">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest font-sans">Pedidos Completados</p>
            <p class="text-3xl font-bold text-gray-900 font-serif mt-1">{{ number_format($totalHistoricalOrders) }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="text-xl font-playfair font-semibold text-gray-900 mb-6 border-b border-gray-100 pb-4">Evolución de Ingresos</h3>
    @if(count($chartData['labels']) > 0)
        <div class="relative h-80 w-full">
            <canvas id="{{ $chartId }}"></canvas>
        </div>
    @else
        <div class="flex flex-col items-center justify-center h-64 bg-gray-50/50 rounded-xl border border-dashed border-gray-200">
            <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-400 font-sans tracking-wide">Aún no hay datos de ventas registrados para graficar.</span>
        </div>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-base font-bold font-sans uppercase tracking-widest text-gray-800">Desglose Mensual</h3>
        </div>
        <x-admin.table :headers="['Mes', 'Pedidos', 'Ingresos generados']" :rows="$salesByMonth">
            @forelse($salesByMonth as $data)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 capitalize font-sans">{{ $data['month'] }}</td>
                    <td class="px-6 py-4 text-sm text-center font-medium text-gray-600 font-sans">{{ $data['count'] }}</td>
                    <td class="px-6 py-4 text-sm text-right font-bold text-amber-900 font-sans">${{ number_format($data['revenue'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center bg-white">
                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-400 font-sans">Sin datos registrados.</span>
                    </td>
                </tr>
            @endforelse
        </x-admin.table>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-base font-bold font-sans uppercase tracking-widest text-gray-800">Top 5 Rentables</h3>
            <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-xl">Basado en ingresos</span>
        </div>
        <x-admin.table :headers="['Producto', 'Uds. Vendidas', 'Ingreso Total']" :rows="$topProducts">
            @forelse($topProducts as $top)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-900 font-sans">{{ $top->name }}</div>
                        <div class="text-xs font-mono tracking-widest text-gray-400 mt-1">{{ $top->sku }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-center font-medium text-gray-600 font-sans">{{ $top->total_sold }}</td>
                    <td class="px-6 py-4 text-sm text-right font-bold text-gray-900 font-sans">${{ number_format($top->total_revenue, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center bg-white">
                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-400 font-sans">Aún no hay productos vendidos.</span>
                    </td>
                </tr>
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
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Ingresos Netos ($)',
                            data: data,
                            // Usando el hexadecimal exacto de amber-900 en Tailwind
                            backgroundColor: '#78350f', 
                            hoverBackgroundColor: '#92400e', // amber-800 para el hover
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#111827', // Tooltip oscuro elegante (gray-900)
                                titleFont: { family: 'Inter', size: 13 },
                                bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        let value = context.raw;
                                        return '$' + value.toLocaleString('en-US', {minimumFractionDigits: 2});
                                    }
                                }
                            }
                        },
                        scales: {
                            x: { 
                                grid: { display: false },
                                ticks: { font: { family: 'Inter', size: 12 } }
                            },
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: '#f3f4f6' }, // gray-100
                                ticks: {
                                    font: { family: 'Inter', size: 12 },
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