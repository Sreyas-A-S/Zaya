<?php

namespace App\Traits;

use App\Models\Transaction;
use App\Models\HomepageSetting;
use App\Models\ReferralCommissionRate;
use App\Models\User;
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
        
        $companyPercent = 0;
        $referrerPercent = 0;
        $countryId = $data['country_id'] ?? null;

        if ($type === 'booking') {
            // Get current settings (global)
            $settings = HomepageSetting::where('section', 'finance')->where('language', 'en')->pluck('value', 'key');
            $companyPercent = (float) ($settings['company_booking_commission'] ?? 10);
        } else {
            // Referral (country + role pair aware, with global fallback)
            $payerUser = isset($data['user_id']) ? User::find($data['user_id']) : null;
            if (!$countryId && $payerUser) {
                $countryId = $this->resolveCountryIdFromUser($payerUser);
            }

            $referrerRole = $data['referrer_role'] ?? null;
            if (!$referrerRole && !empty($data['referrer_id'])) {
                $referrerRole = User::whereKey($data['referrer_id'])->value('role');
            }

            $referredRole = $data['referred_role'] ?? null;
            if (!$referredRole && !empty($data['practitioner_id'])) {
                $referredRole = User::whereKey($data['practitioner_id'])->value('role');
            }

            $rate = null;
            if ($countryId && $referrerRole && $referredRole) {
                $rate = ReferralCommissionRate::where('country_id', $countryId)
                    ->where('referrer_role', $referrerRole)
                    ->where('referred_role', $referredRole)
                    ->first();
            }

            if ($rate) {
                $companyPercent = (float) $rate->company_commission_percent;
                $referrerPercent = (float) $rate->referrer_commission_percent;
            } else {
                $settings = HomepageSetting::where('section', 'finance')->where('language', 'en')->pluck('value', 'key');
                $companyPercent = (float) ($settings['company_referral_commission'] ?? 0);
                $referrerPercent = (float) ($settings['practitioner_referral_commission'] ?? 5);
            }
        }

        // Calculations
        $companyShare = ($amount * $companyPercent) / 100;
        $referrerShare = ($amount * $referrerPercent) / 100;
        $practitionerShare = $amount - $companyShare - $referrerShare;
        if ($practitionerShare < 0) $practitionerShare = 0;

        return Transaction::create([
            'transaction_no' => 'TRX-' . strtoupper(Str::random(12)),
            'user_id' => $data['user_id'],
            'practitioner_id' => $data['practitioner_id'],
            'referrer_id' => $data['referrer_id'] ?? null,
            'booking_id' => $data['booking_id'] ?? null,
            'referral_id' => $data['referral_id'] ?? null,
            'country_id' => $countryId,
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

    private function resolveCountryIdFromUser(User $user): ?int
    {
        $raw = $user->national_id ?? null;
        if ($raw === null) return null;

        $arr = $raw;
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            $arr = $decoded !== null ? $decoded : [$raw];
        }

        if (is_int($arr)) return $arr;

        if (!is_array($arr)) return null;

        foreach ($arr as $val) {
            if (is_numeric($val)) return (int) $val;
        }

        return null;
    }
}
