<footer class="bg-neutral-900 text-white pt-16 pb-8 border-t border-neutral-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

            <!-- Columna 1: Marca y Ubicación -->
            <div class="space-y-6">
                <span class="font-serif text-3xl font-bold tracking-tight text-white block">
                    CARPINTEC.
                </span>
                <p class="text-sm text-neutral-400 leading-relaxed">
                    Creando mobiliario de autor y espacios excepcionales. Cada pieza es diseñada con precisión, pasión y
                    la más alta calidad en maderas finas.
                </p>
                <div class="flex items-start space-x-3 text-sm text-neutral-400">
                    <svg class="h-5 w-5 flex-shrink-0 text-amber-600 mt-0.5" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <span>
                        Showroom Principal<br>
                        Ciudad Juárez, Chihuahua<br>
                        México
                    </span>
                </div>
            </div>

            <!-- Columna 2: Enlaces Rápidos -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-500 mb-6">Explorar</h3>
                <ul class="space-y-4">
                    <li><a href="{{ route('catalog.index') }}"
                            class="text-sm text-neutral-300 hover:text-amber-500 transition-colors">Catálogo
                            Completo</a></li>
                    <li><a href="{{ route('collections.newest') }}"
                            class="text-sm text-neutral-300 hover:text-amber-500 transition-colors">Nuevas
                            Colecciones</a></li>
                    <li><a href="#"
                            class="text-sm text-neutral-300 hover:text-amber-500 transition-colors">Proyectos a
                            Medida</a></li>
                    <li><a href="{{ route('about') }}"
                            class="text-sm text-neutral-300 hover:text-amber-500 transition-colors">Sobre Nosotros</a>
                    </li>
                </ul>
            </div>

            <!-- Columna 3: Atención al Cliente -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-500 mb-6">Soporte</h3>
                <ul class="space-y-4">
                    <li><a href="{{ route('faq') }}"
                            class="text-sm text-neutral-300 hover:text-amber-500 transition-colors">Preguntas
                            Frecuentes</a></li>
                    <li><a href="{{ route('shipping') }}"
                            class="text-sm text-neutral-300 hover:text-amber-500 transition-colors">Envíos y
                            Entregas</a></li>
                    <li><a href="{{ route('warranty') }}"
                            class="text-sm text-neutral-300 hover:text-amber-500 transition-colors">Garantía de
                            Calidad</a></li>
                    <li><a href="{{ route('contact.index') }}"
                            class="text-sm text-neutral-300 hover:text-amber-500 transition-colors">Contacto</a></li>
                </ul>
            </div>

            <!-- Columna 4: Newsletter -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-500 mb-6">Newsletter</h3>
                <p class="text-sm text-neutral-400 mb-4">Suscríbete para recibir noticias sobre nuevas colecciones y
                    piezas exclusivas.</p>

                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col space-y-3">
                    @csrf
                    <div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="Tu correo electrónico"
                            class="bg-neutral-800 border border-neutral-700 text-white text-sm rounded-lg focus:ring-amber-600 focus:border-amber-600 block w-full p-3 placeholder-neutral-500 transition-colors">

                        @error('email')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full inline-flex justify-center items-center bg-amber-800 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded-lg text-sm transition-colors duration-200">
                        Suscribirse
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75">
                            </path>
                        </svg>
                    </button>
                </form>
            </div>

        </div>

        <!-- Parte Inferior (Legal y Derechos) -->
        <div
            class="pt-8 border-t border-neutral-800 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <p class="text-xs text-neutral-500">
                &copy; {{ date('Y') }} Carpintec. Todos los derechos reservados.
            </p>
            <div class="flex space-x-6 text-xs text-neutral-500">
                <a href="{{ route('terms') }}" class="hover:text-amber-500 transition-colors">Términos de Servicio</a>
                <a href="{{ route('privacy') }}" class="hover:text-amber-500 transition-colors">Política de
                    Privacidad</a>
            </div>
        </div>
    </div>
</footer>
