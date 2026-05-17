<x-mail::message>
# Nuevo pedido en Carpintec

Hola, equipo.

Se acaba de registrar una nueva orden de compra en la boutique. A continuación, los detalles para validación de inventario y seguimiento.

<x-mail::panel>
**ID de Pedido:** {{ strtoupper(substr($order->id, 0, 8)) }}

**Cliente:** {{ $order->customer->user->first_name }} {{ $order->customer->user->last_name }}

**Correo:** {{ $order->customer->user->email }}

**Total a procesar:** ${{ number_format($order->total, 2) }}
</x-mail::panel>

### Resumen de Piezas Solicitadas

<x-mail::table>
| Producto | Cantidad | Subtotal |
| :--- | :---: | ---: |
@foreach($order->items as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | ${{ number_format($item->unit_price * $item->quantity, 2) }} |
@endforeach
</x-mail::table>

@if($order->notes)
### Notas del Cliente:
> "{{ $order->notes }}"
@endif

<x-mail::button :url="route('admin.orders.show', $order)">
Ir al Panel del Pedido
</x-mail::button>

Atentamente,<br>
**Departamento de Ventas | Carpintec**
</x-mail::message>