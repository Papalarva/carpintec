<x-guest-layout>
    <div class="min-h-screen flex w-full overflow-hidden bg-white">
        
        <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900 items-center justify-center p-12">
            <div class="absolute inset-0 bg-cover bg-center opacity-40" 
                 style="background-image: url('https://images.unsplash.com/photo-1554774853-719586f82d77?auto=format&fit=crop&q=80&w=1920');">
            </div>
            
            <div class="relative z-10 max-w-lg text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-6 border border-white/20">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <h2 class="font-serif text-5xl text-white tracking-tighter mb-4">Solo un paso más</h2>
                <div class="h-1 w-16 bg-amber-600 mx-auto mb-6"></div>
                <p class="text-gray-300 font-sans text-lg font-light leading-relaxed">
                    Aseguramos la comunicación directa contigo para entregarte cotizaciones y actualizaciones de tus proyectos sin demoras.
                </p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 px-6 py-12 sm:px-12 lg:px-20">
            <div class="w-full max-w-md">
                
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="font-serif text-4xl text-gray-900 tracking-tight mb-3">Verifica tu correo</h2>
                    <p class="font-sans text-gray-600 text-sm leading-relaxed">
                        ¡Gracias por unirte a Carpintec! Antes de comenzar a solicitar proyectos a medida, necesitamos validar tu dirección de correo electrónico.
                    </p>
                    <p class="font-sans text-gray-500 text-sm leading-relaxed mt-4">
                        Revisa tu bandeja de entrada y haz clic en el enlace que te acabamos de enviar. Si no lo encuentras, con gusto te enviaremos otro.
                    </p>
                </div>

                <div class="mt-8 flex flex-col items-center lg:items-start space-y-4 w-full">
                    
                    <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                        @csrf
                        <x-primary-button class="w-full justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-all shadow-md active:scale-[0.98]">
                            Reenviar correo de verificación
                        </x-primary-button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="w-full mt-4 text-center lg:text-left">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-amber-900 font-medium underline underline-offset-4 transition-colors focus:outline-none">
                            Cerrar sesión
                        </button>
                    </form>
                    
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>