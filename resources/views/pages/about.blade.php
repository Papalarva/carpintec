<x-app-layout>
    <x-slot:title>
        Sobre Nosotros | Carpintec
    </x-slot:title>

    <div class="bg-gray-50/30 min-h-screen font-sans selection:bg-amber-900 selection:text-white pb-24">

        {{-- 1. Hero Section Editorial --}}
        <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
                <span
                    class="text-amber-800 font-semibold tracking-[0.2em] uppercase text-xs sm:text-sm mb-4 block">Nuestra
                    Esencia</span>
                <h1 class="font-serif text-4xl sm:text-5xl lg:text-7xl text-gray-900 tracking-tight mb-8">
                    Arte en cada veta,<br> <span class="text-gray-400 font-italic">diseño en cada espacio.</span>
                </h1>
                <p class="max-w-2xl mx-auto text-base sm:text-lg text-gray-600 leading-relaxed">
                    En Carpintec no fabricamos simples muebles; esculpimos la madera para crear piezas atemporales que
                    cuentan una historia, elevan tu entorno y perduran por generaciones.
                </p>
            </div>
        </section>

        {{-- 2. Sección de Historia (Imagen + Texto cruzado) --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {{-- Placeholder de Imagen Premium --}}
                <div class="relative group">
                    <div
                        class="absolute inset-0 bg-amber-900/10 transform translate-x-4 translate-y-4 rounded-2xl transition-transform duration-500 group-hover:translate-x-2 group-hover:translate-y-2">
                    </div>
                    <div class="relative aspect-[4/5] bg-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <img src="{{ Vite::asset('resources/images/about/taller-carpintec.jpg') }}"
                            alt="Artesanos de Carpintec trabajando madera"
                            class="rounded-2xl object-cover w-full h-full">
                    </div>
                </div>

                <div class="lg:pl-8 space-y-8">
                    <h2 class="font-serif text-3xl sm:text-4xl text-gray-900">Una tradición de precisión y pasión.</h2>
                    <div class="space-y-6 text-gray-600 leading-relaxed">
                        <p>
                            Nacimos de la convicción de que el diseño minimalista no tiene por qué ser frío. Cada pieza
                            que sale de nuestro taller es el resultado de un riguroso proceso donde la tecnología
                            moderna se encuentra con la ebanistería tradicional.
                        </p>
                        <p>
                            Seleccionamos meticulosamente cada bloque de madera, respetando sus imperfecciones naturales
                            y vetas únicas, asegurando que adquieras no solo un artículo funcional, sino una obra
                            exclusiva y sostenible.
                        </p>
                    </div>

                    <div class="pt-6 border-t border-gray-200">
                        <p class="font-serif text-xl text-gray-900 italic">"Diseñamos para quienes aprecian el silencio
                            visual y la calidad táctil."</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- 3. Valores de la Marca (Con íconos outline 1.5px) --}}
        <section class="bg-white border-y border-gray-100 py-24 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-serif text-3xl sm:text-4xl text-gray-900">Nuestros Pilares</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-16">
                    {{-- Pilar 1 --}}
                    <div class="text-center group">
                        <div
                            class="mx-auto w-16 h-16 flex items-center justify-center bg-gray-50 rounded-full mb-6 text-gray-900 group-hover:bg-amber-50 group-hover:text-amber-800 transition-colors duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-serif text-xl text-gray-900 mb-3">Sustentabilidad</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Maderas de origen ético y procesos que respetan
                            nuestro entorno para un futuro más verde.</p>
                    </div>

                    {{-- Pilar 2 --}}
                    <div class="text-center group">
                        <div
                            class="mx-auto w-16 h-16 flex items-center justify-center bg-gray-50 rounded-full mb-6 text-gray-900 group-hover:bg-amber-50 group-hover:text-amber-800 transition-colors duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-serif text-xl text-gray-900 mb-3">Artesanía Pura</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Ebanistería de precisión. Cada detalle cuenta,
                            desde los ensambles hasta el pulido final a mano.</p>
                    </div>

                    {{-- Pilar 3 --}}
                    <div class="text-center group">
                        <div
                            class="mx-auto w-16 h-16 flex items-center justify-center bg-gray-50 rounded-full mb-6 text-gray-900 group-hover:bg-amber-50 group-hover:text-amber-800 transition-colors duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-serif text-xl text-gray-900 mb-3">Diseño Atemporal</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">Formas limpias y geometrías exactas que escapan
                            de las tendencias pasajeras para vivir en armonía visual.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- 4. CTA (Llamado a la Acción) --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-24 text-center">
            <h2 class="font-serif text-3xl sm:text-4xl text-gray-900 mb-6">¿Listo para transformar tu espacio?</h2>
            <p class="text-gray-600 mb-10 max-w-xl mx-auto">Explora nuestro catálogo y descubre la pieza que fue
                diseñada pensando en ti.</p>
            <a href="{{ route('catalog.index') }}"
                class="inline-flex items-center justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-10 py-5 transition-colors duration-200 shadow-sm">
                Ver Catálogo
            </a>
        </section>

    </div>
</x-app-layout>
