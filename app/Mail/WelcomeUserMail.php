<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $email;
    public string $plainPassword;
    public string $loginUrl;

    public function __construct(string $email, string $plainPassword, string $loginUrl)
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->loginUrl = $loginUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.default',
            with: [
                'title' => 'Welcome to ' . config('app.name'),
                'intro' => 'Your account has been created successfully. Use the credentials below to log in.',
                'credentials' => [
                    'email' => $this->email,
                    'password' => $this->plainPassword,
                    'login_url' => $this->loginUrl,
                ],
                'outro' => 'For security, please change your password after your first login.',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
    