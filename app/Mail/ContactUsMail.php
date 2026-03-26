<?php

namespace App\Mail;

use App\Models\ContactUs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageData;

    /**
     * Create a new message instance.
     */
    public function __construct(ContactUs $messageData)
    {
        $this->messageData = $messageData;
        $this->mailer('info');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Us Message - ' . $this->messageData->first_name . ' ' . $this->messageData->last_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.default',
            with: [
                'title' => 'New Contact Message Received',
                'intro' => 'You have received a new inquiry from the Zaya Wellness contact form.',
                'messageData' => $this->messageData,
                'outro' => 'This is an automated notification from the Zaya Wellness Platform.',
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
