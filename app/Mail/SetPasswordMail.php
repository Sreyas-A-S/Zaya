<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $email;
    public string $setPasswordUrl;

    public function __construct(string $email, string $setPasswordUrl)
    {
        $this->email = $email;
        $this->setPasswordUrl = $setPasswordUrl;
        $this->mailer('info');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Set Your Password - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.default',
            with: [
                'title' => 'Create your password',
                'intro' => 'Your application has been approved. Please create a password to activate your account.',
                'credentials' => [
                    'email' => $this->email,
                   
                    'password' => null,
                ],
                'outro' => 'This link is secure and expires after use. If you did not request this, please contact support.',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

