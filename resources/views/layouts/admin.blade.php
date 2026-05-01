<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Muebles Premium</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
        <div class="p-4 text-xl font-bold border-b border-gray-700">
            Muebles Premium
        </div>
        <nav class="mt-4">
            <x-admin.sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                Dashboard
            </x-admin.sidebar-link>

            @role(['admin','worker'])
            <x-admin.sidebar-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')">Categorías</x-admin.sidebar-link>
            <x-admin.sidebar-link href="{{ route('admin.products.index') }}" :active="request()->routeIs('admin.products.*')">Productos</x-admin.sidebar-link>
            <x-admin.sidebar-link href="#" :active="false">Pedidos</x-admin.sidebar-link>
            <x-admin.sidebar-link href="#" :active="false">Cotizaciones</x-admin.sidebar-link>
            @endrole

            @role('admin')
            <x-admin.sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">Usuarios</x-admin.sidebar-link>
            <x-admin.sidebar-link href="#" :active="false">Descuentos</x-admin.sidebar-link>
            <x-admin.sidebar-link href="#" :active="false">Inventario</x-admin.sidebar-link>
            <x-admin.sidebar-link href="#" :active="false">Reportes</x-admin.sidebar-link>
            @endrole
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="flex-1 flex flex-col">
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6">
            <h1 class="text-lg font-semibold text-gray-800">@yield('header', 'Panel de Administración')</h1>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">{{ auth()->user()->full_name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-600 hover:underline">Salir</button>
                </form>
            </div>
        </header>
        <div class="p-6 flex-1">
            @yield('content')
        </div>
    </main>
</div>
@stack('scripts')
</body>
</html>