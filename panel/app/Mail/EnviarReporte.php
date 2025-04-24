<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class EnviarReporte extends Mailable
{
    use Queueable, SerializesModels;

    public $reason;
    public $replyMail;
    public $pdfContent;
    public $replyInfo;

    public function __construct($reason, $pdfContent,$replyMail,$replyInfo)
    {
        $this->reason = $reason;
        $this->pdfContent = $pdfContent;
        $this->replyMail = $replyMail;
        $this->replyInfo = $replyInfo;

        $fechaInicioRaw = Carbon::parse($replyInfo["FechaInicio"]);
        $this->replyInfo["FechaInicio"] = $fechaInicioRaw->translatedFormat('F d');

        $fechaFinRaw = Carbon::parse($replyInfo["FechaFin"])->subDay();
        $this->replyInfo["FechaFin"] = $fechaFinRaw->translatedFormat('F d');


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
            ->with([
                'informacion' => $this->replyInfo,
                'reason' => $this->reason,
            ])
            ->attachData($this->pdfContent, 'Reporte.pdf', [
                'mime' => 'application/pdf',
            ])->replyTo($this->replyMail, 'Administración Coolsoft Technology SAS');
    }
}
