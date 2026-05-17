<x-app-layout>
    <x-slot:title>Colecciones - Carpintec</x-slot:title>

    @push('head')
        <meta name="description" content="Nuestras colecciones curadas de muebles.">
        <meta name="robots" content="index, follow">
    @endpush

    <div class="bg-gray-50/30 min-h-screen pt-12 pb-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col border-b border-gray-200 pb-6 mb-12">
                <h1 class="text-4xl font-serif font-bold tracking-tight text-gray-900">
                    Nuestras Colecciones
                </h1>
                <p class="font-sans text-gray-500 mt-3 text-lg">
                    Curaduría artesanal de muebles con alma de madera.
                </p>
            </div>

            @if ($collections->count())
                <div class="grid grid-cols-1 gap-y-16 gap-x-12 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($collections as $collection)
                        <div class="group relative flex flex-col h-full rounded-2xl bg-transparent transition-all duration-300 cursor-pointer">
                            
                            @php
                                // Mantenemos la solución de Spatie que aplicamos antes
                                $mediaUrl = $collection->products->first()?->getFirstMediaUrl('product_images');
                                $coverImage = $mediaUrl ?: asset('images/placeholder-collection.jpg');
                            @endphp

                            <div class="aspect-square w-full overflow-hidden rounded-2xl bg-gray-100 relative shadow-sm group-hover:shadow-xl transition-shadow duration-500">
                                <img src="{{ $coverImage }}" 
                                     alt="{{ $collection->name }}" 
                                     class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out">
                                
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-500 pointer-events-none"></div>
                            </div>

                            <div class="pt-6 flex flex-col flex-grow text-center">
                                <div class="text-[10px] text-amber-700 font-bold uppercase tracking-widest mb-2 relative z-20">
                                    {{ $collection->products->count() }} Piezas
                                </div>
                                <h3 class="text-2xl font-serif font-bold text-gray-900 leading-snug group-hover:text-amber-800 transition-colors mb-2 flex-grow">
                                    <a href="{{ route('collections.show', $collection->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0 z-10"></span>
                                        {{ $collection->name }}
                                    </a>
                                </h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-32 bg-transparent">
                    <svg class="mx-auto h-20 w-20 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-6 text-xl font-serif font-bold text-gray-900">Sin colecciones activas</h3>
                    <p class="mt-2 text-sm text-gray-500">Estamos preparando nuevas piezas para esta sección.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>