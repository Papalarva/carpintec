<x-app-layout>
    <x-slot:title>
        Pedido #{{ substr($order->id, 0, 8) }} | Carpintec
    </x-slot:title>

    <div class="bg-gray-50/30 min-h-screen py-12 pt-32 font-sans">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Navegación superior --}}
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('orders.index') }}"
                            class="text-gray-500 hover:text-amber-800 transition-colors">Mis Compras</a>
                    </li>
                    <li class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-gray-900 font-medium">Pedido #{{ substr($order->id, 0, 8) }}</span>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Columna Principal: Productos --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                            <h2 class="font-serif text-xl text-gray-900">Artículos del Pedido</h2>
                            <span class="text-sm text-gray-500">{{ $order->items->count() }} producto(s)</span>
                        </div>
                        <ul class="divide-y divide-gray-100">
                            @forelse ($order->items as $item)
                                <li class="p-6 flex gap-6">
                                    {{-- Sustituye el bloque de la imagen (aprox. línea 34) por este: --}}
                                    <div
                                        class="w-20 h-20 bg-gray-50 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                                        @php
                                            // SOLUCIÓN: Extraemos la primera imagen disponible de la relación media del producto
                                            $media = $item->product ? $item->product->media->first() : null;
                                            $imageUrl = $media ? $media->getUrl() : null;
                                        @endphp

                                        @if ($imageUrl)
                                            <img src="{{ $imageUrl }}"
                                                alt="{{ $item->product->name ?? 'Producto' }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                {{-- Icono outline de 1.5px según estándar de Carpintec --}}
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <h3 class="font-serif text-lg text-gray-900">
                                                {{ $item->product->name ?? 'Producto Personalizado' }}</h3>
                                            <p class="font-medium text-gray-900">
                                                ${{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Precio unitario:
                                            ${{ number_format($item->unit_price, 2) }}</p>
                                        <p class="text-sm text-gray-500">Cantidad: {{ $item->quantity }}</p>
                                    </div>
                                </li>
                            @empty
                                <li class="p-6">
                                    <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-5 text-sm text-gray-600">
                                        <p class="font-medium text-gray-900 mb-1">Pedido personalizado</p>
                                        <p>{{ $order->quotation?->subject ?? 'La cotización no tiene productos asociados.' }}</p>
                                    </div>
                                </li>
                            @endforelse
                        </ul>

                        {{-- Resumen de Totales --}}
                        <div class="bg-gray-50/50 p-6 border-t border-gray-100 space-y-3">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if ($order->discount > 0)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Descuento</span>
                                    <span>-${{ number_format($order->discount, 2) }}</span>
                                </div>
                            @endif
                            <div
                                class="flex justify-between text-base font-bold text-gray-900 pt-3 border-t border-gray-200">
                                <span>Total del Pedido</span>
                                <span>${{ number_format($order->total, 2) }} MXN</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Columna Lateral: Estado y Logística --}}
                <div class="space-y-6">
                    {{-- Estado del Pedido --}}
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">Estado actual</p>
                        <span
                            class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest border border-current {{ $order->status_id->color() }}">
                            {{ $order->status_id->label() }}
                        </span>
                        <div class="mt-6 pt-6 border-t border-gray-50">
                            <p class="text-sm text-gray-600 italic">"Gracias por confiar en la artesanía de Carpintec."
                            </p>
                        </div>
                    </div>

                    {{-- Información de Entrega --}}
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <svg class="w-5 h-5 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path
                                    d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25c0-4.446-3.542-7.875-7.875-7.875H9">
                                </path>
                            </svg>
                            <h3 class="font-serif text-lg text-gray-900">Entrega</h3>
                        </div>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p class="font-medium text-gray-900">{{ $order->customer->first_name }}
                                {{ $order->customer->last_name }}</p>
                            <p>{{ $order->shipping_address ?? 'Recolección en taller' }}</p>
                            <p>Ciudad Juárez, Chihuahua</p>
                            <p class="pt-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    stroke-width="1.5" viewBox="0 0 24 24">
                                    <path
                                        d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3">
                                    </path>
                                </svg>
                                {{ $order->customer->phone }}
                            </p>
                        </div>
                    </div>

                    {{-- Ayuda --}}
                    <div class="bg-amber-50/50 border border-amber-100 rounded-2xl p-6">
                        <h4 class="text-amber-900 font-bold text-sm uppercase tracking-wider mb-2">¿Necesitas ayuda?
                        </h4>
                        <p class="text-amber-800/80 text-xs leading-relaxed mb-4">Si tienes dudas sobre tu fabricación o
                            tiempos de entrega, contáctanos mencionando tu número de pedido.</p>
                        <a href="{{ route('contact.index') }}"
                            class="text-xs font-bold text-amber-900 underline underline-offset-4">Contactar Soporte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
