<?php

namespace App\Mail;

use App\Models\EmployeeProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMatchFound extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EmployeeProfile $matchedProfile,
        public EmployeeProfile $recipientProfile
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Transfer Match Found - {$this->matchedProfile->first_name} wants to transfer to your area!",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-match-found',
        );
    }
}
