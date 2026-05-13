<x-app-layout>
    <x-slot:title>Checkout</x-slot:title>

    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Finalizar compra</h1>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Columna izquierda: pasos -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Paso 1: Dirección -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">1. Dirección de envío</h2>
                        @if($addresses->count())
                            <fieldset>
                                <div class="space-y-3">
                                    @foreach($addresses as $address)
                                        <label class="flex items-start border rounded-md p-3 cursor-pointer hover:bg-gray-50 {{ old('address_id') == $address->id ? 'ring-2 ring-indigo-500' : '' }}">
                                            <input type="radio" name="address_id" value="{{ $address->id }}" {{ old('address_id', auth()->user()->customer->addresses()->where('is_primary', true)->value('id')) == $address->id ? 'checked' : '' }} class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-3 text-sm">
                                                <strong>{{ $address->alias ?? $address->street }} {{ $address->exterior_number }}</strong><br>
                                                {{ $address->street }} {{ $address->exterior_number }}
                                                @if($address->interior_number) Int. {{ $address->interior_number }} @endif<br>
                                                Col. {{ $address->neighborhood }}, {{ $address->city }}<br>
                                                {{ $address->state }}, C.P. {{ $address->postal_code }}
                                                @if($address->is_primary)
                                                    <span class="text-green-600 text-xs">(Principal)</span>
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endif
                        <a href="{{ route('addresses.create') }}" class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                            + Agregar nueva dirección
                        </a>
                    </div>

                    <!-- Paso 2: Método de envío -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">2. Método de envío</h2>
                        <fieldset>
                            <div class="space-y-3">
                                @foreach($shippingMethods as $key => $method)
                                    <label class="flex justify-between items-center border rounded-md p-3 cursor-pointer hover:bg-gray-50 {{ $selectedShipping == $key ? 'ring-2 ring-indigo-500' : '' }}">
                                        <div class="flex items-center">
                                            <input type="radio" name="shipping_method" value="{{ $key }}" {{ $selectedShipping == $key ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-3 text-sm font-medium">{{ $method['name'] }}</span>
                                        </div>
                                        <span class="text-sm text-gray-900 font-semibold">${{ number_format($method['cost'], 2) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>

                    <!-- Paso 3: Cupón (opcional) -->
                    <div class="bg-white rounded-lg shadow p-6" x-data="couponApp()">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">3. Cupón de descuento</h2>
                        
                        {{-- Regla de Oro: Mostramos errores silenciosos del backend si falló la validación inicial --}}
                        @if($couponError)
                            <div class="mb-4 text-sm text-red-700 bg-red-50 p-3 rounded-md border-l-4 border-red-700">
                                {{ $couponError }}
                            </div>
                        @endif

                        <div class="flex space-x-2">
                            <input type="text" x-model="code" @keydown.enter.prevent="applyCoupon()" placeholder="Código de cupón"
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            
                            <button type="button" @click="applyCoupon()"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition text-sm font-semibold">
                                Aplicar
                            </button>
                            
                            @if($appliedCoupon)
                                <button type="button" @click="removeCoupon()"
                                        class="px-3 py-2 text-red-600 text-sm hover:underline font-semibold">
                                    Quitar
                                </button>
                            @endif
                        </div>
                        
                        <p x-cloak x-show="message" :class="success ? 'text-green-600' : 'text-red-600'" class="text-sm mt-2" x-text="message"></p>
                    </div>

                    <!-- Notas -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Notas adicionales</h2>
                        <textarea name="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Instrucciones especiales...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Columna derecha: Resumen -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumen del pedido</h2>

                        <div class="flow-root">
                            <ul class="divide-y divide-gray-200">
                                @foreach($items as $item)
                                    @php
                                        $prod = $item->product;
                                        $price = $prod->price ?? 0;
                                        $qty = $item->quantity;
                                    @endphp
                                    <li class="py-3 flex justify-between text-sm">
                                        <span>{{ $prod->name }} x{{ $qty }}</span>
                                        <span>${{ number_format($price * $qty, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="border-t mt-4 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($discountAmount > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Descuento</span>
                                    <span>-${{ number_format($discountAmount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span>Envío</span>
                                <span>${{ number_format($shippingCost, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold pt-2">
                                <span>Total</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="mt-6 w-full bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition font-semibold">
                            Confirmar pedido
                        </button>

                        <p class="mt-2 text-xs text-gray-500 text-center">
                            Al confirmar, tu pedido será registrado y pasará a estado pendiente de pago. Un administrador aprobará el pago manualmente.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function couponApp() {
            return {
                code: '{{ $appliedCoupon ?? '' }}',
                message: '',
                success: false,
                
                async applyCoupon() {
                    if (!this.code) return;
                    try {
                        const resp = await fetch('{{ route('checkout.apply-coupon') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ code: this.code })
                        });
                        const data = await resp.json();
                        this.message = data.message;
                        this.success = data.success;
                        
                        if (data.success) {
                            window.location.reload(); // Recargar para actualizar totales
                        }
                    } catch (error) {
                        this.message = 'Error de conexión al aplicar el cupón.';
                        this.success = false;
                    }
                },

                // SOLUCIÓN: Eliminación asíncrona limpia sin romper el DOM
                async removeCoupon() {
                    try {
                        const resp = await fetch('{{ route('checkout.remove-coupon') }}', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await resp.json();
                        
                        if (data.success) {
                            window.location.reload(); // Recargar para volver a los totales originales
                        }
                    } catch (error) {
                        this.message = 'Error de conexión al eliminar el cupón.';
                    }
                }
            }
        }
    </script>
</x-app-layout>