<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PractitionerApplicationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $roleLabel = 'Practitioner')
    {
        $this->mailer('info');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Application Submitted - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.default',
            with: [
                'title' => 'Thank you for your interest in Zaya Wellness',
                'intro' => "We thank you for your interest in joining the ZAYA Wellness online consultation portal—an Indian initiative launched in 2025 to connect Ayurvedic doctors, spiritual counsellors, and yoga therapists with clients worldwide. Our mission is to empower holistic practitioners and offer authentic, expert-led care.",
                'outro' => "Incomplete applications will not be reviewed. Please ensure all documents are legible and complete to avoid delays. Your application will be reviewed by our Approval Commission within 30 days. You will receive a response via email.",
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
