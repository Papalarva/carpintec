@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Vista General')

@section('content')

    {{-- 👑 1. VISTA DE ADMINISTRADOR (Finanzas y Analítica) --}}
    @if(auth()->user()->hasRole('admin'))
        <div class="mb-6">
            <h2 class="text-xl font-playfair font-semibold text-gray-800">Resumen Financiero</h2>
            <p class="text-sm text-gray-500 font-inter">Métricas clave y estado general del negocio.</p>
        </div>

        {{-- Tarjetas de resumen (Elegancia Semántica) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-admin.stat-card-analytic
                title="Ingresos Totales"
                value="${{ number_format($totalRevenue ?? 0, 2) }}"
                trend="Estable"
                trendType="positive"
                icon="money"
            />
            <x-admin.stat-card-analytic
                title="Órdenes Totales"
                value="{{ $totalOrders ?? 0 }}"
                trend="+5% este mes"
                trendType="positive"
                icon="bag"
            />
            <x-admin.stat-card-analytic
                title="Nuevos Clientes"
                value="{{ $newCustomersThisMonth ?? 0 }}"
                trend="Recientes"
                trendType="neutral"
                icon="users"
            />
            <x-admin.stat-card-analytic
                title="Cotizaciones Pendientes"
                value="{{ $pendingQuotations ?? 0 }}"
                trend="Requieren acción"
                trendType="{{ ($pendingQuotations ?? 0) > 0 ? 'negative' : 'neutral' }}"
                icon="document"
            />
        </div>

        {{-- Gráfico y resumen de alertas --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 font-playfair">Evolución de Ventas</h3>
                @if(!empty($salesChartConfig))
                    <!-- Tu componente de gráfica intacto -->
                    <x-admin.chart :config="$salesChartConfig" currencyY />
                @else
                    <div class="flex items-center justify-center h-48 bg-gray-50 rounded border border-dashed border-gray-200">
                        <span class="text-sm text-gray-400">Sin datos suficientes para graficar.</span>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 font-playfair">Alertas de Sistema</h3>
                <ul class="space-y-4">
                    <li class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-100">
                        <span class="text-sm font-medium text-red-800">Productos con bajo stock</span>
                        <span class="font-bold text-red-600 bg-red-200 px-3 py-1 rounded-full text-xs">{{ $lowStockProducts ?? 0 }}</span>
                    </li>
                    <li class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                        <span class="text-sm font-medium text-yellow-800">Cotizaciones en espera</span>
                        <span class="font-bold text-yellow-600 bg-yellow-200 px-3 py-1 rounded-full text-xs">{{ $pendingQuotations ?? 0 }}</span>
                    </li>
                </ul>
            </div>
        </div>
    @endif

    {{-- 👷‍♂️ 2. VISTA DE WORKER (Centro de Tareas) --}}
    @if(auth()->user()->hasRole('worker'))
        <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-2xl font-playfair font-semibold text-gray-900">
                Hola {{ auth()->user()->first_name }}, este es tu resumen de hoy.
            </h2>
            <p class="text-gray-500 mt-1 font-inter">Tienes algunas tareas operativas que requieren tu atención inmediata.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Tarea 1: Cotizaciones --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start space-x-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900 font-inter">Cotizaciones Pendientes</h3>
                    <p class="text-sm text-gray-500 mb-4 font-inter">Hay <strong class="text-amber-600">{{ $pendingQuotations ?? 0 }}</strong> cotizaciones esperando respuesta de los clientes.</p>
                    <a href="{{ route('admin.quotations.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 transition ease-in-out duration-150">
                        Atender ahora
                    </a>
                </div>
            </div>

            {{-- Tarea 2: Pedidos --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start space-x-4">
                <div class="p-3 bg-[#C15C3D]/10 text-[#C15C3D] rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900 font-inter">Pedidos por Preparar</h3>
                    <p class="text-sm text-gray-500 mb-4 font-inter">Revisa los últimos pedidos aprobados que deben enviarse a taller o empaque.</p>
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#C15C3D] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#a34b30] transition ease-in-out duration-150">
                        Ver pedidos
                    </a>
                </div>
            </div>

            {{-- Tarea 3: Inventario --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start space-x-4 lg:col-span-2 xl:col-span-1">
                <div class="p-3 bg-red-50 text-red-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-medium text-gray-900 font-inter">Alerta de Stock</h3>
                    <p class="text-sm text-gray-500 mb-4 font-inter">Existen <strong class="text-red-600">{{ $lowStockProducts ?? 0 }}</strong> productos con stock crítico en la bodega.</p>
                    <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-red-200 rounded-md font-semibold text-xs text-red-600 uppercase tracking-widest hover:bg-red-50 transition ease-in-out duration-150">
                        Revisar inventario
                    </a>
                </div>
            </div>
        </div>
    @endif

@endsection