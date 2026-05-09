<x-app-layout>
    <x-slot:title>
        Términos de Servicio | Carpintec
    </x-slot:title>

    <div class="bg-gray-50/30 min-h-screen font-sans selection:bg-amber-900 selection:text-white pb-24 pt-32">
        
        {{-- Encabezado --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-16 lg:mb-20">
            <span class="text-amber-800 font-semibold tracking-[0.2em] uppercase text-xs sm:text-sm mb-4 block">Legal</span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-gray-900 tracking-tight mb-6">
                Términos de Servicio
            </h1>
            <p class="text-gray-500 text-sm">Última actualización: {{ now()->format('d \d\e F, Y') }}</p>
        </div>

        {{-- Contenido Legal --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-16 space-y-12">
                
                {{-- Sección 1 --}}
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                        </svg>
                        <h2 class="font-serif text-2xl text-gray-900">Aceptación de los Términos</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Al acceder y utilizar el sitio web de Carpintec, usted acepta cumplir y estar sujeto a los siguientes términos y condiciones de uso. Estos términos rigen la relación entre usted y nuestro taller artesanal en Ciudad Juárez respecto a la compra de muebles y servicios de cotización a medida.
                    </p>
                </section>

                {{-- Sección 2 --}}
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                        </svg>
                        <h2 class="font-serif text-2xl text-gray-900">Productos y Propiedades Naturales</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm mb-4">
                        Cada mueble es único. El cliente reconoce que la madera maciza es un material natural y vivo, por lo que:
                    </p>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-2 pl-4">
                        <li>Las variaciones en la veta, nudos y tonalidad no se consideran defectos de fabricación.</li>
                        <li>Las dimensiones finales pueden variar ligeramente debido al proceso de lijado artesanal.</li>
                        <li>La madera puede experimentar movimientos sutiles según el clima de su ubicación.</li>
                    </ul>
                </section>

                {{-- Sección 3 --}}
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .415.162.798.425 1.082.263.285.629.46 1.037.477 1.12.047 1.891.956 1.891 2.054v9.455c0 1.192-.906 2.191-2.094 2.258-1.066.06-2.132.088-3.202.088-1.069 0-2.136-.028-3.202-.088-1.188-.067-2.094-1.114-2.094-2.258V6.108c0-1.135.845-2.098 1.976-2.192a48.424 48.424 0 011.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .415.162.798.425 1.082.263.285.629.46 1.037.477 1.12.047 1.891.956 1.891 2.054v9.455c0 1.192-.906 2.191-2.094 2.258-1.066.06-2.132.088-3.202.088-1.069 0-2.136-.028-3.202-.088-1.188-.067-2.094-1.114-2.094-2.258V6.108c0-1.135.845-2.098 1.976-2.192a48.424 48.424 0 011.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .415.162.798.425 1.082.263.285.629.46 1.037.477 1.12.047 1.891.956 1.891 2.054v9.455c0 1.192-.906 2.191-2.094 2.258-1.066.06-2.132.088-3.202.088-1.069 0-2.136-.028-3.202-.088-1.188-.067-2.094-1.114-2.094-2.258V6.108c0-1.135.845-2.098 1.976-2.192a48.424 48.424 0 011.123-.08"></path>
                        </svg>
                        <h2 class="font-serif text-2xl text-gray-900">Cotizaciones Personalizadas</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Las cotizaciones enviadas a través de nuestro portal tienen una vigencia de 15 días naturales. Carpintec se reserva el derecho de ajustar los precios estimados después de este periodo debido a cambios en los costos internacionales de la madera o insumos. Un pedido se considera "En Producción" únicamente tras la confirmación del pago total o anticipo pactado.
                    </p>
                </section>

                {{-- Sección 4 --}}
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h2 class="font-serif text-2xl text-gray-900">Pagos y Precios</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Todos los precios están expresados en Pesos Mexicanos (MXN). Utilizamos pasarelas de pago seguras y encriptadas. En caso de pedidos personalizados, el pago inicial no es reembolsable una vez que los materiales han sido cortados o preparados específicamente para su proyecto.
                    </p>
                </section>

                {{-- Sección 5 --}}
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"></path>
                        </svg>
                        <h2 class="font-serif text-2xl text-gray-900">Ley Aplicable</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Cualquier controversia derivada del uso de este sitio o la compra de productos se regirá por las leyes vigentes en el Estado de Chihuahua, México, y se someterá a la jurisdicción de los tribunales competentes en Ciudad Juárez.
                    </p>
                </section>

                <div class="pt-12 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400">Si tiene alguna duda sobre estos términos, por favor contáctenos a legal@carpintec.local</p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>