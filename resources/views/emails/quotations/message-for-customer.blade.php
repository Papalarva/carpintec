<x-mail::message>
# Actualización de tu Proyecto

Hola, **{{ $message->quotation->customer->user->first_name }}**.

Uno de nuestros artesanos ha respondido a tu solicitud para el proyecto **"{{ $message->quotation->subject }}"**.

### Mensaje del Taller:
<x-mail::panel>
"{{ $message->message }}"
</x-mail::panel>

Para continuar con la conversación, afinar detalles sobre la madera o descargar documentos adjuntos, por favor ingresa a tu expediente seguro:

<x-mail::button :url="route('quotations.show', $message->quotation)">
Ver mi Proyecto
</x-mail::button>

Gracias por dejar este proyecto en nuestras manos,<br>
**El Taller de Carpintec**
</x-mail::message>