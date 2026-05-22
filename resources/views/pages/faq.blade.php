<x-app-layout>
    <x-slot:title>
        Preguntas Frecuentes | Carpintec
    </x-slot:title>

    {{-- Es importante añadir esta regla en tu app.css si no la tienes, o dejarla aquí para evitar "parpadeos" al cargar Alpine --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="bg-gray-50/30 min-h-screen font-sans selection:bg-amber-900 selection:text-white pb-24 pt-32">
        
        {{-- Encabezado --}}
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-16 lg:mb-24">
            <span class="text-amber-800 font-semibold tracking-[0.2em] uppercase text-xs sm:text-sm mb-4 block">Soporte</span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-gray-900 tracking-tight mb-6">
                Resolviendo tus dudas.
            </h1>
            <p class="text-base sm:text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                Descubre cómo fabricamos, enviamos y cuidamos cada pieza. Si tienes una consulta específica, nuestro equipo está listo para asesorarte.
            </p>
        </div>

        {{-- Contenedor Principal de FAQs --}}
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

            {{-- Categoría 1: Cotizaciones y Pedidos --}}
            <section>
                <h2 class="font-serif text-2xl text-gray-900 mb-6 pb-2 border-b border-gray-200">Cotizaciones y Pedidos</h2>
                <div class="space-y-4">
                    
                    {{-- Item FAQ --}}
                    <div x-data="{ expanded: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <button @click="expanded = !expanded" class="w-full px-6 py-5 flex items-center justify-between focus:outline-none hover:bg-gray-50 transition-colors">
                            <span class="font-medium text-gray-900 text-left">¿Cómo funciona el proceso de cotización a medida?</span>
                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 shrink-0" :class="{ 'rotate-180 text-amber-800': expanded }" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                            </svg>
                        </button>
                        <div x-show="expanded" x-collapse x-cloak>
                            <div class="px-6 pb-6 text-gray-600 text-sm leading-relaxed border-t border-gray-50 pt-4">
                                Puedes solicitar una cotización directamente desde el catálogo o enviarnos tu propio diseño en PDF/Imagen. Nuestro equipo analizará los requerimientos, calculará los costos y te enviará una propuesta formal a tu panel. Una vez que apruebes el precio estimado, la cotización se convertirá automáticamente en un pedido formal listo para pago.
                            </div>
                        </div>
                    </div>

                    {{-- Item FAQ --}}
                    <div x-data="{ expanded: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <button @click="expanded = !expanded" class="w-full px-6 py-5 flex items-center justify-between focus:outline-none hover:bg-gray-50 transition-colors">
                            <span class="font-medium text-gray-900 text-left">¿Puedo modificar mi pedido una vez confirmado?</span>
                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 shrink-0" :class="{ 'rotate-180 text-amber-800': expanded }" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                            </svg>
                        </button>
                        <div x-show="expanded" x-collapse x-cloak>
                            <div class="px-6 pb-6 text-gray-600 text-sm leading-relaxed border-t border-gray-50 pt-4">
                                Debido a que cada mueble se fabrica bajo demanda y los materiales se preparan inmediatamente, solo aceptamos modificaciones durante las primeras 24 horas después de haber procesado el pago. Por favor, contáctanos urgentemente si necesitas un cambio.
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            {{-- Categoría 2: Envíos y Entregas --}}
            <section>
                <h2 class="font-serif text-2xl text-gray-900 mb-6 pb-2 border-b border-gray-200">Envíos y Entregas</h2>
                <div class="space-y-4">
                    
                    {{-- Item FAQ --}}
                    <div x-data="{ expanded: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <button @click="expanded = !expanded" class="w-full px-6 py-5 flex items-center justify-between focus:outline-none hover:bg-gray-50 transition-colors">
                            <span class="font-medium text-gray-900 text-left">¿Cuánto tiempo tarda en llegar mi mueble?</span>
                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 shrink-0" :class="{ 'rotate-180 text-amber-800': expanded }" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                            </svg>
                        </button>
                        <div x-show="expanded" x-collapse x-cloak>
                            <div class="px-6 pb-6 text-gray-600 text-sm leading-relaxed border-t border-gray-50 pt-4">
                                La fabricación artesanal toma tiempo. El periodo de producción promedio es de 3 a 4 semanas. Una vez que el pedido pasa al estado "Enviado", la logística toma de 3 a 7 días hábiles dependiendo de tu ubicación. Recibirás un número de guía para rastrear el paquete en todo momento.
                            </div>
                        </div>
                    </div>

                    {{-- Item FAQ --}}
                    <div x-data="{ expanded: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <button @click="expanded = !expanded" class="w-full px-6 py-5 flex items-center justify-between focus:outline-none hover:bg-gray-50 transition-colors">
                            <span class="font-medium text-gray-900 text-left">¿Los muebles se entregan armados?</span>
                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 shrink-0" :class="{ 'rotate-180 text-amber-800': expanded }" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                            </svg>
                        </button>
                        <div x-show="expanded" x-collapse x-cloak>
                            <div class="px-6 pb-6 text-gray-600 text-sm leading-relaxed border-t border-gray-50 pt-4">
                                La gran mayoría de nuestras piezas se entregan completamente ensambladas desde nuestro taller para garantizar la integridad estructural y la estética de las uniones. Piezas extremadamente grandes como mesas de comedor monumentales podrían requerir un ensamblaje mínimo (atornillar patas), para lo cual incluimos toda la tornillería y herramientas necesarias.
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            {{-- Categoría 3: Materiales y Cuidados --}}
            <section>
                <h2 class="font-serif text-2xl text-gray-900 mb-6 pb-2 border-b border-gray-200">Materiales y Cuidados</h2>
                <div class="space-y-4">
                    
                    {{-- Item FAQ --}}
                    <div x-data="{ expanded: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <button @click="expanded = !expanded" class="w-full px-6 py-5 flex items-center justify-between focus:outline-none hover:bg-gray-50 transition-colors">
                            <span class="font-medium text-gray-900 text-left">¿Qué tipo de maderas utilizan?</span>
                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 shrink-0" :class="{ 'rotate-180 text-amber-800': expanded }" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                            </svg>
                        </button>
                        <div x-show="expanded" x-collapse x-cloak>
                            <div class="px-6 pb-6 text-gray-600 text-sm leading-relaxed border-t border-gray-50 pt-4">
                                Trabajamos exclusivamente con maderas sólidas de alta densidad y origen sustentable, principalmente Encino, Nogal, Parota y Tzalam. No utilizamos aglomerados ni MDF en nuestras superficies principales, garantizando que cada mueble tenga una vida útil de décadas.
                            </div>
                        </div>
                    </div>

                    {{-- Item FAQ --}}
                    <div x-data="{ expanded: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <button @click="expanded = !expanded" class="w-full px-6 py-5 flex items-center justify-between focus:outline-none hover:bg-gray-50 transition-colors">
                            <span class="font-medium text-gray-900 text-left">¿Cómo debo limpiar y cuidar mis muebles?</span>
                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300 shrink-0" :class="{ 'rotate-180 text-amber-800': expanded }" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                            </svg>
                        </button>
                        <div x-show="expanded" x-collapse x-cloak>
                            <div class="px-6 pb-6 text-gray-600 text-sm leading-relaxed border-t border-gray-50 pt-4">
                                Para el cuidado diario, utiliza un paño de microfibra seco o ligeramente húmedo. Evita estrictamente los limpiadores químicos en aerosol, siliconas o solventes, ya que pueden dañar el barniz de poliuretano protector. Recomendamos evitar la luz solar directa prolongada para mantener el tono natural de la madera.
                            </div>
                        </div>
                    </div>

                </div>
            </section>

        </div>

        {{-- Contacto Directo --}}
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-24">
            <div class="bg-amber-900 rounded-2xl p-8 sm:p-10 text-center shadow-sm relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-serif text-2xl text-white mb-4">¿No encontraste lo que buscabas?</h3>
                    <p class="text-amber-100 text-sm mb-8 max-w-md mx-auto">
                        Nuestro equipo de soporte y diseño está disponible de Lunes a Viernes para resolver cualquier duda técnica o logística.
                    </p>
                    <a href="{{ route('contact.index') }}" class="inline-flex items-center justify-center bg-white text-amber-900 hover:bg-gray-50 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200">
                        Contáctanos
                    </a>
                </div>
                {{-- Elemento decorativo sutil --}}
                <svg class="absolute top-0 right-0 -mt-16 -mr-16 text-amber-800 opacity-50 w-64 h-64 transform rotate-12 pointer-events-none" fill="none" stroke="currentColor" stroke-width="0.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z"></path>
                </svg>
            </div>
        </div>

    </div>
</x-app-layout>