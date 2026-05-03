<x-app-layout>
    <x-slot:title>{{ $product->name }} - Carpintec</x-slot:title>

    @push('head')
        <meta name="description" content="{{ Str::limit($product->short_description, 160) }}">
        <meta name="robots" content="index, follow">
    @endpush

    <!-- Fondo súper limpio, sin cajas que encierren el contenido -->
    <div class="bg-gray-50/30 min-h-screen pb-24">
        
        <!-- Breadcrumb Full Width (Minimalista) -->
        <div class="border-b border-gray-200 bg-white/50 backdrop-blur-md sticky top-20 z-30">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol role="list" class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        <li>
                            <a href="{{ route('catalog.index') }}" class="hover:text-amber-800 transition-colors">Catálogo</a>
                        </li>
                        @if ($product->category->parent)
                            <li class="flex items-center">
                                <span class="mx-2 text-gray-300">/</span>
                                <a href="{{ route('catalog.index', ['category' => $product->category->parent->slug]) }}"
                                    class="hover:text-amber-800 transition-colors">{{ $product->category->parent->name }}</a>
                            </li>
                        @endif
                        <li class="flex items-center">
                            <span class="mx-2 text-gray-300">/</span>
                            <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}"
                                class="hover:text-amber-800 transition-colors">{{ $product->category->name }}</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        @php
            $productImages = $product->getMedia('product_images')->sortBy('order_column') ?? collect();
            $mainImage =
                $product->getFirstMedia('product_images')?->getUrl('webp') ??
                ($productImages->isNotEmpty() ? $productImages->first()->getUrl('webp') : '');
        @endphp

        <!-- Contenedor Principal Editorial (Split Screen) -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-12">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-16 xl:gap-x-24 items-start">
                
                <!-- COLUMNA IZQUIERDA: Galería (STICKY) -->
                <div class="lg:col-span-7 lg:sticky lg:top-40 mb-12 lg:mb-0" x-data="{ mainImage: '{{ $mainImage }}' }">
                    
                    <!-- Imagen Principal (Aspect Square inmenso) -->
                    <div class="aspect-square w-full overflow-hidden rounded-2xl bg-gray-100 shadow-sm relative group">
                        @if($mainImage)
                            <img :src="mainImage" alt="{{ $product->name }}"
                                class="h-full w-full object-cover object-center transition-transform duration-1000 ease-out group-hover:scale-105">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-gray-300">
                                <svg class="h-32 w-32" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="0.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Miniaturas debajo de la foto principal -->
                    @if ($productImages->count() > 1)
                        <div class="mt-6 flex space-x-4 overflow-x-auto pb-2 scrollbar-hide">
                            @foreach ($productImages as $image)
                                <div @click="mainImage = '{{ $image->getUrl('webp') }}'"
                                    class="h-24 w-24 flex-shrink-0 cursor-pointer overflow-hidden rounded-xl bg-gray-50 border-2 transition-all duration-300 hover:opacity-80"
                                    :class="mainImage === '{{ $image->getUrl('webp') }}' ? 'border-amber-800 shadow-md opacity-100' : 'border-transparent opacity-60'">
                                    <img src="{{ $image->getUrl('webp') }}" alt="Miniatura" class="h-full w-full object-cover object-center">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- COLUMNA DERECHA: Información y Compra -->
                <div class="lg:col-span-5 flex flex-col pt-2 lg:pt-8">
                    
                    <div class="mb-4">
                        <span class="text-[11px] font-bold text-amber-700 uppercase tracking-widest">{{ $product->category->name }}</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl font-serif font-bold tracking-tight text-gray-900 mb-4 leading-tight">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-center space-x-4 text-xs font-medium text-gray-400 mb-8 font-sans">
                        <span>SKU: {{ $product->sku }}</span>
                    </div>

                    <div class="pb-8 border-b border-gray-200 mb-8">
                        <p class="text-3xl font-sans font-medium text-gray-900">
                            ${{ number_format($product->price, 2) }} <span class="text-base text-gray-500 font-normal">MXN</span>
                        </p>
                    </div>

                    <!-- Descripción Corta -->
                    <div class="mb-10 text-base leading-relaxed text-gray-600 font-sans">
                        {{ $product->short_description }}
                    </div>

                    <!-- Lógica PHP del Carrito (Intacta) -->
                    @php
                        $cartManager = app(\App\Services\CartManager::class);
                        $qtyInCartNode = collect($cartManager->getItems())->first(function ($item) use ($product) {
                            $itemId = is_object($item)
                                ? $item->product_id ?? ($item->product?->id ?? null)
                                : $item['product_id'] ?? ($item['id'] ?? null);
                            return (string) $itemId === (string) $product->id;
                        });
                        $qtyInCart = $qtyInCartNode ? (int) (is_object($qtyInCartNode) ? $qtyInCartNode->quantity : $qtyInCartNode['quantity']) : 0;
                        $stockReal = $product->inventory?->quantity ?? 0;
                        $disponibleParaAgregar = $product->track_inventory ? max(0, $stockReal - $qtyInCart) : 999;
                        $isOutOfStock = $product->track_inventory && $stockReal < 1;
                        $maxQty = $product->track_inventory ? min($disponibleParaAgregar, 10) : 10;
                    @endphp

                    <!-- Formulario de Compra -->
                    <div class="mb-12" x-data="cartComponent({{ $disponibleParaAgregar }})">
                        <form x-ref="cartForm" @submit.prevent="submitForm()" action="{{ route('cart.add', $product->slug) }}" method="POST">
                            @csrf

                            <!-- Inventario -->
                            <div class="mb-5 flex items-center justify-between">
                                @if ($product->track_inventory && $product->inventory)
                                    @if ($product->inventory->quantity > 0)
                                        <div class="flex items-center text-sm text-emerald-700 font-medium tracking-wide">
                                            <div class="h-2 w-2 rounded-full bg-emerald-500 mr-3 animate-pulse"></div>
                                            Disponible ({{ $product->inventory->quantity }})
                                        </div>
                                    @else
                                        <div class="flex items-center text-sm text-red-700 font-medium tracking-wide">
                                            <div class="h-2 w-2 rounded-full bg-red-500 mr-3"></div>
                                            Agotado
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <div class="flex items-center space-x-4 mb-8">
                                <div class="w-28">
                                    <label for="quantity" class="sr-only">Cantidad</label>
                                    <select id="quantity" name="quantity" x-ref="qtySelect"
                                        class="block w-full rounded-xl border-gray-200 py-3.5 text-center text-base focus:border-amber-800 focus:ring-amber-800 disabled:bg-gray-50 disabled:text-gray-400 font-medium transition-colors"
                                        :disabled="disponible < 1" @if ($isOutOfStock || $disponibleParaAgregar < 1) disabled @endif>
                                        @if ($isOutOfStock || $disponibleParaAgregar < 1)
                                            <option value="0">0</option>
                                        @else
                                            @for ($i = 1; $i <= $maxQty; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        @endif
                                    </select>
                                </div>
                                <div class="flex-1">
                                    @if (!$isOutOfStock)
                                        <span x-show="disponible < 1" x-cloak class="text-sm font-bold text-amber-700">Límite de stock</span>
                                        @if ($product->track_inventory && $qtyInCart > 0)
                                            <span x-show="disponible > 0" class="text-xs font-medium text-gray-500">En carrito: {{ $qtyInCart }}</span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex flex-col gap-4">
                                <button type="submit"
                                    class="w-full flex justify-center items-center rounded-xl border border-transparent bg-amber-900 px-8 py-4 text-sm font-bold tracking-widest uppercase text-white shadow-sm hover:bg-amber-800 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-800 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="loading || disponible < 1"
                                    @if ($isOutOfStock || $disponibleParaAgregar < 1) disabled @endif>

                                    <span x-show="!loading" x-text="disponible < 1 ? 'Agotado' : 'Añadir al carrito'"></span>
                                    <span x-show="loading" class="flex items-center justify-center">
                                        <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        Procesando...
                                    </span>
                                </button>

                                <a href="{{ route('quotation.request', ['product' => $product->slug, 'subject' => $product->name]) }}"
                                    class="w-full flex justify-center items-center rounded-xl border border-gray-300 bg-transparent px-8 py-4 text-sm font-bold tracking-widest uppercase text-gray-900 hover:border-gray-900 hover:bg-gray-50 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 text-center">
                                    Proyecto a medida
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Acordeones de Información (Especificaciones y Descripción Larga) -->
                    <div class="border-t border-gray-200 divide-y divide-gray-200">
                        
                        <!-- Acordeón: Especificaciones -->
                        @if ($product->materials || $product->dimensions || $product->weight_kg)
                        <div x-data="{ expanded: false }" class="py-6">
                            <button @click="expanded = !expanded" class="w-full flex items-center justify-between focus:outline-none group">
                                <span class="text-sm font-bold uppercase tracking-widest text-gray-900 group-hover:text-amber-800 transition-colors">Especificaciones Técnicas</span>
                                <svg :class="expanded ? 'rotate-180' : ''" class="h-5 w-5 text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="expanded" x-collapse x-cloak class="mt-6">
                                <dl class="space-y-4 text-sm font-sans">
                                    @if ($product->materials)
                                        <div class="grid grid-cols-3 gap-4">
                                            <dt class="text-gray-500">Materiales</dt>
                                            <dd class="text-gray-900 col-span-2">{{ $product->materials }}</dd>
                                        </div>
                                    @endif
                                    @if ($product->dimensions)
                                        <div class="grid grid-cols-3 gap-4">
                                            <dt class="text-gray-500">Dimensiones</dt>
                                            <dd class="text-gray-900 col-span-2">{{ $product->dimensions }}</dd>
                                        </div>
                                    @endif
                                    @if ($product->weight_kg)
                                        <div class="grid grid-cols-3 gap-4">
                                            <dt class="text-gray-500">Peso</dt>
                                            <dd class="text-gray-900 col-span-2">{{ $product->weight_kg }} kg</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        </div>
                        @endif

                        <!-- Acordeón: Historia / Descripción Larga -->
                        @if ($product->long_description)
                        <div x-data="{ expanded: true }" class="py-6">
                            <button @click="expanded = !expanded" class="w-full flex items-center justify-between focus:outline-none group">
                                <span class="text-sm font-bold uppercase tracking-widest text-gray-900 group-hover:text-amber-800 transition-colors">Historia de la pieza</span>
                                <svg :class="expanded ? 'rotate-180' : ''" class="h-5 w-5 text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="expanded" x-collapse x-cloak class="mt-6">
                                <div class="text-sm text-gray-600 leading-loose prose prose-sm max-w-none font-sans">
                                    {!! nl2br(e($product->long_description)) !!}
                                </div>
                            </div>
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Relacionados -->
        @if ($related->count())
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-32 border-t border-gray-200 pt-20">
                <div class="mb-12 text-center">
                    <h2 class="text-3xl font-serif font-bold tracking-tight text-gray-900">Colección Relacionada</h2>
                </div>
                
                <div class="grid grid-cols-1 gap-y-16 gap-x-8 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($related as $relatedProduct)
                        <div class="group relative flex flex-col h-full bg-transparent transition-all duration-300 cursor-pointer">
                            <div class="aspect-[4/5] w-full overflow-hidden rounded-2xl bg-gray-100 relative shadow-sm group-hover:shadow-xl transition-shadow duration-500">
                                @if ($relatedProduct->getFirstMedia('product_images'))
                                    <img src="{{ $relatedProduct->getFirstMedia('product_images')?->getUrl('webp') }}"
                                        alt="{{ $relatedProduct->name }}"
                                        class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-gray-300">
                                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-500 pointer-events-none"></div>
                            </div>
                            
                            <div class="pt-6 flex flex-col flex-grow text-center">
                                <h3 class="text-lg font-serif font-bold text-gray-900 leading-snug group-hover:text-amber-800 transition-colors mb-2 flex-grow">
                                    <a href="{{ route('catalog.show', $relatedProduct->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0 z-10"></span>
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h3>
                                <p class="text-base font-medium text-gray-900 font-sans">${{ number_format($relatedProduct->price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- El mismo Script intacto para el Carrito Alpine.js -->
    <script>
        function cartComponent(initialStock = 999) {
            return {
                loading: false,
                disponible: initialStock,

                async submitForm() {
                    const form = this.$refs.cartForm;
                    const formData = new FormData(form);
                    this.loading = true;

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        if (!response.ok) {
                            const data = await response.json().catch(() => ({}));
                            throw new Error(data.message || 'Error al agregar al carrito');
                        }

                        const data = await response.json();
                        const addedQty = parseInt(formData.get('quantity'));
                        this.disponible -= addedQty;

                        if (this.disponible < 1) {
                            this.$refs.qtySelect.innerHTML = '<option value="0">0</option>';
                        } else {
                            const maxOpciones = Math.min(this.disponible, 10);
                            let optionsHtml = '';
                            for (let i = 1; i <= maxOpciones; i++) {
                                optionsHtml += `<option value="${i}">${i}</option>`;
                            }
                            this.$refs.qtySelect.innerHTML = optionsHtml;
                        }

                        window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
                        this.showToast('¡Agregado al carrito exitosamente!', false);

                    } catch (e) {
                        this.showToast(e.message, true);
                    } finally {
                        this.loading = false;
                    }
                },

                showToast(mensaje, isError = false) {
                    const toast = document.getElementById('toast');
                    const msg = document.getElementById('toast-message');
                    if (!toast || !msg) return;

                    msg.textContent = mensaje;
                    toast.classList.remove('hidden', 'bg-green-600', 'bg-red-600');
                    if (isError) {
                        toast.classList.add('bg-red-600');
                    } else {
                        toast.classList.add('bg-green-600');
                    }
                    toast.style.opacity = '1';
                    const timeVisible = isError ? 4500 : 2500;

                    setTimeout(() => { toast.style.opacity = '0'; }, timeVisible);
                }
            }
        }
    </script>
</x-app-layout>
