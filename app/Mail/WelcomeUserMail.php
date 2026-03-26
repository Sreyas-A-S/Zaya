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
    public ?string $plainPassword;
    public string $loginUrl;
    public ?string $role;

    public function __construct(string $email, ?string $plainPassword, string $loginUrl, ?string $role = null)
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->loginUrl = $loginUrl;
        $this->role = $role;
        $this->mailer('info');
    }

    public function envelope(): Envelope
    {
        $subject = 'Welcome to ' . config('app.name');
        
        $practitionerRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist', 'translator'];
        if ($this->role && in_array($this->role, $practitionerRoles)) {
            $subject = 'Welcome! Your Account is Now Active - ' . config('app.name');
        }

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        $practitionerRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist', 'translator'];

        if ($this->role && in_array($this->role, $practitionerRoles)) {
            $title = 'Welcome to ' . config('app.name');
            $intro = 'Great news! Your profile is now active. You can start offering sessions on Zaya Wellness.';
            $outro = 'Log in, finalize your profile details, and open your calendar for bookings.';
        } else {
            $title = 'Welcome to ' . config('app.name');
            $intro = 'Your account has been created successfully. Use the credentials below to log in and start your wellness journey.';
            $outro = 'For security, please change your password after your first login.';
        }

        return new Content(
            view: 'emails.default',
            with: [
                'title' => $title,
                'intro' => $intro,
                'credentials' => $this->plainPassword ? [
                    'email' => $this->email,
                    'password' => $this->plainPassword,
                    'login_url' => $this->loginUrl,
                ] : null,
                'outro' => $outro,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
    
