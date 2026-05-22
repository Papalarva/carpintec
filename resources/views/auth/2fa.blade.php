<x-guest-layout>
    <div class="min-h-screen flex w-full overflow-hidden bg-white">
        
        <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900 items-center justify-center p-12">
            <div class="absolute inset-0 bg-cover bg-center opacity-40" 
                 style="background-image: url('https://images.unsplash.com/photo-1620646233562-f2a31eb2fc53?auto=format&fit=crop&q=80&w=1920');">
            </div>
            
            <div class="relative z-10 max-w-lg text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-6 border border-white/20">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <h2 class="font-serif text-5xl text-white tracking-tighter mb-4">Acceso Protegido</h2>
                <div class="h-1 w-16 bg-amber-600 mx-auto mb-6"></div>
                <p class="text-gray-300 font-sans text-lg font-light leading-relaxed">
                    Tu privacidad y la seguridad de tu información son nuestra prioridad.
                </p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 px-6 py-12 sm:px-12 lg:px-20">
            <div class="w-full max-w-md">
                
                <div class="mb-10 text-center lg:text-left">
                    <h2 class="font-serif text-4xl text-gray-900 tracking-tight mb-3">Verificación en 2 Pasos</h2>
                    <p class="font-sans text-gray-500 text-sm leading-relaxed">
                        Por tu seguridad, ingresa el código de 6 dígitos que acabamos de enviar a tu correo electrónico.
                    </p>
                </div>

                <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-8">
                    @csrf

                    <div x-data="{
                            code: ['', '', '', '', '', ''],
                            focusNext(index) {
                                if (this.code[index] && index < 5) {
                                    document.getElementById('code-' + (index + 1)).focus();
                                }
                            },
                            focusPrev(index, e) {
                                if (e.key === 'Backspace' && !this.code[index] && index > 0) {
                                    document.getElementById('code-' + (index - 1)).focus();
                                }
                            },
                            handlePaste(e) {
                                const paste = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                                for (let i = 0; i < paste.length; i++) {
                                    this.code[i] = paste[i];
                                }
                                setTimeout(() => {
                                    document.getElementById('code-' + Math.min(paste.length, 5)).focus();
                                }, 50);
                            }
                        }">
                        
                        <div class="flex justify-end max-w-xs mx-auto gap-2 sm:gap-3">
                            <template x-for="(digit, index) in code" :key="index">
                                <input :id="'code-' + index"
                                       x-model="code[index]"
                                       @input="code[index] = $event.target.value.replace(/[^0-9]/g, ''); focusNext(index)"
                                       @keydown="focusPrev(index, $event)"
                                       @paste.prevent="handlePaste($event)"
                                       type="text" 
                                       maxlength="1" 
                                       inputmode="numeric"
                                       autocomplete="one-time-code"
                                       class="w-12 h-14 sm:w-14 sm:h-16 text-center text-3xl font-sans rounded-xl border-gray-200 py-3 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-all text-gray-900 bg-white"
                                       :autofocus="index === 0">
                            </template>
                        </div>
                        
                        <input type="hidden" name="code" :value="code.join('')">
                        
                        <div class="text-center mt-3">
                            <x-input-error :messages="$errors->get('code')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-center lg:justify-start">
                        <input id="remember_device" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-amber-900 focus:ring-amber-800 transition-colors cursor-pointer" name="remember_device">
                        <label for="remember_device" class="ml-2 block font-sans text-sm text-gray-600 cursor-pointer">
                            No volver a pedir en este equipo por 30 días
                        </label>
                    </div>

                    <div>
                        <x-primary-button class="w-full justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-5 transition-all shadow-md active:scale-[0.98]">
                            Verificar y Entrar
                        </x-primary-button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    <p class="font-sans text-sm text-gray-500">
                        ¿No recibiste el correo o expiró tu código?
                    </p>
                    
                    <form id="resend-form" method="POST" action="{{ route('2fa.resend') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="text-amber-900 font-bold hover:text-amber-700 underline underline-offset-4 transition-colors text-sm focus:outline-none">
                            Reenviar código de seguridad
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>