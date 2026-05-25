<x-app-layout>
    <x-slot:title>Catálogo - Carpintec</x-slot:title>

    @push('head')
        <meta name="description" content="Catálogo de muebles en México.">
        <meta name="robots" content="index, follow">
    @endpush

    <div class="bg-gray-50/30 min-h-screen pt-12 pb-24" x-data="{ 
        isFiltersOpen: false,
        init() {
            this.$watch('isFiltersOpen', value => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        }
    }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-end justify-between border-b border-gray-200 pb-6 mb-12">
                <h1 class="text-4xl font-serif font-bold tracking-tight text-gray-900">
                    Catálogo de muebles
                </h1>
                
                <button @click="isFiltersOpen = true" 
                        class="group flex items-center space-x-2 text-sm font-bold uppercase tracking-widest text-gray-900 hover:text-amber-800 transition-colors focus:outline-none">
                    <span>Filtrar y Explorar</span>
                    <div class="relative">
                        <svg class="h-5 w-5 text-gray-400 group-hover:text-amber-800 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg>
                        @if(request()->anyFilled(['search', 'min_price', 'max_price', 'category']))
                            <span class="absolute -top-1 -right-1 block h-2.5 w-2.5 rounded-full bg-amber-700 ring-2 ring-white"></span>
                        @endif
                    </div>
                </button>
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
                                    $coverMedia = $product->getFirstMedia('product_images');
                                    $coverImage = $product->mediaUrl($coverMedia) ?? asset('images/product-placeholder.svg');
                                @endphp

                                @if ($coverMedia)
                                    <img src="{{ $coverImage }}"
                                         alt="{{ $product->name }}"
                                         class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-gray-300">
                                        <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-500 pointer-events-none"></div>
                            </div>

                            <div class="pt-6 flex flex-col flex-grow text-center">
                                <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-2 relative z-20">
                                    {{ $product->category->name ?? 'Colección' }}
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-6 text-xl font-serif font-bold text-gray-900">No se encontraron piezas</h3>
                    <p class="mt-2 text-sm text-gray-500">Intenta ajustar los filtros o explorar una categoría diferente.</p>
                </div>
            @endif
        </div>

        <div class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" x-show="isFiltersOpen" x-cloak>
            
            <div class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm transition-opacity"
                 x-show="isFiltersOpen"
                 x-transition:enter="ease-in-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"></div>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        
                        <div class="pointer-events-auto w-screen max-w-md transform transition"
                             x-show="isFiltersOpen"
                             @click.away="isFiltersOpen = false"
                             x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:enter-start="translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="translate-x-full">
                            
                            <div class="flex h-full flex-col overflow-y-auto bg-white shadow-2xl">
                                
                                <div class="flex items-center justify-between px-8 py-6 border-b border-gray-100">
                                    <h2 class="text-2xl font-serif font-bold text-gray-900" id="slide-over-title">Explorar</h2>
                                    <button type="button" @click="isFiltersOpen = false" class="relative text-gray-400 hover:text-gray-900 transition-colors focus:outline-none">
                                        <span class="sr-only">Cerrar panel</span>
                                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <form method="GET" action="{{ route('catalog.index') }}" class="flex-1 flex flex-col">
                                    
                                    <div class="px-8 py-8 flex-1">
                                        
                                        <div class="mb-10">
                                            <label for="search" class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-4">Búsqueda Rápida</label>
                                            <input type="text" name="search" id="search" value="{{ $search ?? '' }}" 
                                                   placeholder="Sillón, roble, mesa..." 
                                                   class="block w-full rounded-none border-0 border-b-2 border-gray-200 focus:border-amber-800 focus:ring-0 sm:text-base transition-colors placeholder:text-gray-300 px-0 py-2">
                                        </div>

                                        <div class="mb-12">
                                            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-5">Categorías</h3>
                                            @if(request('category'))
                                                <input type="hidden" name="category" value="{{ request('category') }}">
                                            @endif
                                            
                                            <div class="space-y-5">
                                                <a href="{{ route('catalog.index') }}" 
                                                   class="block text-base transition-colors {{ !request('category') ? 'font-bold text-amber-800' : 'text-gray-500 hover:text-gray-900' }}">
                                                    Todas las colecciones
                                                </a>
                                                
                                                @foreach ($categories as $parent)
                                                    <div>
                                                        <a href="{{ route('catalog.index', ['category' => $parent->slug]) }}" 
                                                           class="block text-base transition-colors {{ request('category') == $parent->slug ? 'font-bold text-amber-800' : 'font-medium text-gray-900 hover:text-amber-800' }}">
                                                            {{ $parent->name }}
                                                        </a>
                                                        @if ($parent->children->count())
                                                            <div class="mt-3 pl-4 space-y-3 border-l-2 border-gray-100">
                                                                @foreach ($parent->children as $child)
                                                                    <a href="{{ route('catalog.index', ['category' => $child->slug]) }}" 
                                                                       class="block text-sm transition-colors {{ request('category') == $child->slug ? 'font-bold text-amber-800' : 'text-gray-500 hover:text-gray-900' }}">
                                                                        {{ $child->name }}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div>
                                            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-4">Rango de Precio</h3>
                                            <div class="grid grid-cols-2 gap-6">
                                                <div>
                                                    <label for="min_price" class="sr-only">Mínimo</label>
                                                    <input type="number" name="min_price" id="min_price" value="{{ $min ?? '' }}" step="0.01" min="0" 
                                                           placeholder="$ Mínimo" 
                                                           class="block w-full rounded-lg border-gray-200 focus:border-amber-800 focus:ring-amber-800 sm:text-sm transition-colors text-center">
                                                </div>
                                                <div>
                                                    <label for="max_price" class="sr-only">Máximo</label>
                                                    <input type="number" name="max_price" id="max_price" value="{{ $max ?? '' }}" step="0.01" min="0" 
                                                           placeholder="$ Máximo" 
                                                           class="block w-full rounded-lg border-gray-200 focus:border-amber-800 focus:ring-amber-800 sm:text-sm transition-colors text-center">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 space-y-3">
                                        <button type="submit" 
                                                class="w-full flex justify-center items-center rounded-xl bg-amber-900 px-6 py-4 text-sm font-bold tracking-wide text-white shadow-sm hover:bg-amber-800 hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-amber-800 focus:ring-offset-2">
                                            Aplicar Filtros
                                        </button>
                                        
                                        @if(request()->anyFilled(['search', 'min_price', 'max_price', 'category']))
                                            <a href="{{ route('catalog.index') }}" 
                                               class="w-full flex justify-center items-center rounded-xl border border-gray-200 bg-white px-6 py-4 text-sm font-bold tracking-wide text-gray-900 hover:bg-gray-100 transition-all duration-200">
                                                Limpiar Todo
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>