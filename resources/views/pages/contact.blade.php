<x-app-layout>
    <x-slot:title>
        Contacto | Carpintec
    </x-slot:title>

    <div class="bg-gray-50/30 min-h-screen font-sans selection:bg-amber-900 selection:text-white pb-24 pt-32">

        {{-- Encabezado --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-16 lg:mb-24">
            <span
                class="text-amber-800 font-semibold tracking-[0.2em] uppercase text-xs sm:text-sm mb-4 block">Contáctanos</span>
            <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl text-gray-900 tracking-tight mb-6">
                Hablemos de tu próximo proyecto.
            </h1>
            <p class="text-base sm:text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                Ya sea para resolver dudas sobre un mueble de nuestro catálogo o para iniciar una cotización a medida,
                nuestro taller está abierto a tus ideas.
            </p>
        </div>

        {{-- Contenedor Principal: 2 Columnas --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24">

                {{-- Columna Izquierda: Información del Taller --}}
                <div class="space-y-12 lg:pr-8">
                    <div>
                        <h2 class="font-serif text-3xl text-gray-900 mb-8">Información Directa</h2>
                        <p class="text-gray-600 mb-8 leading-relaxed">
                            No utilizamos intermediarios. Al escribirnos, te comunicas directamente con los artesanos y
                            el equipo de diseño que dará vida a tu mueble.
                        </p>
                    </div>

                    <div class="space-y-8">
                        {{-- Dirección --}}
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor"
                                    stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-1">El Taller
                                </h3>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Ciudad Juárez, Chihuahua<br>
                                    México<br>
                                    <span class="text-xs text-gray-400 mt-1 block">(Atención presencial solo con cita
                                        previa)</span>
                                </p>
                            </div>
                        </div>

                        {{-- Correo --}}
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor"
                                    stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5H4.5a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-1">Correo
                                    Electrónico</h3>
                                <a href="mailto:contacto@carpintec.local"
                                    class="text-indigo-600 hover:text-indigo-800 transition-colors text-sm">
                                    contacto@carpintec.local
                                </a>
                            </div>
                        </div>

                        {{-- Horarios --}}
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor"
                                    stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-1">Horario de
                                    Atención</h3>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Lunes a Viernes: 9:00 AM - 6:00 PM<br>
                                    Sábados: 10:00 AM - 2:00 PM
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Columna Derecha: Formulario Elegante --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-10 relative">

                    {{-- Formulario --}}
                    <form method="POST" action="{{ route('contact.send') }}" class="space-y-6" x-data="{ sending: false }"
                        @submit="sending = true"> @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre
                                    Completo</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors placeholder-gray-400"
                                    placeholder="Ej. Juan Pérez">
                                @error('name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono
                                    <span class="text-gray-400 font-normal">(Opcional)</span></label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                    maxlength="10" x-on:input="$el.value = $el.value.replace(/[^0-9\+\-\s\(\)]/g, '')"
                                    class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors placeholder-gray-400"
                                    placeholder="Ej. +52 (656) 123 4567">
                                @error('phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo
                                Electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors placeholder-gray-400"
                                placeholder="correo@ejemplo.com">
                            @error('email')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Asunto</label>
                            <select id="subject" name="subject" required
                                class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors text-gray-700">
                                <option value="" disabled selected>Selecciona un motivo...</option>
                                <option value="cotizacion" {{ old('subject') == 'cotizacion' ? 'selected' : '' }}>
                                    Cotización a medida</option>
                                <option value="catalogo" {{ old('subject') == 'catalogo' ? 'selected' : '' }}>Duda sobre
                                    mueble del catálogo</option>
                                <option value="seguimiento" {{ old('subject') == 'seguimiento' ? 'selected' : '' }}>
                                    Seguimiento de mi pedido</option>
                                <option value="otro" {{ old('subject') == 'otro' ? 'selected' : '' }}>Otro asunto
                                </option>
                            </select>
                            @error('subject')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Mensaje</label>
                            <textarea id="message" name="message" rows="5" required
                                class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors placeholder-gray-400 resize-none"
                                placeholder="Cuéntanos los detalles de lo que tienes en mente..."></textarea>
                            @error('message')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" :disabled="sending"
                                :class="{ 'opacity-70 cursor-not-allowed': sending }"
                                class="w-full sm:w-auto inline-flex justify-center items-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-10 py-5 transition-colors duration-200 shadow-sm">
                                <span x-show="!sending">Enviar Mensaje</span>
                                <span x-show="sending" x-cloak class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Procesando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
