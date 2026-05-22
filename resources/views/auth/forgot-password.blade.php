<x-guest-layout>
    <div class="min-h-screen flex w-full overflow-hidden bg-white">
        
        <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900 items-center justify-center p-12">
            <div class="absolute inset-0 bg-cover bg-center opacity-50" 
                 style="background-image: url({{ Vite::asset('resources/images/auth/login.jpeg') }})">
            </div>
            
            <div class="relative z-10 max-w-lg text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-6 border border-white/20">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                </div>
                <h2 class="font-serif text-5xl text-white tracking-tighter mb-4">Recuperar Acceso</h2>
                <div class="h-1 w-16 bg-amber-600 mx-auto mb-6"></div>
                <p class="text-gray-300 font-sans text-lg font-light leading-relaxed">
                    Te ayudaremos a recuperar tu cuenta para que puedas continuar creando espacios excepcionales.
                </p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 px-6 py-12 sm:px-12 lg:px-20">
            <div class="w-full max-w-md">
                
                <div class="mb-10 text-center lg:text-left">
                    <h2 class="font-serif text-4xl text-gray-900 tracking-tight mb-3">¿Olvidaste tu contraseña?</h2>
                    <p class="font-sans text-gray-500 text-sm leading-relaxed">
                        No hay problema. Ingresa el correo electrónico asociado a tu cuenta y te enviaremos un enlace seguro para restablecerla.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="email" value="Correo Electrónico" class="font-sans text-xs uppercase tracking-widest text-gray-500 mb-2" />
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-amber-800 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <x-text-input id="email" class="block w-full pl-11 rounded-xl border-gray-200 py-4 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-all font-sans" type="email" name="email" :value="old('email')" required autofocus placeholder="tu@correo.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-5 transition-all shadow-md active:scale-[0.98]">
                            Enviar enlace de recuperación
                        </x-primary-button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <a href="{{ route('login') }}" class="text-gray-500 font-sans text-sm hover:text-amber-800 transition-colors inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Cancelar y volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>