<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnviarReporte extends Mailable
{
    use Queueable, SerializesModels;

    public $reason;
    public $pdfContent;

    public function __construct($reason, $pdfContent)
    {
        $this->reason = $reason;
        $this->pdfContent = $pdfContent;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Enviar Reporte',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reporte',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->subject($this->reason)
            ->markdown('emails.reporte')
            ->attachData($this->pdfContent, 'Reporte.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
