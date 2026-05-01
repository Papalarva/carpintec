<x-app-layout>
    <x-slot:title>Catálogo - Carpintec</x-slot:title>

    @push('head')
        <meta name="description" content="Catálogo de muebles premium en México.">
        <meta name="robots" content="index, follow">
    @endpush

    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-8">Catálogo de muebles</h1>

            <div class="lg:grid lg:grid-cols-4 lg:gap-x-8">
                <!-- Sidebar de categorías (escritorio) -->
                <aside class="hidden lg:block">
                    <h2 class="sr-only">Categorías</h2>
                    <div class="space-y-4">
                        @foreach ($categories as $parent)
                            <div>
                                <a href="{{ route('catalog.index', ['category' => $parent->slug]) }}"
                                   class="block text-sm font-semibold text-gray-900 hover:text-amber-700 {{ request('category') == $parent->slug ? 'text-amber-700' : '' }}">
                                    {{ $parent->name }}
                                </a>
                                @if ($parent->children->count())
                                    <ul class="mt-2 space-y-1 pl-4 border-l-2 border-gray-100">
                                        @foreach ($parent->children as $child)
                                            <li>
                                                <a href="{{ route('catalog.index', ['category' => $child->slug]) }}"
                                                   class="block text-sm text-gray-600 hover:text-amber-700 {{ request('category') == $child->slug ? 'text-amber-700 font-medium' : '' }}">
                                                    {{ $child->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </aside>

                <!-- Selector de categorías en móvil (Alpine.js) -->
                <div class="mb-6 lg:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        <span>
                            @if(request('category'))
                                {{ \App\Models\Category::where('slug', request('category'))->value('name') ?? 'Todas las categorías' }}
                            @else
                                Todas las categorías
                            @endif
                        </span>
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-full rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                        <div class="py-1">
                            <a href="{{ route('catalog.index') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ !request('category') ? 'font-medium' : '' }}">
                                Todas las categorías
                            </a>
                            @foreach ($categories as $parent)
                                <a href="{{ route('catalog.index', ['category' => $parent->slug]) }}"
                                   class="block px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-100 {{ request('category') == $parent->slug ? 'text-amber-700' : '' }}">
                                    {{ $parent->name }}
                                </a>
                                @foreach ($parent->children as $child)
                                    <a href="{{ route('catalog.index', ['category' => $child->slug]) }}"
                                       class="block pl-8 pr-4 py-2 text-sm text-gray-600 hover:bg-gray-100 {{ request('category') == $child->slug ? 'text-amber-700 font-medium' : '' }}">
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Área principal: filtros y productos -->
                <div class="lg:col-span-3">
                    <!-- Formulario de filtros -->
                    <form method="GET" action="{{ route('catalog.index') }}" class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                            <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                                   placeholder="Nombre, descripción..."
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700">Precio mínimo</label>
                            <input type="number" name="min_price" id="min_price" value="{{ $min ?? '' }}" step="0.01" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700">Precio máximo</label>
                            <input type="number" name="max_price" id="max_price" value="{{ $max ?? '' }}" step="0.01" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                        </div>
                        <div class="sm:col-span-3 flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center rounded-md bg-amber-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                Filtrar
                            </button>
                            @if(request()->anyFilled(['search', 'min_price', 'max_price', 'category']))
                                <a href="{{ route('catalog.index') }}"
                                   class="ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                    Limpiar filtros
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Listado de productos -->
                    @if ($products->count())
                        <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                            @foreach ($products as $product)
                                <div class="group relative rounded-lg border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow">
                                    
                                    <!-- NUEVO: Badge de Agotado -->
                                    @php $isOutOfStock = $product->track_inventory && ($product->inventory?->quantity ?? 0) < 1; @endphp
                                    @if($isOutOfStock)
                                        <div class="absolute top-2 right-2 z-10 rounded-md bg-red-600 px-2.5 py-1 text-xs font-bold text-white shadow-sm pointer-events-none">
                                            Agotado
                                        </div>
                                    @endif

                                    @if ($product->getFirstMedia('product_images'))
                                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-t-lg bg-gray-100">
                                            <img src="{{ $product->getFirstMedia('product_images')->getUrl('webp') }}"
                                                 alt="{{ $product->name }}"
                                                 class="h-full w-full object-cover object-center group-hover:opacity-75">
                                        </div>
                                    @else
                                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-t-lg bg-gray-200 flex items-center justify-center text-gray-400">
                                            <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="p-4">
                                        <div class="text-xs text-amber-700 font-medium uppercase tracking-wide">{{ $product->category->name ?? '' }}</div>
                                        <a href="{{ route('catalog.show', $product->slug) }}" class="mt-1 block">
                                            <h3 class="text-sm font-medium text-gray-900">{{ $product->name }}</h3>
                                        </a>
                                        <p class="mt-2 text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }} MXN</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron productos</h3>
                            <p class="mt-1 text-sm text-gray-500">Intenta ajustar los filtros o categoría.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>