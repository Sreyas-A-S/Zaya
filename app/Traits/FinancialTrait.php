<?php

namespace App\Traits;

use App\Models\Transaction;
use App\Models\HomepageSetting;
use Illuminate\Support\Str;

trait FinancialTrait
{
    /**
     * Record a transaction with percentage breakdown based on current settings.
     */
    public function recordTransaction($data)
    {
        $type = $data['type'] ?? 'booking'; // booking, referral
        $amount = $data['amount'];
        
        // Get current settings
        $settings = HomepageSetting::where('section', 'finance')->where('language', 'en')->pluck('value', 'key');
        
        $companyPercent = 0;
        $referrerPercent = 0;

        if ($type === 'booking') {
            $companyPercent = (float) ($settings['company_booking_commission'] ?? 10);
        } else {
            // Referral
            $companyPercent = (float) ($settings['company_referral_commission'] ?? 0);
            $referrerPercent = (float) ($settings['practitioner_referral_commission'] ?? 5);
        }

        // Calculations
        $companyShare = ($amount * $companyPercent) / 100;
        $referrerShare = ($amount * $referrerPercent) / 100;
        $practitionerShare = $amount - $companyShare - $referrerShare;

        return Transaction::create([
            'transaction_no' => 'TRX-' . strtoupper(Str::random(12)),
            'user_id' => $data['user_id'],
            'practitioner_id' => $data['practitioner_id'],
            'referrer_id' => $data['referrer_id'] ?? null,
            'booking_id' => $data['booking_id'] ?? null,
            'referral_id' => $data['referral_id'] ?? null,
            'total_amount' => $amount,
            'currency' => $data['currency'] ?? 'INR',
            'company_share' => $companyShare,
            'practitioner_share' => $practitionerShare,
            'referrer_share' => $referrerShare,
            'company_commission_percent' => $companyPercent,
            'referrer_commission_percent' => $referrerPercent,
            'payment_id' => $data['payment_id'] ?? null,
            'status' => 'completed',
            'type' => $type,
        ]);
    }
}
