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
    public $replyMail;
    public $pdfContent;

    public function __construct($reason, $pdfContent,$replyMail)
    {
        $this->reason = $reason;
        $this->pdfContent = $pdfContent;
        $this->replyMail = $replyMail;
    }
    /**
     * Get the message envelope.
     */

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
            ])->replyTo($this->replyMail, 'Administración Coolsoft Technology SAS');
    }
}
