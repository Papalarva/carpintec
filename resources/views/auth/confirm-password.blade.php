<x-guest-layout>
    <div class="min-h-screen flex w-full overflow-hidden bg-white">
        
        <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900 items-center justify-center p-12">
            <div class="absolute inset-0 bg-cover bg-center opacity-40" 
                 style="background-image: url('https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&q=80&w=1920');">
            </div>
            
            <div class="relative z-10 max-w-lg text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-6 border border-white/20">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <h2 class="font-serif text-5xl text-white tracking-tighter mb-4">Área Segura</h2>
                <div class="h-1 w-16 bg-amber-600 mx-auto mb-6"></div>
                <p class="text-gray-300 font-sans text-lg font-light leading-relaxed">
                    Protegemos tus datos más sensibles. Es necesario validar tu identidad para proceder con esta acción.
                </p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 px-6 py-12 sm:px-12 lg:px-20">
            <div class="w-full max-w-md">
                
                <div class="mb-10 text-center lg:text-left">
                    <h2 class="font-serif text-4xl text-gray-900 tracking-tight mb-3">Confirmar Identidad</h2>
                    <p class="font-sans text-gray-600 text-sm leading-relaxed">
                        Estás a punto de acceder a una sección crítica o realizar una acción sensible. Por favor, ingresa tu contraseña para continuar.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf

                    <div x-data="{ show: false }">
                        <x-input-label for="password" value="Tu Contraseña" class="font-sans text-xs uppercase tracking-widest text-gray-500 mb-2" />
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-amber-800 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <x-text-input id="password" class="block w-full pl-11 pr-12 rounded-xl border-gray-200 py-4 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-all font-sans" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••" autofocus />
                            
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-amber-800 transition-colors">
                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-5 transition-all shadow-md active:scale-[0.98]">
                            Confirmar y Continuar
                        </x-primary-button>
                    </div>

                    <div class="mt-6 text-center">
                        <button type="button" onclick="window.history.back()" class="text-sm text-gray-500 hover:text-amber-900 font-medium underline underline-offset-4 transition-colors focus:outline-none">
                            Cancelar y regresar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-guest-layout>