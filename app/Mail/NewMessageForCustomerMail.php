<?php

namespace App\Mail;

use App\Models\QuotationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMessageForCustomerMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $message;

    public function __construct(QuotationMessage $message)
    {
        $this->message = $message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo mensaje sobre tu proyecto: ' . $this->message->quotation->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.quotations.message-for-customer',
        );
    }
}