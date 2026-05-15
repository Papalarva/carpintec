<x-guest-layout>
    <div class="min-h-screen flex w-full">
        
        <div class="hidden lg:flex w-1/2 bg-gray-900 relative items-center justify-center">
            <div class="absolute inset-0 bg-cover bg-center opacity-40" style="background-image: url('https://images.unsplash.com/photo-1618220179428-22790b461013?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');"></div>
            <div class="relative z-10 text-center px-12">
                <h1 class="text-white font-serif text-5xl tracking-tight mb-4">Carpintec</h1>
                <p class="text-gray-200 font-sans text-lg font-light tracking-wide">La esencia de la madera, a tu medida.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 px-8 py-16 sm:px-12 lg:px-24">
            <div class="w-full max-w-md">
                
                <div class="mb-10 text-center lg:text-left">
                    <h2 class="font-serif text-4xl text-gray-900 tracking-tight mb-2">Bienvenido</h2>
                    <p class="font-sans text-gray-500 text-sm">Nos alegra verte de nuevo. Ingresa tus credenciales para acceder a tu espacio.</p>
                </div>

                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="email" value="Correo Electrónico" class="font-sans" />
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <x-text-input id="email" class="block w-full pl-10 rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm font-sans" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu@correo.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label for="password" value="Contraseña" class="font-sans" />
                            @if (Route::has('password.request'))
                                <a tabindex="-1" class="font-sans text-sm text-amber-900 hover:text-amber-800 font-medium transition-colors" href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>
                        <div class="relative mt-1" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            
                            <x-text-input id="password" class="block w-full pl-10 pr-10 rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm font-sans" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••" />
                            
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-amber-800 focus:outline-none transition-colors" tabindex="-1">
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="block pt-2">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-amber-900 shadow-sm focus:ring-amber-800" name="remember">
                            <span class="ms-2 font-sans text-sm text-gray-600">Mantener sesión iniciada</span>
                        </label>
                    </div>

                    <div class="pt-4">
                        <x-primary-button class="w-full justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors shadow-sm">
                            Iniciar Sesión
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>