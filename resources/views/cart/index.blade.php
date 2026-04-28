<x-app-layout>
    <x-slot:title>Carrito de compras</x-slot:title>

    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Carrito de compras</h1>

        @if($items->isEmpty())
            <div class="text-center bg-white p-12 rounded-lg shadow-sm">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                <h2 class="mt-4 text-lg font-medium text-gray-900">Tu carrito está vacío</h2>
                <p class="mt-2 text-gray-500">Explora nuestro catálogo y agrega productos.</p>
                <a href="{{ route('catalog.index') }}" class="mt-6 inline-block bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition">
                    Ver catálogo
                </a>
            </div>
        @else
            <!-- Listado de ítems -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <ul class="divide-y divide-gray-200">
                    @foreach($items as $item)
                        @php
                            $product = $item->product;
                            $cartProductId = $product->id ?? $item->product_id; // según origen
                            $quantity = $item->quantity;
                            $price = $product->price ?? 0;
                            $image = $product->getFirstMedia('product_images')?->getUrl('webp') ?? asset('images/placeholder.jpg');
                        @endphp
                        <li class="p-6 flex flex-col sm:flex-row sm:items-center" x-data="{ qty: {{ $quantity }} }">
                            <!-- Imagen -->
                            <div class="flex-shrink-0 w-24 h-24 bg-gray-200 rounded-md overflow-hidden">
                                <img src="{{ $image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </div>
                            <!-- Info y controles -->
                            <div class="mt-4 sm:mt-0 sm:ml-6 flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">
                                            <a href="{{ route('catalog.show', $product->slug) }}" class="hover:text-indigo-600">
                                                {{ $product->name }}
                                            </a>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">${{ number_format($price, 2) }}</p>
                                        <p class="text-sm text-gray-500">c/u</p>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <!-- Control de cantidad -->
                                    <div class="flex items-center border border-gray-300 rounded-md">
                                        <button @click="if(qty>1) { qty--; $refs.qtyInput.value = qty; }" 
                                                class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition">−</button>
                                        <input type="number" x-ref="qtyInput" x-model="qty" min="1" max="99"
                                               class="w-16 text-center border-x border-gray-300 py-1 text-sm"
                                               form="update-form-{{ $cartProductId }}"
                                               name="quantity">
                                        <button @click="if(qty<99) { qty++; $refs.qtyInput.value = qty; }" 
                                                class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition">+</button>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <p class="text-sm font-medium text-gray-900">
                                            Subtotal: ${{ number_format($price * $quantity, 2) }}
                                        </p>
                                        <!-- Formulario para actualizar cantidad -->
                                        <form action="{{ route('cart.update', $product->slug) }}" method="POST" id="update-form-{{ $cartProductId }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900">Actualizar</button>
                                        </form>
                                        <!-- Eliminar -->
                                        <form action="{{ route('cart.remove', $product->slug) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900"
                                                    onclick="return confirm('¿Eliminar este producto del carrito?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Resumen -->
            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <div class="flex justify-between text-lg font-medium text-gray-900">
                    <span>Subtotal ({{ $items->sum('quantity') }} productos)</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <p class="text-sm text-gray-500 mt-2">El costo de envío y descuentos se calcularán en el checkout.</p>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('catalog.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">
                        Seguir comprando
                    </a>
                    <a href="{{ route('checkout.index') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        Proceder al checkout
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>