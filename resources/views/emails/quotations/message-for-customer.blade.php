<x-mail::message>
# Tienes un nuevo mensaje del Taller

Hola **{{ $message->quotation->customer->user->first_name }}**,

Los artesanos de Carpintec han respondido a tu solicitud para el proyecto **"{{ $message->quotation->subject }}"**.

<x-mail::panel>
"{{ $message->message }}"
</x-mail::panel>

Para continuar la conversación o ver si hay archivos adjuntos, por favor ingresa a tu expediente del proyecto:

<x-mail::button :url="route('quotations.show', $message->quotation)" color="success">
Ver mi Proyecto
</x-mail::button>

Gracias por confiar en,<br>
El equipo de **{{ config('app.name') }}**
</x-mail::message>