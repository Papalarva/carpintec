@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Vista General')

@section('content')

    {{-- Inicializamos Alpine para las animaciones de entrada en cascada --}}
    <div x-data="{ showCards: false, showCharts: false }" 
         x-init="setTimeout(() => showCards = true, 100); setTimeout(() => showCharts = true, 300)">

        {{-- 👑 1. VISTA DE ADMINISTRADOR (Finanzas y Analítica) --}}
        @if(auth()->user()->hasRole('admin'))
            <div class="mb-8" x-show="showCards" x-transition.opacity.duration.700ms x-cloak>
                <h2 class="text-2xl font-serif font-semibold text-gray-900 tracking-tight">Resumen Financiero</h2>
                <p class="text-sm text-gray-500 font-sans mt-1">Métricas clave y estado general del negocio en tiempo real.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" x-show="showCards" x-transition.opacity.duration.700ms x-cloak>
                <x-admin.stat-card-analytic title="Ingresos Totales" value="${{ number_format($totalRevenue ?? 0, 2) }}" trend="Estable" trendType="positive" icon="money" />
                <x-admin.stat-card-analytic title="Órdenes Totales" value="{{ $totalOrders ?? 0 }}" trend="+5% este mes" trendType="positive" icon="bag" />
                <x-admin.stat-card-analytic title="Nuevos Clientes" value="{{ $newCustomersThisMonth ?? 0 }}" trend="Recientes" trendType="neutral" icon="users" />
                <x-admin.stat-card-analytic title="Cotizaciones" value="{{ $pendingQuotations ?? 0 }}" trend="Requieren acción" trendType="{{ ($pendingQuotations ?? 0) > 0 ? 'negative' : 'neutral' }}" icon="document" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" x-show="showCharts" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                
                {{-- Gráfica Principal --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 font-serif">Evolución de Ventas</h3>
                    @if(!empty($salesChartConfig))
                        <x-admin.chart :config="$salesChartConfig" currencyY />
                    @else
                        <div class="flex items-center justify-center h-64 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <span class="text-sm text-gray-400 font-sans">Sin datos suficientes para graficar.</span>
                        </div>
                    @endif
                </div>

                {{-- Top Muebles y Embudo --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 font-serif">Top Muebles</h3>
                        <ul class="space-y-4">
                            @foreach($topProducts as $product)
                                <li class="flex items-center justify-between border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900 font-sans">{{ $product->name }}</span>
                                        <span class="text-xs text-gray-500 font-sans">{{ $product->sales }} unidades</span>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900 font-serif">${{ number_format($product->revenue) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- 👷‍♂️ 2. VISTA DE WORKER (Centro de Tareas Diario) --}}
        @if(auth()->user()->hasRole('worker'))
            <div class="mb-8" x-show="showCards" x-transition.opacity.duration.700ms x-cloak>
                <h2 class="text-3xl font-serif font-semibold text-gray-900">
                    Hola {{ auth()->user()->first_name }}, tu plan de hoy.
                </h2>
                
                {{-- Barra de Progreso Operativo --}}
                <div class="mt-6 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between text-sm font-sans mb-2">
                        <span class="text-gray-500">Progreso de envíos diarios</span>
                        <span class="font-bold text-amber-900">{{ $completedToday }} de {{ $dailyGoal }} pedidos</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="bg-amber-900 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" 
                 x-show="showCharts" 
                 x-transition:enter="transition ease-out duration-700" 
                 x-transition:enter-start="opacity-0 translate-y-4" 
                 x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                
                <x-admin.task-card 
                    title="Cotizaciones" 
                    count="{{ $pendingQuotations ?? 0 }}" 
                    description="esperando respuesta de clientes." 
                    route="{{ route('admin.quotations.index') }}" 
                    buttonText="Atender ahora" 
                    icon="clipboard" 
                    type="amber" 
                />

                <x-admin.task-card 
                    title="Pedidos por Preparar" 
                    count="{{ $pendingOrders ?? 0 }}" 
                    description="órdenes aprobadas para empaque." 
                    route="{{ route('admin.orders.index') }}" 
                    buttonText="Ver pedidos" 
                    icon="box" 
                    type="amber" 
                />

                <x-admin.task-card 
                    title="Alerta de Stock" 
                    count="{{ $lowStockProducts ?? 0 }}" 
                    description="productos en nivel crítico." 
                    route="{{ route('admin.inventory.index') }}" 
                    buttonText="Revisar inventario" 
                    icon="warning" 
                    type="red" 
                />
            </div>
        @endif

    </div>
@endsection