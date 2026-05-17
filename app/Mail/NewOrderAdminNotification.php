<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderAdminNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        // Usamos los primeros 8 caracteres del UUID para una lectura amigable
        $shortId = substr($this->order->id, 0, 8);
        
        return new Envelope(
            subject: 'Nuevo Pedido Registrado #' . strtoupper($shortId) . ' - Carpintec',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.admin-notification',
        );
    }
}