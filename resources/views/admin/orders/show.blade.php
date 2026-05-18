@extends('layouts.admin')

@section('title', 'Pedido #' . substr($order->id, 0, 8))
@section('header')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-serif font-bold text-gray-900">Pedido #{{ substr($order->id, 0, 8) }}</h1>
            <p class="text-sm text-gray-500 font-sans mt-1">Realizado el
                {{ $order->created_at->format('d de F, Y \a \l\a\s H:i') }}</p>
        </div>
        <x-admin.badge :color="$order->status_id->color()" :label="$order->status_id->label()" />
    </div>
@endsection

@section('content')

    @if (session('success'))
        <div class="mb-8 bg-green-50 border-l-4 border-green-700 p-4 rounded-r-xl shadow-sm">
            <p class="font-bold text-green-800 font-serif">¡Éxito!</p>
            <p class="text-green-700 font-sans text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- COLUMNA PRINCIPAL --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Tarjeta de Productos --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-lg font-medium text-gray-900 font-serif flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z">
                            </path>
                        </svg>
                        Artículos del Pedido
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm font-sans">
                        <thead class="bg-white">
                            <tr>
                                <th
                                    class="px-8 py-4 text-left font-semibold text-gray-500 uppercase tracking-wider text-xs">
                                    Producto</th>
                                <th
                                    class="px-8 py-4 text-center font-semibold text-gray-500 uppercase tracking-wider text-xs">
                                    Cant.</th>
                                <th
                                    class="px-8 py-4 text-right font-semibold text-gray-500 uppercase tracking-wider text-xs">
                                    Precio Unitario</th>
                                <th
                                    class="px-8 py-4 text-right font-semibold text-gray-500 uppercase tracking-wider text-xs">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($order->items as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-4 font-medium text-gray-900">
                                        @if ($item->product)
                                            {{ $item->product->name }}
                                            @if ($item->product->trashed())
                                            <br>
                                                <span
                                                    class="ml-2 text-[10px] font-bold uppercase tracking-widest text-red-600 bg-red-50 border border-red-200 px-2 py-0.5 rounded-md">Eliminado
                                                    del catálogo</span>
                                            @endif
                                        @else
                                            <span class="text-gray-500 italic">Producto no disponible (Eliminado)</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-4 text-center text-gray-600">{{ $item->quantity }}</td>
                                    <td class="px-8 py-4 text-right text-gray-600">
                                        ${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-8 py-4 text-right font-semibold text-gray-900">
                                        ${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Resumen Financiero --}}
                <div class="px-8 py-6 bg-gray-50/80 border-t border-gray-100 flex flex-col items-end">
                    <dl class="space-y-2 text-sm text-gray-600 w-full sm:w-64 font-sans">
                        <div class="flex justify-between">
                            <dt>Subtotal</dt>
                            <dd class="font-medium text-gray-900">${{ number_format($order->subtotal, 2) }}</dd>
                        </div>
                        @if ($order->discount_total > 0)
                            <div class="flex justify-between text-green-600">
                                <dt>Descuento</dt>
                                <dd class="font-medium">-${{ number_format($order->discount_total, 2) }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between pb-4 border-b border-gray-200">
                            <dt>Envío</dt>
                            <dd class="font-medium text-gray-900">${{ number_format($order->shipping_cost, 2) }}</dd>
                        </div>
                        <div class="flex justify-between pt-2 text-base">
                            <dt class="font-bold text-gray-900">Total</dt>
                            <dd class="font-bold text-amber-900">${{ number_format($order->total, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Timeline de Historial de Estados --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2
                    class="text-lg font-medium text-gray-900 font-serif mb-6 border-b border-gray-100 pb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Historial de Movimientos
                </h2>

                <div class="relative border-l border-gray-200 ml-3 space-y-8">
                    @forelse($order->statusHistory as $hist)
                        <div class="relative pl-8">
                            {{-- Punto del Timeline --}}
                            <div
                                class="absolute -left-[5px] top-1 h-2.5 w-2.5 rounded-full border-2 border-white bg-amber-600 ring-4 ring-amber-50">
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-1">
                                <h3 class="font-medium text-gray-900 font-sans text-sm">
                                    <x-admin.badge :color="$hist->status_id->color()" :label="$hist->status_id->label()" />
                                </h3>
                                <time
                                    class="text-xs text-gray-500 font-sans mt-1 sm:mt-0">{{ $hist->changed_at->format('d/m/Y \a \l\a\s H:i') }}</time>
                            </div>

                            <p class="text-sm text-gray-500 font-sans mt-2">
                                @if ($hist->user)
                                    Modificado por <span
                                        class="font-medium text-gray-700">{{ $hist->user->first_name }}</span>.
                                @endif
                                @if ($hist->comment)
                                    <span
                                        class="italic block mt-1 bg-gray-50 p-2 rounded-lg border border-gray-100">"{{ $hist->comment }}"</span>
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 font-sans pl-6">No hay historial registrado aún.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- COLUMNA LATERAL --}}
        <div class="space-y-8">

            {{-- Datos del Cliente --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50">
                    <h3 class="text-md font-medium text-gray-900 font-serif flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z">
                            </path>
                        </svg>
                        Datos del Cliente
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Nombre</p>
                        <p class="text-sm text-gray-900 font-medium font-sans">
                            {{ $order->customer->user?->first_name ?? 'Usuario' }}
                            {{ $order->customer->user?->last_name ?? 'Eliminado' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Email</p>
                        <p class="text-sm text-gray-900 font-sans">{{ $order->customer->user?->email ?? 'No disponible' }}
                        </p>
                    </div>
                    @if ($order->quotation)
                        <div class="pt-4 border-t border-gray-100 mt-4">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Origen</p>
                            <a href="{{ route('admin.quotations.show', $order->quotation) }}"
                                class="inline-flex items-center gap-1 text-sm text-amber-700 hover:text-amber-900 font-medium transition-colors">
                                Ver Cotización Original
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Cambiar Estado --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-md font-medium text-gray-900 font-serif mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99">
                        </path>
                    </svg>
                    Gestión de Estado
                </h3>
                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <select name="status_id"
                        class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans bg-gray-50">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" {{ $order->status_id === $status ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>

                    <textarea name="comment" rows="2" placeholder="Añadir un comentario interno (opcional)..."
                        class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans bg-gray-50"></textarea>

                    {{-- Botón Estricto: Si el estado actual es Cancelado usamos rojo para enfatizar peligro al moverlo, si no, es Ambar --}}
                    <button type="submit"
                        class="w-full justify-center inline-flex items-center {{ $order->status_id === \App\Enums\OrderStatus::CANCELLED ? 'bg-red-800 hover:bg-red-900' : 'bg-amber-900 hover:bg-amber-800' }} text-white uppercase tracking-widest text-xs font-bold rounded-xl px-6 py-3.5 transition-colors duration-200 shadow-sm font-sans">
                        Actualizar Estado
                    </button>
                </form>
            </div>

            {{-- Logística / Envío --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-md font-medium text-gray-900 font-serif mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12">
                        </path>
                    </svg>
                    Logística y Envío
                </h3>

                <form method="POST" action="{{ route('admin.orders.update-shipment', $order) }}" class="space-y-3">
                    @csrf @method('PUT')

                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Método</label>
                        <input type="text" name="shipping_method"
                            value="{{ $order->shipment->shipping_method ?? '' }}" placeholder="Ej. Envío Estándar"
                            required
                            class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:ring-amber-800 focus:border-amber-800 bg-gray-50">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Paquetería</label>
                            <input type="text" name="carrier" value="{{ $order->shipment->carrier ?? '' }}"
                                placeholder="Ej. FedEx"
                                class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:ring-amber-800 focus:border-amber-800 bg-gray-50">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Costo
                                Real</label>
                            <input type="number" step="0.01" name="cost"
                                value="{{ $order->shipment->cost ?? '' }}" placeholder="0.00"
                                class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:ring-amber-800 focus:border-amber-800 bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Guía
                            (Tracking)</label>
                        <input type="text" name="tracking_number"
                            value="{{ $order->shipment->tracking_number ?? '' }}" placeholder="Número de rastreo..."
                            class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:ring-amber-800 focus:border-amber-800 bg-gray-50 font-mono">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-1">Entrega
                            Estimada</label>
                        <input type="date" name="estimated_delivery_date"
                            value="{{ optional($order->shipment)->estimated_delivery_date?->format('Y-m-d') }}"
                            class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:ring-amber-800 focus:border-amber-800 bg-gray-50">
                    </div>

                    <button
                        class="w-full mt-2 justify-center inline-flex items-center bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-xs font-bold rounded-xl px-6 py-3.5 transition-colors shadow-sm font-sans">
                        Guardar Logística
                    </button>
                </form>
            </div>

            {{-- Pagos --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-md font-medium text-gray-900 font-serif mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                    Transacciones
                </h3>

                <div class="space-y-4">
                    @forelse($order->payments as $payment)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 relative">
                            <div class="flex justify-between items-start mb-2">
                                <span
                                    class="font-semibold text-gray-900 font-sans">${{ number_format($payment->amount, 2) }}</span>
                                <x-admin.badge :color="$payment->status_id->color()" :label="$payment->status_id->label()" />
                            </div>

                            @if ($payment->mp_transaction_id)
                                <p class="text-xs text-gray-500 font-mono break-all mb-2">Ref:
                                    {{ $payment->mp_transaction_id }}</p>
                            @endif

                            @if ($payment->status_id === \App\Enums\PaymentStatus::PENDING)
                                <form method="POST"
                                    action="{{ route('admin.orders.approve-payment', [$order, $payment]) }}"
                                    class="mt-3 border-t border-gray-200 pt-3">
                                    @csrf @method('PUT')
                                    <button
                                        class="w-full inline-flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-widest text-green-700 hover:text-green-800 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.5 12.75l6 6 9-13.5"></path>
                                        </svg>
                                        Aprobar Pago Manualmente
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <p class="text-sm text-gray-500 font-sans">No hay pagos registrados.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
