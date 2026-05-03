<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'bg-white shadow-sm border-gray-100': scrolled, 'bg-transparent border-transparent': !scrolled }"
     class="fixed w-full z-50 top-0 transition-all duration-500 border-b">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center"> <!-- h-20 da más aire que el h-16 original -->
            
            <!-- Izquierda: Enlaces principales -->
            <div class="hidden sm:flex flex-1 items-center space-x-8">
                <a href="{{ route('catalog.index') }}" class="text-sm font-medium tracking-wide text-gray-900 hover:text-amber-700 transition-colors">
                    Catálogo
                </a>
                <a href="#" class="text-sm font-medium tracking-wide text-gray-900 hover:text-amber-700 transition-colors">
                    Colecciones
                </a>
            </div>

            <!-- Centro: Logo (Tipografía Serif) -->
            <div class="flex-shrink-0 flex items-center justify-center flex-1">
                <a href="{{ route('dashboard') }}" class="font-serif text-3xl font-bold tracking-tight text-gray-900">
                    CARPINTEC.
                </a>
            </div>

            <!-- Derecha: Iconos (Usuario y Carrito con línea fina 1.5px) -->
            <div class="hidden sm:flex flex-1 items-center justify-end space-x-6">
                
                @auth
                    <!-- Dropdown Usuario -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-900 hover:text-amber-700 transition-colors focus:outline-none">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs text-gray-500 uppercase tracking-widest">Bienvenido</p>
                                <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <x-dropdown-link :href="route('dashboard')">Mi Cuenta</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600">
                                    Cerrar sesión
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-900 hover:text-amber-700 transition-colors">Iniciar sesión</a>
                @endauth

                <!-- Carrito -->
                <div x-data="{ count: 0, init() { this.fetchCount(); window.addEventListener('cart-updated', e => { this.count = e.detail.count; }); }, fetchCount() { fetch('{{ route('cart.count') }}').then(r => r.json()).then(data => this.count = data.count); } }"
                     class="relative flex items-center">
                    <a href="{{ route('cart.index') }}" class="text-gray-900 hover:text-amber-700 transition-colors group">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <!-- Badge Minimalista -->
                        <span x-show="count > 0" x-text="count" x-cloak class="absolute -top-1.5 -right-2 flex h-4 w-4 items-center justify-center rounded-full bg-amber-800 text-[10px] font-bold text-white shadow-sm"></span>
                    </a>
                </div>
            </div>

            <!-- Hamburger (Móvil) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-900 hover:text-amber-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú Responsive (Móvil) -->
    <div x-show="open" x-transition class="sm:hidden bg-white border-t border-gray-100 shadow-xl absolute w-full">
        <!-- Contenido móvil... (Mantuve tus rutas intactas) -->
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('catalog.index')">Catálogo</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')">Carrito</x-responsive-nav-link>
        </div>
    </div>
</nav>