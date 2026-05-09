<x-app-layout>
    <x-slot:title>
        Garantía de Calidad | Carpintec
    </x-slot:title>

    <div class="bg-gray-50/30 min-h-screen font-sans selection:bg-amber-900 selection:text-white pb-24 pt-32">
        
        {{-- Encabezado Editorial --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-20 lg:mb-28">
            <span class="text-amber-800 font-semibold tracking-[0.2em] uppercase text-xs sm:text-sm mb-4 block">Nuestra Promesa</span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-gray-900 tracking-tight mb-6">
                Diseñados para trascender.
            </h1>
            <p class="text-base sm:text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                Respaldamos la maestría de nuestra ebanistería. Cada pieza de Carpintec está protegida por una garantía estructural diseñada para darte absoluta tranquilidad.
            </p>
        </div>

        {{-- Qué cubre la garantía (Grid con Iconos Outline 1.5px) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 border-y border-gray-200 py-16">
                
                {{-- Punto 1 --}}
                <div class="text-center group">
                    <div class="mx-auto w-14 h-14 flex items-center justify-center bg-white border border-gray-200 rounded-full mb-6 text-amber-800 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"></path>
                        </svg>
                    </div>
                    <h3 class="font-serif text-xl text-gray-900 mb-3">5 Años en Estructura</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Garantizamos la integridad de los ensambles, bastidores y uniones contra defectos de fabricación o fallas en el encolado bajo condiciones de uso normal.
                    </p>
                </div>

                {{-- Punto 2 --}}
                <div class="text-center group">
                    <div class="mx-auto w-14 h-14 flex items-center justify-center bg-white border border-gray-200 rounded-full mb-6 text-amber-800 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-serif text-xl text-gray-900 mb-3">1 Año en Herrajes</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Cubrimos correderas, bisagras, jaladeras y sistemas de apertura contra defectos mecánicos. Utilizamos componentes de grado premium para asegurar su fluidez.
                    </p>
                </div>

                {{-- Punto 3 --}}
                <div class="text-center group">
                    <div class="mx-auto w-14 h-14 flex items-center justify-center bg-white border border-gray-200 rounded-full mb-6 text-amber-800 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"></path>
                        </svg>
                    </div>
                    <h3 class="font-serif text-xl text-gray-900 mb-3">1 Año en Acabados</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Garantizamos la aplicación de nuestros barnices de poliuretano y aceites naturales contra desprendimientos inusuales no derivados del uso diario o químicos.
                    </p>
                </div>

            </div>
        </div>

        {{-- La sección educativa: Entendiendo la madera maciza --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12 lg:p-16">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                    <div>
                        <h2 class="font-serif text-3xl text-gray-900 mb-6">La madera respira.</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Al elegir Carpintec, estás eligiendo un pedazo de naturaleza. La madera maciza es un material vivo que reacciona a su entorno, y estas reacciones no son defectos, sino características que certifican su autenticidad.
                        </p>
                        
                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Lo que NO se considera defecto:</h4>
                        <ul class="space-y-4 text-sm text-gray-600">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-800 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                                <span><strong>Variaciones de color y veta:</strong> Ningún árbol es idéntico a otro. Las diferencias de tonalidad entre tablones son completamente normales.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-800 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                                <span><strong>Nudos e imperfecciones naturales:</strong> Estas marcas son el historial de crecimiento del árbol y se integran al diseño aportando carácter.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-800 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                                <span><strong>Expansión y contracción:</strong> Según la humedad y temperatura de tu hogar, la madera puede tener ligeros movimientos estacionales. Nuestros ensambles están diseñados para permitirlos sin romperse.</span>
                            </li>
                        </ul>
                    </div>
                    
                    {{-- Bloque de Exclusiones --}}
                    <div class="bg-gray-50 rounded-xl p-8 border border-gray-100">
                        <h3 class="font-serif text-2xl text-gray-900 mb-4">Exclusiones de la Garantía</h3>
                        <p class="text-sm text-gray-600 mb-6">
                            Para mantener la vigencia de tu garantía, es fundamental darle el cuidado adecuado a tus muebles. La garantía pierde validez en los siguientes casos:
                        </p>
                        <ul class="space-y-3 text-sm text-gray-600 list-disc list-inside marker:text-gray-400">
                            <li>Uso de limpiadores químicos abrasivos o siliconas.</li>
                            <li>Exposición directa y prolongada a la luz solar o lluvia (en muebles de interior).</li>
                            <li>Daños por arrastre, golpes o accidentes de mascotas.</li>
                            <li>Modificaciones, cortes o alteraciones hechas por terceros.</li>
                            <li>Colocación de objetos extremadamente calientes directamente sobre la superficie.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cómo hacerla válida --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-serif text-3xl text-gray-900 mb-8">¿Necesitas hacer válida tu garantía?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left mb-12">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative">
                    <span class="absolute -top-4 -left-4 w-8 h-8 flex items-center justify-center bg-amber-900 text-white rounded-full font-bold text-sm shadow-sm">1</span>
                    <h4 class="font-bold text-gray-900 mb-2">Documenta</h4>
                    <p class="text-sm text-gray-600">Toma fotografías claras y un video breve mostrando el detalle o área afectada del mueble.</p>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative">
                    <span class="absolute -top-4 -left-4 w-8 h-8 flex items-center justify-center bg-amber-900 text-white rounded-full font-bold text-sm shadow-sm">2</span>
                    <h4 class="font-bold text-gray-900 mb-2">Escríbenos</h4>
                    <p class="text-sm text-gray-600">Envíanos un correo con las evidencias y el número de tu pedido para que nuestros artesanos evalúen el caso.</p>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative">
                    <span class="absolute -top-4 -left-4 w-8 h-8 flex items-center justify-center bg-amber-900 text-white rounded-full font-bold text-sm shadow-sm">3</span>
                    <h4 class="font-bold text-gray-900 mb-2">Resolución</h4>
                    <p class="text-sm text-gray-600">Te ofreceremos una solución que puede ir desde el envío de refacciones hasta la reparación en taller.</p>
                </div>
            </div>
            
            <a href="{{ route('contact.index') }}" class="inline-flex items-center justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-10 py-5 transition-colors duration-200 shadow-sm">
                Contactar a Soporte
            </a>
        </div>

    </div>
</x-app-layout>