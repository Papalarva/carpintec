<x-guest-layout>
    <div class="min-h-screen flex w-full">
        
        {{-- Banner Lateral (Visual) --}}
        <div class="hidden lg:flex w-1/2 bg-gray-900 relative items-center justify-center">
            <div class="absolute inset-0 bg-cover bg-center opacity-40" style="background-image: url({{ Vite::asset('resources/images/auth/login.jpeg') }});"></div>
            <div class="relative z-10 text-center px-12">
                <h1 class="text-white font-serif text-5xl tracking-tight mb-4">Carpintec</h1>
                <p class="text-gray-200 font-sans text-lg font-light tracking-wide">La esencia de la madera, a tu medida.</p>
            </div>
        </div>

        {{-- Formulario de Login --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 px-8 py-16 sm:px-12 lg:px-24">
            <div class="w-full max-w-md" x-data="loginForm()" x-init="setupValidators($refs.form)">
                
                <div class="mb-10 text-center lg:text-left">
                    <h2 class="font-serif text-4xl text-gray-900 tracking-tight mb-2">Bienvenido</h2>
                    <p class="font-sans text-gray-500 text-sm">Nos alegra verte de nuevo. Ingresa tus credenciales para acceder a tu espacio.</p>
                </div>

                {{-- Status de Sesión de Breeze (Ej. Mensaje de reset password) --}}
                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form x-ref="form" @submit="attemptSubmit($event)" method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Correo Electrónico --}}
                    <div>
                        <label for="email" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 font-sans">Correo Electrónico</label>
                        <div class="relative mt-1 group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-amber-900 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="tu@correo.com"
                                   class="block w-full pl-11 rounded-xl border-gray-200 bg-gray-50/50 py-3.5 focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-all font-sans text-sm">
                        </div>
                        @error('email') <p class="text-[11px] font-bold tracking-wide text-rose-600 mt-2 font-sans">{{ $message }}</p> @enderror
                    </div>

                    {{-- Contraseña --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest font-sans">Contraseña</label>
                            @if (Route::has('password.request'))
                                <a tabindex="-1" class="font-sans text-xs text-amber-900 hover:text-amber-700 font-bold transition-colors" href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-amber-900 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            
                            <input id="password" x-bind:type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••"
                                   class="block w-full pl-11 pr-12 rounded-xl border-gray-200 bg-gray-50/50 py-3.5 focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-all font-sans text-sm">
                            
                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-amber-900 transition-colors focus:outline-none" tabindex="-1">
                                <svg x-show="!showPass" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-cloak x-show="showPass" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password') <p class="text-[11px] font-bold tracking-wide text-rose-600 mt-2 font-sans">{{ $message }}</p> @enderror
                    </div>

                    {{-- Mantener sesión --}}
                    <div class="block pt-2">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-900 bg-gray-50 transition-colors cursor-pointer shadow-sm">
                            <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-gray-900 font-sans transition-colors">Mantener sesión iniciada</span>
                        </label>
                    </div>

                    {{-- Botón de Enviar --}}
                    <div class="pt-4">
                        <button type="submit" :disabled="isSubmitting"
                                class="w-full inline-flex justify-center items-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-8 py-4 transition-all shadow-md focus:outline-none disabled:opacity-75 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">Iniciar Sesión</span>
                            <span x-show="isSubmitting" x-cloak class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Ingresando...
                            </span>
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center font-sans text-sm text-gray-500">
                    ¿Aún no tienes cuenta en Carpintec? 
                    <a href="{{ route('register') }}" class="text-amber-900 font-bold hover:text-amber-700 underline underline-offset-4 transition-colors">
                        Regístrate aquí
                    </a>
                </p>

            </div>
        </div>
    </div>

    {{-- EL CEREBRO DE ALPINE PARA LOGIN --}}
    <script>
        function loginForm() {
            return {
                showPass: false,
                isSubmitting: false,

                // Forzar mensajes de validación nativos al español
                setupValidators(form) {
                    if (!form) return;
                    form.querySelectorAll('input').forEach(el => {
                        el.oninvalid = e => {
                            e.target.setCustomValidity('');
                            if (!e.target.validity.valid) {
                                if (e.target.validity.valueMissing) e.target.setCustomValidity('Este campo es obligatorio.');
                                else if (e.target.validity.typeMismatch) e.target.setCustomValidity('El formato no es válido.');
                                else e.target.setCustomValidity('Valor inválido.');
                            }
                        };
                        el.oninput = e => {
                            e.target.setCustomValidity('');
                        };
                    });
                },

                attemptSubmit(event) {
                    if(this.$refs.form.checkValidity()) {
                        this.isSubmitting = true;
                        // Permitimos que el formulario se envíe nativamente, pero con estado de carga activo
                    } 
                }
            }
        }
    </script>
</x-guest-layout>