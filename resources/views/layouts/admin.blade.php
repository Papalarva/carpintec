<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Carpintec Premium</title>

    <!-- 🎨 Tipografías del Sistema de Diseño -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Estilos base para tipografías y scroll oculto -->
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-inter { font-family: 'Inter', sans-serif; }
        /* Oculta el scrollbar en la navegación horizontal pero permite deslizar en móviles */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gray-50 font-inter antialiased text-gray-800 flex flex-col min-h-screen">
    
    <!-- 🥇 BARRA NIVEL 1: Identidad y Perfil -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <!-- Logo Carpintec -->
                <div class="flex-shrink-0 flex items-center">
                    <span class="font-playfair text-2xl font-semibold tracking-wide text-gray-900">
                        Carpintec<span class="text-[#C15C3D]">.</span>
                    </span>
                </div>

                <!-- Perfil y Salir -->
                <div class="flex items-center gap-5">
                    <div class="flex flex-col text-right hidden sm:block">
                        <span class="text-sm font-semibold text-gray-900">{{ auth()->user()->full_name }}</span>
                        <!-- Muestra el rol principal con la primera letra mayúscula -->
                        <span class="text-xs text-gray-500 capitalize">{{ auth()->user()->roles->first()?->name ?? 'Usuario' }}</span>
                    </div>
                    <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm font-medium text-gray-500 hover:text-[#C15C3D] transition-colors duration-200">
                            Salir
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </header>

    <!-- 🥈 BARRA NIVEL 2: Navegación de Módulos (Acción) -->
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-10">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8 overflow-x-auto no-scrollbar">
                
                <x-admin.sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                    Dashboard
                </x-admin.sidebar-link>

                @role(['admin', 'worker'])
                    <x-admin.sidebar-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')">
                        Categorías
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link href="{{ route('admin.products.index') }}" :active="request()->routeIs('admin.products.*')">
                        Productos
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link href="{{ route('admin.quotations.index') }}" :active="request()->routeIs('admin.quotations.*')">
                        Cotizaciones
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')">
                        Pedidos
                    </x-admin.sidebar-link>
                @endrole

                @role('admin')
                    <x-admin.sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                        Usuarios
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link href="{{ route('admin.discounts.index') }}" :active="request()->routeIs('admin.discounts.*')">
                        Descuentos
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link href="{{ route('admin.inventory.index') }}" :active="request()->routeIs('admin.inventory.*')">
                        Inventario
                    </x-admin.sidebar-link> 
                    <x-admin.sidebar-link href="{{ route('admin.coupons.index') }}" :active="request()->routeIs('admin.coupons.*')">
                        Cupones
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link href="{{ route('admin.reports.index') }}" :active="request()->routeIs('admin.reports.*')">
                        Reportes
                    </x-admin.sidebar-link>
                @endrole

            </div>
        </div>
    </nav>

    <!-- 🖥️ CONTENIDO PRINCIPAL -->
    <main class="flex-1 max-w-screen-2xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Cabecera de la Vista -->
        <div class="mb-6">
            <h1 class="text-2xl font-playfair font-semibold text-gray-900">
                @yield('header', 'Panel de Administración')
            </h1>
        </div>

        <!-- Área de Trabajo (Tarjeta Blanca) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                @yield('content')
            </div>
        </div>

    </main>
    @if (session('success') || session('error'))
        <div id="toast-notification" class="fixed top-20 right-6 z-50 transform transition-all duration-300 translate-y-0 opacity-100 flex items-center w-full max-w-xs p-4 space-x-3 bg-white rounded-lg shadow-lg border border-gray-100" role="alert">
            
            @if(session('success'))
                <!-- Ícono de Éxito -->
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-500 bg-emerald-100 rounded-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="ml-3 text-sm font-medium text-gray-700">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <!-- Ícono de Error -->
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-rose-500 bg-rose-100 rounded-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="ml-3 text-sm font-medium text-gray-700">{{ session('error') }}</div>
            @endif
            
            <button type="button" onclick="document.getElementById('toast-notification').remove()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8">
                <span class="sr-only">Cerrar</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>

        <!-- Script para auto-destruir el toast después de 4 segundos -->
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-notification');
                if(toast) {
                    toast.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 4000);
        </script>
    @endif
</body>
</html>