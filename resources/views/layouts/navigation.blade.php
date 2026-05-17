<nav x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
    :class="{ 'bg-white shadow-premium border-gray-100': scrolled, 'bg-transparent border-transparent': !scrolled }"
    class="fixed w-full z-50 top-0 transition-all duration-500 border-b font-sans flex flex-col">

    @if (Auth::check() && !Auth::user()->hasVerifiedEmail())
        <div class="w-full bg-brand-light border-b border-brand-light px-4 py-2.5 flex justify-center items-center text-center shadow-sm">
            <svg class="h-5 w-5 text-brand mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
            <span class="text-sm font-sans text-brand">
                Tu cuenta casi está lista. Verifica tu correo para solicitar cotizaciones y realizar compras.
                <a href="{{ route('verification.notice') }}" class="font-bold underline underline-offset-2 ml-1 hover:text-brand-hover transition-colors">Verificar ahora</a>
            </span>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex justify-between h-20 items-center">

            <div class="hidden sm:flex flex-1 items-center space-x-8">
                <a href="{{ route('catalog.index') }}"
                    class="text-sm font-medium tracking-wide text-gray-900 hover:text-brand transition-colors">
                    Catálogo
                </a>
                <a href="{{ route('collections.index') }}"
                    class="text-sm font-medium tracking-wide text-gray-900 hover:text-brand transition-colors">
                    Colecciones
                </a>
            </div>

            <div class="flex-shrink-0 flex items-center justify-center flex-1">
                <a href="{{ route('home') }}"
                    class="font-serif text-3xl font-bold tracking-tight text-gray-900 hover:text-brand transition-colors">
                    CARPINTEC.
                </a>
            </div>

            <div class="hidden sm:flex flex-1 items-center justify-end space-x-6">

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-gray-900 hover:text-brand transition-colors focus:outline-none">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                                <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Bienvenido</p>
                                <p class="text-sm font-bold text-gray-900 truncate">
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                </p>
                            </div>

                            <div class="py-1">
                                <x-dropdown-link :href="route('profile.edit')" class="hover:bg-gray-50 hover:text-brand">
                                    Mi Perfil
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('addresses.index')" class="hover:bg-gray-50 hover:text-brand">
                                    Mis Direcciones
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('quotations.index')" class="hover:bg-gray-50 hover:text-brand">
                                    Mis Cotizaciones
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('orders.index')" class="hover:bg-gray-50 hover:text-brand">
                                    Mis Compras
                                </x-dropdown-link>
                            </div>

                            <div class="border-t border-gray-100 py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-red-600 hover:bg-red-50 hover:text-red-700">
                                        Cerrar sesión
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}"
                            class="text-sm font-medium tracking-wide text-gray-900 hover:text-brand transition-colors">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}"
                           class="text-sm font-medium tracking-wide text-gray-900 hover:text-brand transition-colors">
                            Crear cuenta
                        </a>
                    </div>
                @endauth

                {{-- SOLUCIÓN: Reactividad global limpia delegada al DOM --}}
                <div x-data="{ count: 0, fetchCount() { fetch('{{ route('cart.count') }}').then(r => r.json()).then(data => this.count = data.count).catch(() => this.count = 0); } }"
                     x-init="fetchCount()"
                     @cart-updated.window="count = $event.detail.count" 
                     class="relative flex items-center">
                    
                    <a href="{{ route('cart.index') }}"
                        class="text-gray-900 hover:text-brand transition-colors group">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <span x-show="count > 0" x-text="count" x-cloak
                            class="absolute -top-1.5 -right-2 flex h-4 w-4 items-center justify-center rounded-full bg-brand text-[10px] font-bold text-white shadow-sm"></span>
                    </a>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-900 hover:text-brand transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="sm:hidden bg-white border-t border-gray-100 shadow-premium w-full" style="display: none;">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('catalog.index')"
                class="hover:text-brand hover:bg-gray-50">Catálogo</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('collections.index')"
                class="hover:text-brand hover:bg-gray-50">Colecciones</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')"
                class="hover:text-brand hover:bg-gray-50">Carrito</x-responsive-nav-link>
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-100">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-900">{{ Auth::user()->first_name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="hover:text-brand hover:bg-gray-50">
                        Mi Perfil
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('addresses.index')" class="hover:text-brand hover:bg-gray-50">
                        Mis Direcciones
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('quotations.index')" class="hover:text-brand hover:bg-gray-50">
                        Mis Cotizaciones
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('orders.index')" class="hover:text-brand hover:bg-gray-50">
                        Mis Compras
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-600 hover:bg-red-50 hover:text-red-700">
                            Cerrar sesión
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-100">
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')" class="hover:text-brand hover:bg-gray-50">
                        Iniciar sesión
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')" class="hover:text-brand hover:bg-gray-50">
                        Crear cuenta
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>