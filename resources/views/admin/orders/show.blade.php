@extends('layouts.admin')

@section('title', 'Pedido #' . $order->id)
@section('header', 'Pedido #' . $order->id)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Información principal --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium">Información General</h2>
            <dl class="mt-2 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                <dt class="text-gray-500">Cliente</dt>
                <dd>
                    {{ $order->customer->user?->first_name ?? 'Usuario' }} {{ $order->customer->user?->last_name ?? 'Eliminado' }} 
                    <span class="text-gray-400">({{ $order->customer->user?->email ?? 'No disponible' }})</span>
                </dd>
                <dt class="text-gray-500">Estado</dt>
                <dd><x-admin.badge :color="$order->status_id->color()" :label="$order->status_id->label()" /></dd>
                <dt class="text-gray-500">Subtotal</dt>
                <dd>${{ number_format($order->subtotal, 2) }}</dd>
                <dt class="text-gray-500">Descuento</dt>
                <dd>${{ number_format($order->discount_total, 2) }}</dd>
                <dt class="text-gray-500">Envío</dt>
                <dd>${{ number_format($order->shipping_cost, 2) }}</dd>
                <dt class="text-gray-500">Total</dt>
                <dd class="font-semibold">${{ number_format($order->total, 2) }}</dd>
                @if($order->notes)
                <dt class="text-gray-500">Notas</dt>
                <dd>{{ $order->notes }}</dd>
                @endif
                @if($order->quotation)
                <dt class="text-gray-500">Cotización</dt>
                <dd><a href="{{ route('admin.quotations.show', $order->quotation) }}" class="text-indigo-600">#{{ $order->quotation->id }}</a></dd>
                @endif
            </dl>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium mb-3">Productos</h2>
            <table class="min-w-full text-sm">
                <thead class="border-b">
                    <tr><th class="text-left py-1">Producto</th><th class="text-right py-1">Cant.</th><th class="text-right py-1">P. Unit.</th><th class="text-right py-1">Total</th></tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-b">
                            <td class="py-1">{{ $item->product->name }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium mb-3">Historial de Estados</h2>
            <ul class="space-y-2 text-sm">
                @forelse($order->statusHistory as $hist)
                    <li>
                        <x-admin.badge :color="$hist->status_id->color()" :label="$hist->status_id->label()" />
                        <span class="text-gray-500 ml-2">{{ $hist->changed_at->format('d/m/Y H:i') }}</span>
                        @if($hist->user)
                            <span class="text-gray-400">por {{ $hist->user->first_name }}</span>
                        @endif
                        @if($hist->comment)
                            <p class="text-gray-600 mt-1">{{ $hist->comment }}</p>
                        @endif
                    </li>
                @empty
                    <li class="text-gray-500">Sin historial.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Panel lateral --}}
    <div class="space-y-6">
        <!-- Cambiar estado -->
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-md font-medium mb-2">Cambiar Estado</h3>
            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                @csrf @method('PUT')
                <select name="status_id" class="w-full rounded border-gray-300 mb-2">
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" {{ $order->status_id === $status ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
                <textarea name="comment" rows="2" placeholder="Comentario (opcional)" class="w-full rounded border-gray-300 mb-2"></textarea>
                <button class="bg-indigo-600 text-white w-full py-2 rounded text-sm">Actualizar estado</button>
            </form>
        </div>

        <!-- Envío -->
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-md font-medium mb-2">Información de Envío</h3>
            @if($order->shipment)
                <dl class="text-sm space-y-1 mb-2">
                    <dt>Método:</dt><dd>{{ $order->shipment->shipping_method }}</dd>
                    <dt>Transportista:</dt><dd>{{ $order->shipment->carrier ?? '-' }}</dd>
                    <dt>Costo:</dt><dd>${{ number_format($order->shipment->cost, 2) }}</dd>
                    <dt>Tracking:</dt><dd>{{ $order->shipment->tracking_number ?? '-' }}</dd>
                </dl>
            @endif
            <form method="POST" action="{{ route('admin.orders.update-shipment', $order) }}">
                @csrf @method('PUT')
                <input type="text" name="shipping_method" value="{{ $order->shipment->shipping_method ?? '' }}" placeholder="Método de envío" required class="w-full rounded border-gray-300 mb-2">
                <input type="text" name="carrier" value="{{ $order->shipment->carrier ?? '' }}" placeholder="Transportista" class="w-full rounded border-gray-300 mb-2">
                <input type="number" step="0.01" name="cost" value="{{ $order->shipment->cost ?? '' }}" placeholder="Costo" class="w-full rounded border-gray-300 mb-2">
                <input type="text" name="tracking_number" value="{{ $order->shipment->tracking_number ?? '' }}" placeholder="Número de guía" class="w-full rounded border-gray-300 mb-2">
                <input type="date" name="estimated_delivery_date" value="{{ optional($order->shipment)->estimated_delivery_date?->format('Y-m-d') }}" class="w-full rounded border-gray-300 mb-2">
                <button class="bg-blue-600 text-white w-full py-2 rounded text-sm">Guardar envío</button>
            </form>
        </div>

        <!-- Pagos -->
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-md font-medium mb-2">Pagos</h3>
            @forelse($order->payments as $payment)
                <div class="text-sm border-b pb-2 mb-2">
                    <p>Monto: ${{ number_format($payment->amount, 2) }}</p>
                    <p>Estado: <x-admin.badge :color="$payment->status_id->color()" :label="$payment->status_id->label()" /></p>
                    @if($payment->mp_transaction_id)
                        <p>Transacción: {{ $payment->mp_transaction_id }}</p>
                    @endif
                    @if($payment->status_id === \App\Enums\PaymentStatus::PENDING)
                        <form method="POST" action="{{ route('admin.orders.approve-payment', [$order, $payment]) }}" class="mt-1">
                            @csrf @method('PUT')
                            <button class="text-green-600 hover:underline text-sm">Aprobar pago</button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">No hay pagos registrados.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection