<?php

namespace App\Mail;

use App\Models\Referral;
use App\Models\Service;
use App\Services\CurrencyConversionService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReferralInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $referral;

    /**
     * Create a new message instance.
     */
    public function __construct(Referral $referral)
    {
        $this->referral = $referral->load(['user', 'referredBy', 'referredTo']);
        $this->mailer('info');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Session Referral: ' . $this->referral->referredTo->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $serviceTitles = [];
        try {
            $serviceIds = (array) ($this->referral->service_ids ?? []);
            $serviceIds = array_values(array_filter($serviceIds, fn ($v) => is_numeric($v)));
            if (!empty($serviceIds)) {
                $serviceTitles = Service::whereIn('id', $serviceIds)->pluck('title')->filter()->values()->all();
            }
        } catch (\Throwable $e) {
            $serviceTitles = [];
        }

        $expertCurrency = strtoupper((string) ($this->referral->currency ?? ($this->referral->referredTo->profile->payout_currency ?? '')));
        if ($expertCurrency === '') {
            $expertCurrency = derive_currency_from_user($this->referral->referredTo);
        }

        $clientCurrency = derive_currency_from_user($this->referral->user);

        $converted = null;
        if ($expertCurrency && $clientCurrency && $expertCurrency !== $clientCurrency) {
            $converted = app(CurrencyConversionService::class)->convert((float) $this->referral->amount, $expertCurrency, $clientCurrency);
        }

        return new Content(
            view: 'emails.referral-invitation',
            with: [
                'referral' => $this->referral,
                'payUrl' => route('referrals.pay', $this->referral->referral_no),
                'serviceTitles' => $serviceTitles,
                'expertCurrency' => $expertCurrency,
                'clientCurrency' => $clientCurrency,
                'converted' => $converted,
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
