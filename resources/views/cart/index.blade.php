<x-app-layout>
    <x-slot:title>Carrito de compras</x-slot:title>

    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8" 
         x-data="cartPage({{ $subtotal ?? 0 }}, {{ $items->sum('quantity') ?? 0 }})">
        
        <h1 class="text-3xl font-bold text-gray-900 mb-8 tracking-tight">Carrito de compras</h1>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <ul class="divide-y divide-gray-100" role="list">
                @forelse($items as $item)
                    @php
                        $product = $item->product;
                        // TIP: Mueve esta resolución de imagen a un accesor en tu modelo Product
                        $image = $product->getFirstMediaUrl('product_images', 'webp') ?: asset('images/placeholder.jpg');
                        $price = $product->price ?? 0;
                    @endphp
                    
                    <li class="p-6 flex flex-col sm:flex-row sm:items-center transition-opacity duration-300"
                        x-data="cartItem('{{ $product->slug }}', {{ $item->quantity }}, {{ $price }})"
                        x-init="updateLocalSubtotal()"
                        :class="{ 'opacity-50 pointer-events-none': loading }"
                    >
                        <div class="flex-shrink-0 w-24 h-24 sm:w-28 sm:h-28 bg-gray-50 rounded-lg overflow-hidden border border-gray-100">
                            <img src="{{ $image }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-center" loading="lazy">
                        </div>

                        <div class="mt-4 sm:mt-0 sm:ml-6 flex-1 flex flex-col sm:flex-row sm:justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <a href="{{ route('catalog.show', $product->slug) }}" class="hover:text-indigo-600 focus:outline-none focus:underline">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 font-mono">SKU: {{ $product->sku }}</p>
                            </div>

                            <div class="mt-4 sm:mt-0 flex flex-col sm:items-end justify-between">
                                <div class="text-left sm:text-right mb-4">
                                    <p class="text-lg font-bold text-gray-900" x-text="formatMoney(localSubtotal)"></p>
                                    <p class="text-sm text-gray-500">${{ number_format($price, 2) }} c/u</p>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center border border-gray-300 rounded-lg bg-white shadow-sm">
                                        <button @click="decrement()" 
                                                aria-label="Disminuir cantidad"
                                                class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-l-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                :disabled="qty <= 1 || loading">
                                            <span class="text-xl leading-none">−</span>
                                        </button>
                                        
                                        <input type="number" 
                                               x-model.number="qty" 
                                               min="1" 
                                               max="99"
                                               aria-label="Cantidad del producto"
                                               class="w-12 h-10 text-center border-0 border-x border-gray-300 p-0 text-sm font-medium focus:ring-0"
                                               @change="handleManualInput()"
                                               :disabled="loading">
                                        
                                        <button @click="increment()" 
                                                aria-label="Aumentar cantidad"
                                                class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-r-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                :disabled="qty >= 99 || loading">
                                            <span class="text-xl leading-none">+</span>
                                        </button>
                                    </div>

                                    <form action="{{ route('cart.remove', $product->slug) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                aria-label="Eliminar {{ $product->name }} del carrito"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none transition-all"
                                                @click="removeItem($event)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <p x-show="errorMessage" x-text="errorMessage" class="text-red-500 text-xs mt-2 font-medium"></p>
                            </div>
                        </div>
                    </li>
                @empty
                    <div class="text-center py-16 px-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-50 mb-6">
                            <svg class="h-10 w-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Tu carrito está vacío</h2>
                        <p class="mt-2 text-gray-500 max-w-sm mx-auto">Parece que aún no has decidido qué llevarte. Descubre nuestros muebles de temporada.</p>
                        <a href="{{ route('catalog.index') }}" class="mt-8 inline-flex items-center justify-center bg-indigo-600 text-white px-8 py-3.5 text-sm font-semibold rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                            Explorar el catálogo
                        </a>
                    </div>
                @endforelse
            </ul>
        </div>

        @if($items->isNotEmpty())
            <div class="mt-8 bg-gray-50 rounded-xl p-6 sm:p-8 border border-gray-200">
                <div class="flex items-center justify-between text-base text-gray-900 mb-4">
                    <p class="font-medium text-gray-600">Subtotal (<span x-text="totalItems"></span> artículos)</p>
                    <p class="font-bold text-xl" x-text="formatMoney(cartSubtotal)"></p>
                </div>
                <p class="text-sm text-gray-500 border-t border-gray-200 pt-4">Los impuestos y gastos de envío se calcularán en el siguiente paso.</p>

                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-4 gap-y-3 sm:gap-y-0">
                    <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3.5 border border-gray-300 text-sm font-medium text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        Seguir comprando
                    </a>
                    <a href="{{ route('checkout.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-sm font-bold text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        Ir al Checkout
                        <svg class="ml-2 -mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Formateador de moneda reutilizable
        const formatMoney = (amount) => {
            return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(amount);
        };

        // Estado global de la página
        function cartPage(initialSubtotal = 0, initialItems = 0) {
            return {
                cartSubtotal: initialSubtotal,
                totalItems: initialItems,
            }
        }

        // Estado local por producto
        function cartItem(slug, initialQty, unitPrice) {
            return {
                qty: initialQty,
                unitPrice: unitPrice,
                localSubtotal: initialQty * unitPrice,
                loading: false,
                timeout: null,
                errorMessage: '',

                updateLocalSubtotal() {
                    this.localSubtotal = this.qty * this.unitPrice;
                },

                handleManualInput() {
                    if (this.qty < 1) this.qty = 1;
                    if (this.qty > 99) this.qty = 99;
                    this.updateLocalSubtotal();
                    this.scheduleUpdate();
                },

                scheduleUpdate() {
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        this.updateCart();
                    }, 400); // 400ms reduce la carga en el servidor
                },

                async updateCart() {
                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const resp = await fetch(`/carrito/${slug}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ quantity: this.qty })
                        });

                        if (!resp.ok) throw new Error('Error de conexión');

                        const data = await resp.json();
                        
                        // Actualizamos el estado global usando el "magical property" de Alpine
                        if (data.subtotal !== undefined) {
                            this.$data.cartSubtotal = parseFloat(data.subtotal);
                        }
                        if (data.totalItems !== undefined) {
                            this.$data.totalItems = parseInt(data.totalItems);
                        }
                    } catch (e) {
                        this.errorMessage = 'No se pudo actualizar';
                        // Revertimos la UI si el backend falla
                        setTimeout(() => location.reload(), 1500);
                    } finally {
                        this.loading = false;
                    }
                },

                increment() {
                    if (this.qty < 99) {
                        this.qty++;
                        this.updateLocalSubtotal();
                        this.scheduleUpdate();
                    }
                },

                decrement() {
                    if (this.qty > 1) {
                        this.qty--;
                        this.updateLocalSubtotal();
                        this.scheduleUpdate();
                    }
                },

                removeItem(event) {
                    if (!confirm('¿Estás seguro de eliminar este mueble del carrito?')) {
                        event.preventDefault();
                    }
                }
            }
        }
    </script>
</x-app-layout>