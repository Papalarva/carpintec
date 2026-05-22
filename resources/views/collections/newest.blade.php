<x-app-layout>
    <x-slot:title>Novedades - Carpintec</x-slot:title>

    <div class="bg-gray-50/30 min-h-screen pt-12 pb-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-16 text-center">
                <span class="text-[10px] font-bold uppercase tracking-widest text-amber-700">Lanzamientos
                    Recientes</span>
                <h1 class="mt-4 font-serif text-5xl text-gray-900">Nuevas Colecciones</h1>
                <p class="mt-6 mx-auto max-w-2xl font-sans text-gray-500 leading-relaxed">
                    Explora nuestras visiones más recientes en ebanistería. Piezas diseñadas para transformar espacios
                    con la calidez eterna de la madera de alta calidad.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-20">
                @foreach ($collections as $collection)
                    <div
                        class="group relative flex flex-col h-full rounded-2xl bg-transparent transition-all duration-300 cursor-pointer">

                        <div
                            class="aspect-square w-full overflow-hidden rounded-2xl bg-gray-100 relative shadow-sm group-hover:shadow-xl transition-shadow duration-500">
                            @php
                                // Extraemos la imagen del PRIMER producto de la colección, igual que en el index
                                $mediaUrl = $collection->products->first()?->getFirstMediaUrl('product_images');
                                $coverImage = $mediaUrl ?: asset('images/placeholder-collection.jpg');
                            @endphp

                            <img src="{{ $coverImage }}" alt="{{ $collection->name }}"
                                class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out">

                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-500 pointer-events-none">
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col flex-grow text-center">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                                {{ $collection->products()->count() }} Piezas Disponibles
                            </span>

                            <h2 class="font-serif text-2xl text-gray-900 group-hover:text-amber-800 transition-colors">
                                <a href="{{ route('collections.show', $collection->slug) }}">
                                    <span aria-hidden="true" class="absolute inset-0 z-10"></span>
                                    {{ $collection->name }}
                                </a>
                            </h2>

                            <p class="mt-3 font-sans text-sm text-gray-500 line-clamp-2">
                                {{ $collection->description }}
                            </p>

                            <div
                                class="mt-4 flex justify-center items-center gap-2 text-amber-800 font-sans text-xs font-bold uppercase tracking-widest">
                                Explorar Colección
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="w-4 h-4 transition-transform group-hover:translate-x-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-32 text-center border-t border-gray-100 pt-16">
                <h3 class="font-serif text-3xl text-gray-900">¿Buscas algo a medida?</h3>
                <p class="mt-4 font-sans text-gray-500">Nuestros artesanos pueden dar vida a tus ideas únicas.</p>
                
                <a href="{{ route('quotations.index') }}"
                   class="mt-8 inline-block bg-amber-800 text-white px-8 py-4 rounded-lg font-sans text-sm font-bold uppercase tracking-widest hover:bg-amber-900 transition shadow-sm">
                    Solicitar Cotización Personalizada
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
