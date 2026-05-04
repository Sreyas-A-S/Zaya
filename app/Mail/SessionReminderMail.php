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
    public $session;
    public $isMissed;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, string $type, string $videoLink, array $session = null, bool $isMissed = false)
    {
        $this->booking = $booking;
        $this->type = $type;
        $this->videoLink = $videoLink;
        $this->session = $session;
        $this->isMissed = $isMissed;
        $this->mailer('info');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $sessionStr = '';
        if ($this->session) {
            $sessionStr = " (" . ($this->session['day'] ?? '') . " " . ($this->session['time'] ?? '') . ")";
        }

        $subjectPrefix = $this->isMissed ? 'MISSED SESSION: ' : 'Join Your Upcoming Session - ';

        return new Envelope(
            subject: $subjectPrefix . $this->booking->invoice_no . $sessionStr,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $timezone = derive_timezone_from_user($this->booking->user);

        $title = $this->isMissed ? 'You have missed a session' : 'Time to join your session!';
        $intro = $this->isMissed 
            ? 'We apologize for the technical issue with our previous notification. It seems your scheduled session has already passed.' 
            : 'Your online session is about to start. Please use the button below to join the video conference.';

        return new Content(
            view: 'emails.session-reminder',
            with: [
                'title' => $title,
                'intro' => $intro,
                'booking' => $this->booking,
                'type' => $this->type,
                'videoLink' => $this->videoLink,
                'timezone' => $timezone,
                'session' => $this->session,
                'isMissed' => $this->isMissed,
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
