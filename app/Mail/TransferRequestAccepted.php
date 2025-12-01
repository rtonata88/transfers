<?php

namespace App\Mail;

use App\Models\TransferRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransferRequestAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TransferRequest $transferRequest
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Good news! {$this->transferRequest->requestee->first_name} accepted your transfer request",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.transfer-request-accepted',
        );
    }
}
