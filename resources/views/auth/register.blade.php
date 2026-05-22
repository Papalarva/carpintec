<x-guest-layout>
    <div class="min-h-screen flex w-full overflow-hidden bg-white">
        
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 px-6 py-12 sm:px-12 lg:px-20 overflow-y-auto">
            <div class="w-full max-w-md my-auto" x-data="registerForm()">
                
                <div class="mb-10 text-center lg:text-left">
                    <h2 class="font-serif text-4xl text-gray-900 tracking-tight mb-3">Crear Cuenta</h2>
                    <p class="font-sans text-gray-500 text-sm leading-relaxed">
                        Únete a Carpintec. Diseña tus espacios y gestiona tus proyectos a medida con nosotros.
                    </p>
                </div>

                <form x-ref="form" method="POST" action="{{ route('register') }}" class="space-y-6" @submit="attemptSubmit($event)">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-6">
                        {{-- Nombre --}}
                        <div>
                            <label for="first_name" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nombre(s)</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-amber-900 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus maxlength="255"
                                       class="block w-full pl-11 rounded-xl border-gray-200 bg-gray-50/50 py-3.5 focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-all font-sans text-sm">
                            </div>
                            @error('first_name') <p class="text-[11px] font-bold tracking-wide text-rose-600 mt-2">{{ $message }}</p> @enderror
                        </div>

                        {{-- Apellidos --}}
                        <div>
                            <label for="last_name" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Apellidos</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-amber-900 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required maxlength="255"
                                       class="block w-full pl-11 rounded-xl border-gray-200 bg-gray-50/50 py-3.5 focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-all font-sans text-sm">
                            </div>
                            @error('last_name') <p class="text-[11px] font-bold tracking-wide text-rose-600 mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Correo Electrónico --}}
                    <div>
                        <label for="email" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Correo Electrónico</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-amber-900 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="255" placeholder="tu@correo.com"
                                   class="block w-full pl-11 rounded-xl border-gray-200 bg-gray-50/50 py-3.5 focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-all font-sans text-sm">
                        </div>
                        @error('email') <p class="text-[11px] font-bold tracking-wide text-rose-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Contraseña --}}
                    <div>
                        <label for="password" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Contraseña</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-amber-900 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <input id="password" x-bind:type="showPass ? 'text' : 'password'" name="password" required placeholder="••••••••" minlength="8"
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
                        @error('password') <p class="text-[11px] font-bold tracking-wide text-rose-600 mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirmar Contraseña --}}
                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Confirmar Contraseña</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-amber-900 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <input id="password_confirmation" x-bind:type="showConf ? 'text' : 'password'" name="password_confirmation" required placeholder="••••••••" minlength="8"
                                   class="block w-full pl-11 pr-12 rounded-xl border-gray-200 bg-gray-50/50 py-3.5 focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-all font-sans text-sm"
                                   @input="checkPasswordMatch()">
                            
                            <button type="button" @click="showConf = !showConf" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-amber-900 transition-colors focus:outline-none" tabindex="-1">
                                <svg x-show="!showConf" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-cloak x-show="showConf" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Checkbox Newsletter --}}
                    <div class="flex items-start pt-2">
                        <div class="flex items-center h-5">
                            <input id="accepts_marketing" name="accepts_marketing" type="checkbox" value="1" class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-900 bg-gray-50 transition-colors cursor-pointer shadow-sm" {{ old('accepts_marketing') ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="accepts_marketing" class="font-sans font-bold text-gray-700 cursor-pointer">Suscribirme al Newsletter</label>
                            <p class="text-gray-500 font-sans text-xs mt-0.5 font-medium">Deseo recibir correos con nuevas colecciones, promociones exclusivas e inspiración de Carpintec.</p>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" :disabled="isSubmitting"
                                class="w-full inline-flex justify-center items-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-8 py-4 transition-all shadow-md focus:outline-none disabled:opacity-75 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">Completar Registro</span>
                            <span x-show="isSubmitting" x-cloak class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Creando cuenta...
                            </span>
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center font-sans text-sm text-gray-500">
                    ¿Ya eres parte de Carpintec? 
                    <a href="{{ route('login') }}" class="text-amber-900 font-bold hover:text-amber-700 underline underline-offset-4 transition-colors">
                        Inicia sesión
                    </a>
                </p>
            </div>
        </div>

        <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900 items-center justify-center p-12">
            <div class="absolute inset-0 bg-cover bg-center opacity-40" 
                 style="background-image: url({{ Vite::asset('resources/images/auth/login.jpeg') }});">
            </div>
            
            <div class="relative z-10 max-w-lg text-center">
                <h2 class="font-serif text-5xl text-white tracking-tighter mb-4">Artesanía Pura</h2>
                <div class="h-1 w-16 bg-amber-600 mx-auto mb-6"></div>
                <p class="text-gray-300 font-sans text-lg font-light leading-relaxed">
                    Cada veta cuenta una historia. Registra tu cuenta y comienza a dar forma a tus espacios con madera de la más alta calidad.
                </p>
            </div>
        </div>
        
    </div>

    {{-- CEREBRO DE ALPINE PARA REGISTRO --}}
    <script>
        function registerForm() {
            return {
                isSubmitting: false,
                showPass: false,
                showConf: false,

                init() {
                    this.$nextTick(() => {
                        this.setupValidators(this.$refs.form);
                    });
                },

                setupValidators(form) {
                    if (!form) return;
                    form.querySelectorAll('input').forEach(el => {
                        el.oninvalid = e => {
                            e.target.setCustomValidity('');
                            if (!e.target.validity.valid) {
                                if (e.target.validity.valueMissing) {
                                    e.target.setCustomValidity('Este campo es obligatorio.');
                                } else if (e.target.validity.typeMismatch) {
                                    e.target.setCustomValidity('El formato no es válido.');
                                } else if (e.target.validity.tooShort) {
                                    e.target.setCustomValidity(`Debe tener al menos ${e.target.minLength} caracteres.`);
                                } else {
                                    e.target.setCustomValidity('Valor inválido.');
                                }
                            }
                        };
                        el.oninput = e => {
                            e.target.setCustomValidity('');
                        };
                    });
                },

                // Verifica la contraseña mientras escribe
                checkPasswordMatch() {
                    const form = this.$refs.form;
                    const pass = form.querySelector('#password').value;
                    const conf = form.querySelector('#password_confirmation');
                    
                    if (conf.value !== '' && pass !== conf.value) {
                        conf.setCustomValidity('Las contraseñas no coinciden.');
                    } else {
                        conf.setCustomValidity('');
                    }
                },

                attemptSubmit(event) {
                    const form = this.$refs.form;
                    
                    // Doble validación antes del envío
                    this.checkPasswordMatch();

                    if(form.checkValidity()) {
                        this.isSubmitting = true;
                    } else {
                        event.preventDefault(); // Detiene el envío si algo falla
                    }
                }
            }
        }
    </script>
</x-guest-layout>