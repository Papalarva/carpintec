<x-app-layout>
    <x-slot:title>{{ $collection->name }} - Carpintec</x-slot:title>

    <div class="bg-gray-50/30 min-h-screen pt-12 pb-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col border-b border-gray-200 pb-6 mb-12">
                <nav class="flex mb-4 font-sans text-xs uppercase tracking-widest text-gray-400">
                    <a href="{{ route('home') }}" class="hover:text-amber-800 transition-colors">Inicio</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('collections.index') }}" class="hover:text-amber-800 transition-colors">Colecciones</a>
                </nav>
                
                <h1 class="text-4xl font-serif font-bold tracking-tight text-gray-900">
                    {{ $collection->name }}
                </h1>
                
                @if($collection->description)
                    <p class="font-sans text-gray-500 mt-4 max-w-2xl text-lg">
                        {{ $collection->description }}
                    </p>
                @endif
            </div>

            @if ($products->count())
                <div class="grid grid-cols-1 gap-y-16 gap-x-12 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($products as $product)
                        <div class="group relative flex flex-col h-full rounded-2xl bg-transparent transition-all duration-300 cursor-pointer">
                            
                            @php $isOutOfStock = $product->track_inventory && ($product->inventory?->quantity ?? 0) < 1; @endphp
                            @if($isOutOfStock)
                                <div class="absolute top-4 right-4 z-20 rounded bg-red-900/90 backdrop-blur-md px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-white shadow-sm pointer-events-none">
                                    Agotado
                                </div>
                            @endif

                            <div class="aspect-square w-full overflow-hidden rounded-2xl bg-gray-100 relative shadow-sm group-hover:shadow-xl transition-shadow duration-500">
                                @php
                                    // Extracción directa de URL con Spatie
                                    $productImageUrl = $product->mediaUrl($product->getFirstMedia('product_images')) ?: asset('images/product-placeholder.svg');
                                @endphp
                                
                                <img src="{{ $productImageUrl }}"
                                     alt="{{ $product->name }}"
                                     class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out">
                                
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-500 pointer-events-none"></div>
                            </div>

                            <div class="pt-6 flex flex-col flex-grow text-center">
                                <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-2 relative z-20">
                                    {{ $product->category->name ?? 'Colección ' . $collection->name }}
                                </div>
                                <h3 class="text-xl font-serif font-bold text-gray-900 leading-snug group-hover:text-amber-800 transition-colors mb-2 flex-grow">
                                    <a href="{{ route('catalog.show', $product->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0 z-10"></span>
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="text-lg font-medium text-gray-900 relative z-20">
                                    ${{ number_format($product->price, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-20 border-t border-gray-200 pt-10">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-32 bg-transparent">
                    <svg class="mx-auto h-20 w-20 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="mt-6 text-xl font-serif font-bold text-gray-900">Colección en preparación</h3>
                    <p class="mt-2 text-sm text-gray-500">Aún no hay piezas asignadas a esta colección exclusiva.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>