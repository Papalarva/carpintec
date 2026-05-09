<x-app-layout>
    <x-slot:title>
        Mis Compras | Carpintec
    </x-slot:title>

    <div class="bg-gray-50/30 min-h-screen py-12 pt-32 font-sans">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Encabezado --}}
            <div class="mb-10 flex flex-col sm:flex-row sm:items-end justify-between border-b border-gray-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl sm:text-4xl text-gray-900 tracking-tight">Mis Compras</h1>
                    <p class="mt-2 text-sm text-gray-600">Historial de tus pedidos y seguimiento de envíos.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('catalog.index') }}" class="text-sm font-medium text-amber-800 hover:text-amber-900 transition-colors inline-flex items-center gap-1">
                        Seguir explorando
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"></path></svg>
                    </a>
                </div>
            </div>

            {{-- Contenedor de Pedidos --}}
            <div class="space-y-8">
                @forelse ($orders as $order)
                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden transition-shadow hover:shadow-md">
                        
                        {{-- Cabecera del Pedido --}}
                        <div class="bg-gray-50/50 border-b border-gray-100 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex flex-wrap gap-x-8 gap-y-2 text-sm">
                                <div>
                                    <p class="font-semibold text-gray-500 uppercase tracking-wider text-[10px] mb-0.5">Fecha de pedido</p>
                                    <p class="text-gray-900 font-medium">{{ $order->created_at->format('d M, Y') }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-500 uppercase tracking-wider text-[10px] mb-0.5">Total</p>
                                    <p class="text-gray-900 font-medium">${{ number_format($order->total, 2) }} MXN</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-500 uppercase tracking-wider text-[10px] mb-0.5">Número de pedido</p>
                                    <p class="text-gray-900 font-mono">{{ substr($order->id, 0, 8) }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                {{-- Aquí asumo que usas tu componente de badge, o puedes crear un texto estilizado --}}
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest border border-current {{ $order->status_id->color() }}">
                                    {{ $order->status_id->label() }}
                                </span>
                            </div>
                        </div>

                        {{-- Lista de Productos del Pedido --}}
                        <div class="p-6">
                            <ul class="divide-y divide-gray-100">
                                @foreach ($order->items as $item)
                                    <li class="py-4 flex flex-col sm:flex-row gap-6 first:pt-0 last:pb-0">
                                        {{-- Imagen del Producto --}}
                                        <div class="w-full sm:w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                            @if($item->product && $item->product->getFirstMediaUrl('images'))
                                                <img src="{{ $item->product->getFirstMediaUrl('images') }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path></svg>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Info del Producto --}}
                                        <div class="flex-1 flex flex-col justify-center">
                                            <h4 class="text-base font-serif font-medium text-gray-900">{{ $item->product->name ?? 'Producto Eliminado' }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">Cantidad: {{ $item->quantity }}</p>
                                        </div>

                                        {{-- Acciones del Item --}}
                                        <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center mt-4 sm:mt-0">
                                            <p class="text-gray-900 font-medium">${{ number_format($item->unit_price, 2) }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        {{-- Botón de Acción --}}
                        <div class="bg-white border-t border-gray-100 px-6 py-4">
                            <div class="flex justify-end">
                                <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center justify-center bg-white border border-gray-200 text-gray-900 hover:bg-gray-50 uppercase tracking-widest text-xs font-bold rounded-lg px-6 py-3 transition-colors duration-200 shadow-sm">
                                    Ver detalles del pedido
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Estado Vacío Elegante --}}
                    <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-sm">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                        </svg>
                        <h3 class="font-serif text-2xl text-gray-900 mb-2">Aún no tienes compras</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">Tu historial está vacío. Explora nuestras colecciones y encuentra la pieza perfecta para tu espacio.</p>
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm">
                            Ir al catálogo
                        </a>
                    </div>
                @endforelse

                {{-- Paginación si aplica --}}
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>