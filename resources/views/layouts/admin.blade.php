<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Carpintec</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-inter { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }        
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 font-inter antialiased text-gray-800 flex flex-col min-h-screen">
    
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <div class="flex-shrink-0 flex items-center">
                    <span class="font-playfair text-2xl font-semibold tracking-wide text-gray-900">
                        Carpintec<span class="text-amber-900">.</span>
                    </span>
                </div>

                <div class="flex items-center gap-5">
                    <div class="flex flex-col text-right hidden sm:block">
                        <span class="text-sm font-semibold text-gray-900">{{ auth()->user()->full_name }}</span>
                        <span class="text-xs text-gray-500 capitalize">{{ auth()->user()->roles->first()?->name ?? 'Usuario' }}</span>
                    </div>
                    <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm font-medium text-gray-500 hover:text-amber-900 transition-colors duration-200">
                            Salir
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </header>

    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-10">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex sm:justify-center space-x-8 overflow-x-auto no-scrollbar">
                
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
                    <x-admin.sidebar-link href="{{ route('admin.inventory.index') }}" :active="request()->routeIs('admin.inventory.*')">
                        Inventario
                    </x-admin.sidebar-link> 
                    <x-admin.sidebar-link href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')">
                        Pedidos
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link href="{{ route('admin.collections.index') }}" :active="request()->routeIs('admin.collections.*')">
                        Colecciones
                    </x-admin.sidebar-link>
                @endrole

                @role('admin')
                    <x-admin.sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                        Usuarios
                    </x-admin.sidebar-link>
                    <x-admin.sidebar-link href="{{ route('admin.discounts.index') }}" :active="request()->routeIs('admin.discounts.*')">
                        Descuentos
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

    <main class="flex-1 max-w-screen-2xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-6">
            <h1 class="text-2xl font-playfair font-semibold text-gray-900">
                @yield('header', 'Panel de Administración')
            </h1>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                @yield('content')
            </div>
        </div>

    </main>

    @if (session('success') || session('error'))
        <div id="toast-notification" class="fixed top-24 right-6 z-50 transform transition-all duration-500 translate-y-0 opacity-100 flex items-center w-full max-w-sm p-4 space-x-4 bg-white rounded-xl shadow-lg border border-gray-100" role="alert">
            
            @if(session('success'))
                <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-emerald-600 bg-emerald-50 rounded-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 text-sm font-medium text-gray-700">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-rose-600 bg-rose-50 rounded-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3 text-sm font-medium text-gray-700">{{ session('error') }}</div>
            @endif
            
            <button type="button" onclick="document.getElementById('toast-notification').remove()" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-200 p-1.5 hover:bg-gray-50 inline-flex h-8 w-8 items-center justify-center transition-colors">
                <span class="sr-only">Cerrar</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-notification');
                if(toast) {
                    toast.classList.add('opacity-0', '-translate-y-2');
                    setTimeout(() => toast.remove(), 500); // 500ms match duration-500
                }
            }, 4000);
        </script>
    @endif

    @stack('scripts')
</body>
</html>