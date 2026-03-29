<?php

namespace App\Mail;

use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReferralReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $referral;

    public function __construct(Referral $referral)
    {
        $this->referral = $referral->load(['user', 'referredBy', 'referredTo']);
        $this->mailer('info');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Client Referral: ' . $this->referral->user->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.referral-received',
            with: [
                'referral' => $this->referral,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
