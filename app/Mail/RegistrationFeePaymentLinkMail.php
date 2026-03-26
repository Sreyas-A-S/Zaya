<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationFeePaymentLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $roleLabel,
        public float $amount,
        public string $currency,
        public string $paymentUrl
    ) {
        $this->mailer('info');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Action Needed: Complete Your Registration Payment'
        );
    }

    public function content(): Content
    {
        $button = '<a href="' . e($this->paymentUrl) . '" style="display:inline-block;padding:14px 24px;background:#2E4B3C;color:#fff;border-radius:8px;text-decoration:none;font-weight:700;">Pay Registration Fee</a>';

        return new Content(
            view: 'emails.default',
            with: [
                'title' => 'Complete Your Registration',
                'intro' => "We’ve received your application. Please complete the {$this->roleLabel} registration fee to proceed.",
                'messageData' => (object)[
                    'first_name' => '',
                    'last_name' => '',
                    'email' => '',
                    'phone' => '',
                    'user_type' => [],
                    'message' => "Amount: {$this->currency} " . number_format($this->amount, 2) . "\nReview timeline: up to 30 days\n\nClick the button below to pay:",
                ],
                'outro' => $button,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
