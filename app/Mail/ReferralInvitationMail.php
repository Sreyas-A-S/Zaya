<?php

namespace App\Mail;

use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReferralInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $referral;

    /**
     * Create a new message instance.
     */
    public function __construct(Referral $referral)
    {
        $this->referral = $referral->load(['user', 'referredBy', 'referredTo']);
        $this->mailer('info');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Session Referral: ' . $this->referral->referredTo->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.referral-invitation',
            with: [
                'referral' => $this->referral,
                'payUrl' => route('referrals.pay', $this->referral->referral_no)
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
