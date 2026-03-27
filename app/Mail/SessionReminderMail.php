<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SessionReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $type;
    public $videoLink;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, string $type, string $videoLink)
    {
        $this->booking = $booking;
        $this->type = $type;
        $this->videoLink = $videoLink;
        $this->mailer('info');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Join Your Upcoming Session - ' . $this->booking->invoice_no,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $title = 'Time to join your session!';
        $intro = 'Your online session is about to start. Please use the button below to join the video conference.';

        return new Content(
            view: 'emails.session-reminder',
            with: [
                'title' => $title,
                'intro' => $intro,
                'booking' => $this->booking,
                'type' => $this->type,
                'videoLink' => $this->videoLink,
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
