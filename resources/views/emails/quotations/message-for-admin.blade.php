<x-mail::message>
# Mensaje de Cliente: {{ $message->quotation->subject }}

El cliente **{{ $message->quotation->customer->user->first_name }} {{ $message->quotation->customer->user->last_name }}** ha enviado un nuevo mensaje en el hilo de negociación.

<x-mail::panel>
"{{ $message->message }}"
</x-mail::panel>

<x-mail::button :url="route('admin.quotations.show', $message->quotation)">
Ir al Panel de Control
</x-mail::button>

Sistema de Notificaciones Automáticas,<br>
**{{ config('app.name') }}**
</x-mail::message>