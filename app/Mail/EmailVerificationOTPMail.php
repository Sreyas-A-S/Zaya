<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationOTPMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $name;

    public function __construct(string $name, string $otp)
    {
        $this->name = $name;
        $this->otp = $otp;
        $this->mailer('info');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Verify Your Email - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.default',
            with: [
                'title' => 'Verify Your Email Address',
                'intro' => 'Hi ' . $this->name . ', please use the following One-Time Password (OTP) to verify your email address on ' . config('app.name') . '.',
                'otp' => $this->otp, // The default view might need adjustment to show OTP prominently
                'outro' => 'This code will expire in 10 minutes. If you did not request this, please ignore this email.',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
