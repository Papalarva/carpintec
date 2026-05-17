<x-mail::message>
# Nuevo Mensaje de Cliente

Hola, equipo.

El cliente **{{ $message->quotation->customer->user->first_name }} {{ $message->quotation->customer->user->last_name }}** ha añadido una nueva respuesta en la negociación del proyecto **"{{ $message->quotation->subject }}"**.

### Mensaje:
<x-mail::panel>
"{{ $message->message }}"
</x-mail::panel>

<x-mail::button :url="route('admin.quotations.show', $message->quotation)">
Ir al Expediente del Proyecto
</x-mail::button>

Atentamente,<br>
**Sistema de Notificaciones | Carpintec**
</x-mail::message>