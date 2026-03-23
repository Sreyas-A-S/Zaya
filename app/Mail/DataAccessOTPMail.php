<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DataAccessOTPMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $requesterName;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $requesterName)
    {
        $this->otp = $otp;
        $this->requesterName = $requesterName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Security OTP: Data Access Request from ' . $this->requesterName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.data-access-otp',
            with: [
                'otp' => $this->otp,
                'requesterName' => $this->requesterName,
            ],
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
}
