<x-app-layout>
    <x-slot:title>Finalizar compra - Carpintec</x-slot:title>

    <div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8 pb-24" x-data="checkoutApp()">
        <h1 class="text-4xl font-serif font-medium text-gray-900 mb-8 tracking-tight">Finalizar compra</h1>

        <form action="{{ route('checkout.store') }}" method="POST" @submit="submitForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-8 space-y-8">
                    
                    <div class="bg-white rounded-xl shadow-premium p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            <h2 class="text-xl font-serif text-gray-900">1. Dirección de envío</h2>
                        </div>
                        
                        @if($addresses->count())
                            <fieldset>
                                <div class="space-y-4">
                                    @foreach($addresses as $address)
                                        <label class="flex items-start border border-gray-200 rounded-xl p-5 cursor-pointer transition-colors duration-200"
                                               :class="{ 'ring-2 ring-brand bg-brand-light/30 border-transparent': selectedAddress == '{{ $address->id }}', 'hover:bg-gray-50': selectedAddress != '{{ $address->id }}' }">
                                            <input type="radio" 
                                                   name="address_id" 
                                                   value="{{ $address->id }}" 
                                                   x-model="selectedAddress" 
                                                   class="mt-1 h-4 w-4 text-brand focus:ring-brand border-gray-300">
                                            <span class="ml-4 font-sans text-sm text-gray-700">
                                                <strong class="text-gray-900 text-base font-medium">{{ $address->alias ?? $address->street }} {{ $address->exterior_number }}</strong><br>
                                                <span class="mt-1 block">
                                                    {{ $address->street }} {{ $address->exterior_number }}
                                                    @if($address->interior_number) Int. {{ $address->interior_number }} @endif<br>
                                                    Col. {{ $address->neighborhood }}, {{ $address->city }}<br>
                                                    {{ $address->state }}, C.P. {{ $address->postal_code }}
                                                </span>
                                                @if($address->is_primary)
                                                    <span class="mt-2 inline-block text-brand text-xs font-bold tracking-wider uppercase">(Principal)</span>
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endif
                        <a href="{{ route('addresses.create') }}" class="mt-6 inline-flex items-center text-sm font-sans font-medium text-brand hover:text-brand-hover transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Agregar nueva dirección
                        </a>
                    </div>

                    <div class="bg-white rounded-xl shadow-premium p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                            <h2 class="text-xl font-serif text-gray-900">2. Método de envío</h2>
                        </div>
                        <fieldset>
                            <div class="space-y-4">
                                @foreach($shippingMethods as $key => $method)
                                    <label class="flex justify-between items-center border border-gray-200 rounded-xl p-5 cursor-pointer transition-colors duration-200"
                                           :class="{ 'ring-2 ring-brand bg-brand-light/30 border-transparent': selectedShipping === '{{ $key }}', 'hover:bg-gray-50': selectedShipping !== '{{ $key }}' }">
                                        <div class="flex items-center">
                                            <input type="radio" 
                                                   name="shipping_method" 
                                                   value="{{ $key }}" 
                                                   x-model="selectedShipping" 
                                                   class="h-4 w-4 text-brand focus:ring-brand border-gray-300">
                                            <span class="ml-4 font-sans text-sm font-medium text-gray-900">{{ $method['name'] }}</span>
                                        </div>
                                        <span class="text-sm font-sans text-gray-900 font-semibold">${{ number_format($method['cost'], 2) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>

                    <div class="bg-white rounded-xl shadow-premium p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                            <h2 class="text-xl font-serif text-gray-900">3. Cupón de descuento</h2>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                            <input type="text" 
                                   x-model="code" 
                                   @keydown.enter.prevent="applyCoupon" 
                                   placeholder="Código de cupón"
                                   class="flex-1 rounded-xl border-gray-200 py-3.5 focus:border-brand focus:ring-brand shadow-sm font-sans text-sm">
                            
                            <button type="button" 
                                    @click="applyCoupon"
                                    class="px-6 py-3.5 bg-gray-100 text-gray-900 rounded-xl hover:bg-gray-200 transition-colors font-sans text-sm font-semibold tracking-wide">
                                Aplicar
                            </button>
                            
                            <button type="button" 
                                    x-show="discountAmount > 0" x-cloak
                                    @click="removeCoupon"
                                    class="px-4 py-3.5 text-red-600 font-sans text-sm font-medium hover:text-red-800 transition-colors">
                                Quitar
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-premium p-8">
                        <h2 class="text-xl font-serif text-gray-900 mb-4">Notas adicionales</h2>
                        <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-200 py-3.5 focus:border-brand focus:ring-brand shadow-sm font-sans text-sm" placeholder="Instrucciones especiales para entrega...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white rounded-xl shadow-premium p-8 sticky top-32">
                        <h2 class="text-2xl font-serif text-gray-900 mb-6">Resumen del pedido</h2>

                        <div class="flow-root mb-6">
                            <ul class="divide-y divide-gray-100">
                                @foreach($items as $item)
                                    <li class="py-4 flex justify-between font-sans text-sm">
                                        <span class="text-gray-700 pr-4">{{ $item->product->name }} <span class="text-gray-400">x{{ $item->quantity }}</span></span>
                                        <span class="font-medium text-gray-900">${{ number_format(($item->product->price ?? 0) * $item->quantity, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="border-t border-gray-100 mt-4 pt-6 space-y-4 font-sans text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            
                            <div x-show="discountAmount > 0" x-cloak class="flex justify-between text-brand font-medium">
                                <span>Descuento</span>
                                <span x-text="'-$' + formatMoney(discountAmount)">-${{ number_format($discountAmount, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-gray-600">
                                <span>Envío</span>
                                {{-- Fallback robusto en PHP dentro del span --}}
                                <span x-text="'$' + formatMoney(shippingCost)">${{ number_format($shippingCost, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-xl font-serif text-gray-900 pt-4 border-t border-gray-100">
                                <span>Total</span>
                                {{-- Fallback robusto en PHP dentro del span --}}
                                <span x-text="'$' + formatMoney(total)">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" 
                                :disabled="isSubmitting"
                                class="mt-8 w-full bg-brand text-white hover:bg-brand-hover uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors shadow-premium disabled:opacity-75 disabled:cursor-not-allowed">
                            <span x-text="buttonText">Confirmar pedido</span>
                        </button>

                        <p class="mt-4 text-xs font-sans text-gray-400 text-center leading-relaxed">
                            Al confirmar, tu pedido será registrado y pasará a estado pendiente de pago. Un administrador aprobará el pago manualmente.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Script nativo sin @push para asegurar que siempre se inyecte --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkoutApp', () => ({
                // Parseo seguro de variables PHP para evitar errores de sintaxis en JS
                subtotal: Number(@json($subtotal ?? 0)),
                discountAmount: Number(@json($discountAmount ?? 0)),
                shippingMethods: @json($shippingMethods ?? []),
                selectedShipping: @json($selectedShipping ?? 'standard'),
                selectedAddress: @json(old('address_id', auth()->user()->customer->addresses()->where('is_primary', true)->value('id'))),
                code: @json($appliedCoupon ?? ''),
                
                // Control UI
                isSubmitting: false,
                buttonText: 'Confirmar pedido',

                // Computados Reactivos
                get shippingCost() {
                    if (this.shippingMethods && this.shippingMethods[this.selectedShipping]) {
                        return Number(this.shippingMethods[this.selectedShipping].cost) || 0;
                    }
                    return 0;
                },

                get total() {
                    const baseTotal = Math.max(0, this.subtotal - this.discountAmount);
                    return baseTotal + this.shippingCost;
                },

                // Métodos utilitarios
                formatMoney(amount) {
                    return Number(amount).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },

                submitForm(e) {
                    if (this.isSubmitting) {
                        e.preventDefault();
                        return;
                    }
                    this.isSubmitting = true;
                    this.buttonText = 'PROCESANDO...';
                },

                // Peticiones
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
                        
                        if (data.success) {
                            this.discountAmount = Number(data.discount); 
                            if(typeof window.showToast === 'function') window.showToast(data.message, false);
                        } else {
                            this.discountAmount = 0;
                            if(typeof window.showToast === 'function') window.showToast(data.message, true);
                        }
                    } catch (error) {
                        if(typeof window.showToast === 'function') window.showToast('Error de conexión al aplicar el cupón.', true);
                    }
                },

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
                            this.discountAmount = 0;
                            this.code = '';
                            if(typeof window.showToast === 'function') window.showToast(data.message, false);
                        }
                    } catch (error) {
                        if(typeof window.showToast === 'function') window.showToast('Error de conexión al eliminar el cupón.', true);
                    }
                }
            }));
        });
    </script>
</x-app-layout>