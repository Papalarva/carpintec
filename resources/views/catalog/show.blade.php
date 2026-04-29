<x-app-layout>
    <x-slot:title>{{ $product->name }} - Carpintec</x-slot:title>

    @push('head')
        <meta name="description" content="{{ Str::limit($product->short_description, 160) }}">
        <meta name="robots" content="index, follow">
    @endpush

    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Breadcrumb básico -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol role="list" class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('catalog.index') }}" class="hover:text-amber-700">Catálogo</a></li>
                @if($product->category->parent)
                    <li class="flex items-center">
                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        <a href="{{ route('catalog.index', ['category' => $product->category->parent->slug]) }}" class="ml-2 hover:text-amber-700">{{ $product->category->parent->name }}</a>
                    </li>
                @endif
                <li class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                    <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="ml-2 hover:text-amber-700">{{ $product->category->name }}</a>
                </li>
                <li class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                    <span class="ml-2 font-medium text-amber-800">{{ $product->name }}</span>
                </li>
            </ol>
        </nav>

        @php
            $productImages = $product->getMedia('product_images')->sortBy('order_column') ?? collect();
            $mainImage = $product->getFirstMedia('product_images')?->getUrl('webp')
                ?? ($productImages->isNotEmpty() ? $productImages->first()->getUrl('webp') : '');
        @endphp

        <!-- Galería de imágenes + Detalles -->
        <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-8">
            <!-- Galería -->
            <div x-data="{ mainImage: '{{ $mainImage }}' }" class="flex flex-col-reverse">
                <!-- Imagen principal -->
                <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-100">
                    <img :src="mainImage" alt="{{ $product->name }}" class="h-full w-full object-cover object-center">
                </div>

                <!-- Miniaturas (si hay más de una imagen) -->
                @if($productImages->count() > 1)
                    <div class="mx-auto mt-6 w-full max-w-2xl sm:block lg:max-w-none">
                        <div class="grid grid-cols-4 gap-6">
                            @foreach($productImages as $image)
                                <div @click="mainImage = '{{ $image->getUrl('webp') }}'"
                                     class="relative flex cursor-pointer items-center justify-center overflow-hidden rounded-md bg-gray-100 hover:ring-2 hover:ring-amber-500"
                                     :class="{ 'ring-2 ring-amber-700': mainImage === '{{ $image->getUrl('webp') }}' }">
                                    <img src="{{ $image->getUrl('webp') }}" alt="" class="h-full w-full object-cover object-center">
                                    <span class="sr-only">Ver imagen {{ $loop->index + 1 }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Información del producto -->
            <div class="mt-10 px-4 sm:mt-16 sm:px-0 lg:mt-0">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $product->name }}</h1>

                <!-- SKU y categoría -->
                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                    <span>SKU: {{ $product->sku }}</span>
                    <span>·</span>
                    <span>{{ $product->category->name }}</span>
                </div>

                <!-- Precio -->
                <div class="mt-4">
                    <p class="text-3xl tracking-tight text-gray-900">${{ number_format($product->price, 2) }} MXN</p>
                </div>

                <!-- Estado de inventario (si track_inventory) -->
                @if($product->track_inventory && $product->inventory)
                    @if($product->inventory->quantity > 0)
                        <p class="mt-2 text-sm text-green-600">
                            Disponible ({{ $product->inventory->quantity }} en stock)
                        </p>
                    @else
                        <p class="mt-2 text-sm text-red-600">
                            Agotado
                        </p>
                    @endif
                @endif

                <!-- Descripción corta -->
                <div class="mt-6">
                    <h3 class="sr-only">Descripción</h3>
                    <p class="text-base text-gray-700">{{ $product->short_description }}</p>
                </div>

                <!-- Detalles técnicos -->
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-900">Detalles</h3>
                    <dl class="mt-2 divide-y divide-gray-200 border-t border-b border-gray-200">
                        @if($product->materials)
                            <div class="flex justify-between py-2 text-sm">
                                <dt class="text-gray-500">Materiales</dt>
                                <dd class="text-gray-900">{{ $product->materials }}</dd>
                            </div>
                        @endif
                        @if($product->dimensions)
                            <div class="flex justify-between py-2 text-sm">
                                <dt class="text-gray-500">Dimensiones</dt>
                                <dd class="text-gray-900">{{ $product->dimensions }}</dd>
                            </div>
                        @endif
                        @if($product->weight_kg)
                            <div class="flex justify-between py-2 text-sm">
                                <dt class="text-gray-500">Peso</dt>
                                <dd class="text-gray-900">{{ $product->weight_kg }} kg</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Acciones: agregar al carrito o solicitar cotización -->
                <div class="mt-8">
                    @if($product->is_customizable)
                        <p class="mb-4 inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">
                            Producto personalizable
                        </p>
                        <a href="{{ route('quotation.request', $product->slug) }}"
                           class="flex w-full items-center justify-center rounded-md border border-transparent bg-amber-700 px-8 py-3 text-base font-medium text-white hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                            Solicitar cotización a medida
                        </a>
                    @else
                        <form action="{{ route('cart.add', $product->slug) }}" method="POST">                          
                            @csrf
                            <div class="flex items-center space-x-4 mb-4">
                                <label for="quantity" class="text-sm font-medium text-gray-700">Cantidad</label>
                                <select id="quantity" name="quantity"
                                        class="rounded-md border-gray-300 py-1.5 text-base focus:border-amber-500 focus:outline-none focus:ring-amber-500 sm:text-sm">
                                    @for($i = 1; $i <= min($product->inventory?->quantity ?: 10, 10); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <button type="submit"
                                    class="flex w-full items-center justify-center rounded-md border border-transparent bg-amber-800 px-8 py-3 text-base font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
                                    @if($product->track_inventory && $product->inventory && $product->inventory->quantity < 1) disabled @endif>
                                Agregar al carrito
                            </button>
                        </form>
                        @if($product->track_inventory && $product->inventory && $product->inventory->quantity < 1)
                            <p class="mt-2 text-sm text-red-600">Producto agotado temporalmente.</p>
                        @endif
                    @endif
                </div>

                <!-- Descripción larga (plegable) -->
                @if($product->long_description)
                    <div class="mt-10 border-t pt-6" x-data="{ expanded: false }">
                        <h3 class="text-sm font-medium text-gray-900 cursor-pointer flex items-center justify-between" @click="expanded = !expanded">
                            <span>Descripción extendida</span>
                            <svg :class="expanded ? 'rotate-180' : ''" class="h-5 w-5 transform transition-transform" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </h3>
                        <div x-show="expanded" x-cloak class="mt-3 text-sm text-gray-700 prose prose-sm max-w-none">
                            {!! nl2br(e($product->long_description)) !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Productos relacionados -->
        @if($related->count())
            <div class="mt-16">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">También te puede interesar</h2>
                <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    @foreach($related as $relatedProduct)
                        <div class="group relative">
                            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-100">
                                @if($relatedProduct->getFirstMedia('product_images'))
                                    <img src="{{ $relatedProduct->getFirstMedia('product_images')?->getUrl('webp') }}"
                                         alt="{{ $relatedProduct->name }}"
                                         class="h-full w-full object-cover object-center group-hover:opacity-75">
                                @else
                                    <div class="flex h-full items-center justify-center text-gray-400">
                                        <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4">
                                <h3 class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('catalog.show', $relatedProduct->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm font-medium text-gray-900">${{ number_format($relatedProduct->price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
</x-app-layout>