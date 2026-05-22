<x-app-layout>
    <x-slot:title>
        Aviso de Privacidad | Carpintec
    </x-slot:title>

    <div class="bg-gray-50/30 min-h-screen font-sans selection:bg-amber-900 selection:text-white pb-24 pt-32">
        
        {{-- Encabezado --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-16 lg:mb-20">
            <span class="text-amber-800 font-semibold tracking-[0.2em] uppercase text-xs sm:text-sm mb-4 block">Privacidad</span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-gray-900 tracking-tight mb-6">
                Aviso de Privacidad
            </h1>
            <p class="text-gray-500 text-sm">Última actualización: {{ now()->format('d \d\e F, Y') }}</p>
        </div>

        {{-- Contenido Legal --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-16 space-y-12">
                
                {{-- Identidad --}}
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                        </svg>
                        <h2 class="font-serif text-2xl text-gray-900">Identidad y Domicilio</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Carpintec, con domicilio en Ciudad Juárez, Chihuahua, México, es responsable del tratamiento de sus datos personales. Nuestra prioridad es proteger la privacidad de nuestros clientes y artesanos, garantizando que su información sea utilizada exclusivamente para los fines de diseño, fabricación y entrega de piezas de mobiliario.
                    </p>
                </section>

                {{-- Datos Recabados --}}
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                        </svg>
                        <h2 class="font-serif text-2xl text-gray-900">Datos Personales Recabados</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm mb-4">
                        Para llevar a cabo las finalidades descritas en el presente aviso, recabaremos los siguientes datos:
                    </p>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-2 pl-4">
                        <li><strong>Identificación:</strong> Nombre(s) y Apellidos.</li>
                        <li><strong>Contacto:</strong> Correo electrónico y número telefónico.</li>
                        <li><strong>Logística:</strong> Dirección de envío para la entrega de muebles.</li>
                        <li><strong>Interacción:</strong> Información proporcionada en formularios de cotización o archivos técnicos adjuntos.</li>
                    </ul>
                </section>

                {{-- Finalidades --}}
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h2 class="font-serif text-2xl text-gray-900">Finalidades del Tratamiento</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm mb-4">
                        Utilizamos su información para las siguientes finalidades primarias:
                    </p>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-2 pl-4">
                        <li>Gestión de su cuenta de usuario y perfil en Carpintec.</li>
                        <li>Procesamiento de cotizaciones personalizadas y presupuestos.</li>
                        <li>Fabricación y personalización de piezas de madera.</li>
                        <li>Envío y entrega de pedidos a través de nuestra red logística.</li>
                        <li>Atención al cliente y soporte post-venta (garantías).</li>
                    </ul>
                </section>

                {{-- Seguridad --}}
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                        </svg>
                        <h2 class="font-serif text-2xl text-gray-900">Seguridad de la Información</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Carpintec implementa medidas de seguridad técnicas y administrativas para proteger sus datos personales contra daño, pérdida, alteración, destrucción o el uso, acceso o tratamiento no autorizado. Todas las transacciones se realizan bajo protocolos de cifrado y no almacenamos datos sensibles de sus métodos de pago.
                    </p>
                </section>

                {{-- Derechos ARCO --}}
                <section>
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                        </svg>
                        <h2 class="font-serif text-2xl text-gray-900">Derechos ARCO</h2>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Usted tiene derecho a conocer qué datos personales tenemos de usted, para qué los utilizamos y las condiciones del uso que les damos (Acceso). Asimismo, es su derecho solicitar la corrección de su información (Rectificación); que la eliminemos de nuestros registros (Cancelación); así como oponerse al uso de sus datos para fines específicos (Oposición). Para ejercer estos derechos, puede dirigirse a privacidad@carpintec.local.
                    </p>
                </section>

                <div class="pt-12 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400 italic">Este aviso se rige por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares en México.</p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>