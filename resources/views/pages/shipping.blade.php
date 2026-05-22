<x-app-layout>
    <x-slot:title>
        Envíos y Entregas | Carpintec
    </x-slot:title>

    <div class="bg-gray-50/30 min-h-screen font-sans selection:bg-amber-900 selection:text-white pb-24 pt-32">
        
        {{-- Encabezado Editorial --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-20 lg:mb-28">
            <span class="text-amber-800 font-semibold tracking-[0.2em] uppercase text-xs sm:text-sm mb-4 block">Logística Especializada</span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-gray-900 tracking-tight mb-6">
                Llevamos el arte a tu puerta.
            </h1>
            <p class="text-base sm:text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                Entendemos que no enviamos simples cajas, sino piezas de diseño. Nuestra red logística está estructurada para garantizar que tu mueble llegue en condiciones prístinas.
            </p>
        </div>

        {{-- Proceso de Envío (Línea de tiempo con Iconos Outline 1.5px) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
            <h2 class="font-serif text-3xl text-gray-900 text-center mb-16">Nuestro Proceso de Entrega</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                {{-- Línea conectora (visible solo en escritorio) --}}
                <div class="hidden md:block absolute top-12 left-[15%] right-[15%] h-[1px] bg-gray-200 border-t border-dashed border-gray-300"></div>

                {{-- Paso 1 --}}
                <div class="text-center relative z-10">
                    <div class="mx-auto w-24 h-24 flex items-center justify-center bg-white border border-gray-100 rounded-full mb-6 text-amber-800 shadow-sm">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"></path>
                        </svg>
                    </div>
                    <h3 class="font-serif text-xl text-gray-900 mb-3">1. Empaque Estructural</h3>
                    <p class="text-sm text-gray-600 leading-relaxed px-4">
                        Cada mueble es envuelto en capas de protección anti-impacto y, para piezas monumentales, construimos huacales de madera a medida para blindar tu inversión.
                    </p>
                </div>

                {{-- Paso 2 --}}
                <div class="text-center relative z-10">
                    <div class="mx-auto w-24 h-24 flex items-center justify-center bg-white border border-gray-100 rounded-full mb-6 text-amber-800 shadow-sm">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"></path>
                        </svg>
                    </div>
                    <h3 class="font-serif text-xl text-gray-900 mb-3">2. Tránsito Seguro</h3>
                    <p class="text-sm text-gray-600 leading-relaxed px-4">
                        Trabajamos exclusivamente con flotas transportistas certificadas en manejo de carga delicada. Podrás monitorear tu pedido en tiempo real con tu número de guía.
                    </p>
                </div>

                {{-- Paso 3 --}}
                <div class="text-center relative z-10">
                    <div class="mx-auto w-24 h-24 flex items-center justify-center bg-white border border-gray-100 rounded-full mb-6 text-amber-800 shadow-sm">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"></path>
                        </svg>
                    </div>
                    <h3 class="font-serif text-xl text-gray-900 mb-3">3. Recepción en Hogar</h3>
                    <p class="text-sm text-gray-600 leading-relaxed px-4">
                        Tu mueble llega listo para habitar tu espacio. La entrega estándar se realiza a pie de camión o en planta baja, asegurando un proceso ágil y respetuoso con tu domicilio.
                    </p>
                </div>
            </div>
        </div>

        {{-- Tiempos y Cobertura (Dos columnas informativas) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    
                    {{-- Cobertura Local / Origen --}}
                    <div class="p-8 sm:p-12 lg:p-16 border-b lg:border-b-0 lg:border-r border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"></path></svg>
                            <h2 class="font-serif text-2xl text-gray-900">Desde nuestro taller</h2>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Todos nuestros envíos parten desde nuestro taller ubicado en Ciudad Juárez, Chihuahua. Para nuestros clientes locales, ofrecemos un servicio de entrega directa y personalizada.
                        </p>
                        <ul class="space-y-4 text-sm text-gray-600">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-800 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                <span><strong>Entregas locales:</strong> Coordinadas directamente por nuestro equipo, con opción de instalación en sitio según el volumen de la pieza.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-800 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                <span><strong>Tiempo estimado local:</strong> 1 a 2 días hábiles una vez concluido el proceso de fabricación y barnizado.</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Cobertura Nacional --}}
                    <div class="p-8 sm:p-12 lg:p-16 bg-gray-50/50">
                        <div class="flex items-center gap-3 mb-6">
                            <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5"></path></svg>
                            <h2 class="font-serif text-2xl text-gray-900">Envíos Nacionales</h2>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Llegamos a casi todo el territorio mexicano. Los costos de envío se calculan de manera dinámica durante tu proceso de pago, basándose en el volumen de tu compra y tu código postal.
                        </p>
                        <ul class="space-y-4 text-sm text-gray-600">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-800 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                <span><strong>Cobertura:</strong> Principales ciudades y áreas metropolitanas de la República Mexicana.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-800 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                <span><strong>Tiempo de tránsito:</strong> Entre 3 y 7 días hábiles, posteriores al tiempo de fabricación indicado en cada producto.</span>
                            </li>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </div>

        {{-- Recepción de Mercancía --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-serif text-3xl text-gray-900 mb-6">Al recibir tu mueble</h2>
            <p class="text-gray-600 mb-10 leading-relaxed">
                Nuestros embalajes son altamente seguros, pero la logística a veces escapa a nuestro control. Es vital que, al momento de recibir tu paquete, inspecciones el exterior de las cajas o huacales. Si notas abolladuras profundas, rupturas o humedad, haz una anotación en la boleta del transportista antes de firmar y contáctanos inmediatamente.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('contact.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-white border border-gray-300 text-gray-900 hover:bg-gray-50 uppercase tracking-widest text-sm font-bold rounded-xl px-10 py-4 transition-colors duration-200 shadow-sm">
                    Contactar Logística
                </a>
                <a href="{{ route('faq') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-10 py-4 transition-colors duration-200 shadow-sm">
                    Leer Preguntas Frecuentes
                </a>
            </div>
        </div>

    </div>
</x-app-layout>