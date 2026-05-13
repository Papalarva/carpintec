<x-app-layout>
    <x-slot:title>Pedido Confirmado - Carpintec</x-slot:title>

    <div class="max-w-3xl mx-auto py-16 px-4 sm:px-6 lg:px-8 text-center">
        {{-- Icono de éxito con nuestra línea fina de 1.5px --}}
        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-50 rounded-full mb-8 border border-green-100">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1 class="text-4xl font-serif font-bold text-gray-900 mb-4">¡Gracias por tu pedido!</h1>
        <p class="text-lg text-gray-600 font-sans mb-12">
            El pedido <span class="font-bold text-amber-900">#{{ substr($order->id, 0, 8) }}</span> ha sido recibido correctamente y está en espera de validación de pago.
        </p>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mb-12">
            <div class="p-8 text-left">
                <h2 class="text-sm uppercase tracking-widest font-bold text-gray-400 mb-6">Resumen del Pedido</h2>
                
                <div class="space-y-4 mb-8">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm font-sans">
                            <span class="text-gray-600">{{ $item->product->name }} <span class="text-gray-400">x{{ $item->quantity }}</span></span>
                            <span class="font-semibold text-gray-900">${{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-50 pt-4 space-y-2">
                    <div class="flex justify-between text-sm font-sans">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_total > 0)
                        <div class="flex justify-between text-sm font-sans text-green-600">
                            <span>Descuento aplicado</span>
                            <span>-${{ number_format($order->discount_total, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm font-sans">
                        <span class="text-gray-500">Envío</span>
                        <span class="text-gray-900">${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xl font-serif font-bold text-gray-900 pt-4">
                        <span>Total</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Instrucciones de pago con color Terracota --}}
            <div class="bg-amber-50 p-6 border-t border-amber-100">
                <p class="text-sm text-amber-900 font-sans">
                    <strong>Nota importante:</strong> Tu pago está siendo procesado. Un administrador de Carpintec revisará la transacción y recibirás un correo electrónico cuando el estado de tu pedido cambie a "Preparando envío".
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('catalog.index') }}" class="inline-flex justify-center items-center px-8 py-4 border border-gray-200 rounded-xl text-sm font-bold uppercase tracking-widest text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 font-sans">
                Seguir comprando
            </a>
            <a href="{{ route('orders.index') }}" class="inline-flex justify-center items-center px-8 py-4 border border-transparent rounded-xl text-sm font-bold uppercase tracking-widest text-white bg-amber-900 hover:bg-amber-800 transition-colors duration-200 font-sans shadow-sm">
                Ver mis pedidos
            </a>
        </div>
    </div>
</x-app-layout>