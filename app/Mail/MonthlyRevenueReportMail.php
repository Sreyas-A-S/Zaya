<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonthlyRevenueReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $practitioner;
    public $reportData;
    public $month;
    public $year;

    /**
     * Create a new message instance.
     */
    public function __construct($practitioner, $reportData, $month, $year)
    {
        $this->practitioner = $practitioner;
        $this->reportData = $reportData;
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Monthly Revenue Report - {$this->month} {$this->year} | Zaya Wellness",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.monthly-revenue-report',
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
