<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $type; // 'client', 'practitioner', 'translator'

    public function __construct(Booking $booking, string $type)
    {
        $this->booking = $booking->load(['user', 'practitioner.user', 'translator.user']);
        $this->type = $type;
        $this->mailer('info');
    }

    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'client' => 'Booking Confirmed - ' . $this->booking->invoice_no,
            'practitioner' => 'New Session Booking - ' . $this->booking->invoice_no,
            'translator' => 'New Translation Request - ' . $this->booking->invoice_no,
            default => 'Booking Update - ' . $this->booking->invoice_no,
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        $title = match($this->type) {
            'client' => 'Your session is confirmed!',
            'practitioner' => 'You have a new booking!',
            'translator' => 'New translation assignment!',
            default => 'Booking Details',
        };

        $intro = match($this->type) {
            'client' => 'Thank you for booking with Zaya Wellness. Your session details are below.',
            'practitioner' => 'A new session has been booked with you. Please review the details below.',
            'translator' => 'You have been assigned as a translator for an upcoming session.',
            default => 'Details for booking ' . $this->booking->invoice_no,
        };

        return new Content(
            view: 'emails.booking',
            with: [
                'title' => $title,
                'intro' => $intro,
                'booking' => $this->booking,
                'type' => $this->type,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
