<?php

namespace App\Traits;

use App\Models\Transaction;
use App\Models\HomepageSetting;
use App\Models\ReferralCommissionRate;
use App\Models\User;
use App\Models\OpenRegisterLink;
use Illuminate\Support\Str;

trait FinancialTrait
{
    /**
     * Record a transaction with percentage breakdown based on current settings.
     */
    public function recordTransaction($data)
    {
        $type = $data['type'] ?? 'booking'; // booking (direct), referral
        $amount = (float) $data['amount'];
        
        $companyPercent = 0;
        $referrerPercent = 0;
        $countryId = null; // We will resolve this strictly from the practitioner/expert
        $referrerRole = $data['referrer_role'] ?? null;
        $referrerId = $data['referrer_id'] ?? null;

        // Resolve Country ID strictly from the Expert (Practitioner/Doctor/etc.)
        // Commission rates are based on the provider's location.
        if (!empty($data['practitioner_id'])) {
            $practitionerUser = User::find($data['practitioner_id']);
            if ($practitionerUser) {
                $countryId = $this->resolveCountryIdFromProfile($practitionerUser);
            }
        }

        // If no practitioner (e.g. registration fee), use the user's own country
        if (!$countryId) {
            $payerUser = isset($data['user_id']) ? User::find($data['user_id']) : null;
            if ($payerUser) {
                $countryId = $this->resolveCountryIdFromUser($payerUser);
            }
        }

        $payerUser = $payerUser ?? (isset($data['user_id']) ? User::find($data['user_id']) : null);
        
        // Persistent Referrer Check
        if (!$referrerId && $payerUser && $payerUser->open_register_link_id) {
            $link = OpenRegisterLink::find($payerUser->open_register_link_id);
            if ($link && $link->created_by) {
                $referrerId = $link->created_by;
                // If it's a booking by a referred user, we treat it as a referral transaction
                $type = 'referral';
                if (!$referrerRole) {
                    $referrerRole = User::whereKey($referrerId)->value('role');
                }
            }
        }

        $practitionerRole = $data['referred_role'] ?? null;
        if (!$practitionerRole && !empty($data['practitioner_id'])) {
            $practitionerRole = User::whereKey($data['practitioner_id'])->value('role');
        }
        
        // Map user roles to commission roles if needed
        $roleMap = [
            'doctor' => 'doctor',
            'practitioner' => 'practitioner',
            'mindfulness_practitioner' => 'mindfulness_practitioner',
            'yoga_therapist' => 'yoga_therapist',
            'translator' => 'translator'
        ];
        $practitionerRole = $roleMap[$practitionerRole] ?? $practitionerRole;

        if ($type === 'booking') {
            // Direct Booking
            $rate = null;
            if ($countryId && $practitionerRole) {
                $rate = ReferralCommissionRate::where('country_id', $countryId)
                    ->where('type', 'direct')
                    ->where('referred_role', $practitionerRole)
                    ->first();
            }

            // Fallback to Global Role-wise Settings (country_id IS NULL)
            if (!$rate && $practitionerRole) {
                $rate = ReferralCommissionRate::whereNull('country_id')
                    ->where('type', 'direct')
                    ->where('referred_role', $practitionerRole)
                    ->first();
            }

            if ($rate) {
                $companyPercent = (float) $rate->company_commission_percent;
            } else {
                // Final fallback to hardcoded global settings
                $settings = HomepageSetting::where('section', 'finance')->where('language', 'en')->where('country_code', 'all')->pluck('value', 'key');
                $companyPercent = (float) ($settings['company_booking_commission'] ?? 10);
            }
            $referrerPercent = 0;
        } elseif ($type === 'registration') {
            // Registration Fee
            $companyPercent = 100;
            $referrerPercent = 0;
        } else {
            // Referral Booking
            // The admin UI currently configures referral commissions primarily for referrer_role='practitioner'.
            // If a non-practitioner referred, we first try that role, then fallback to 'practitioner'.
            $rate = null;
            if ($countryId && $practitionerRole) {
                $rate = ReferralCommissionRate::where('country_id', $countryId)
                    ->where('type', 'referral')
                    ->where('referrer_role', $referrerRole ?: 'practitioner')
                    ->where('referred_role', $practitionerRole)
                    ->first();

                if (!$rate && $referrerRole && $referrerRole !== 'practitioner') {
                    $rate = ReferralCommissionRate::where('country_id', $countryId)
                        ->where('type', 'referral')
                        ->where('referrer_role', 'practitioner')
                        ->where('referred_role', $practitionerRole)
                        ->first();
                }
            }

            // Fallback to Global Role-wise Settings (country_id IS NULL)
            if (!$rate && $practitionerRole) {
                $rate = ReferralCommissionRate::whereNull('country_id')
                    ->where('type', 'referral')
                    ->where('referrer_role', $referrerRole ?: 'practitioner')
                    ->where('referred_role', $practitionerRole)
                    ->first();

                if (!$rate && $referrerRole && $referrerRole !== 'practitioner') {
                    $rate = ReferralCommissionRate::whereNull('country_id')
                        ->where('type', 'referral')
                        ->where('referrer_role', 'practitioner')
                        ->where('referred_role', $practitionerRole)
                        ->first();
                }
            }

            if ($rate) {
                $companyPercent = (float) $rate->company_commission_percent;
                $referrerPercent = (float) $rate->referrer_commission_percent;
            } else {
                // Final fallback to hardcoded global settings
                $settings = HomepageSetting::where('section', 'finance')->where('language', 'en')->where('country_code', 'all')->pluck('value', 'key');
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
            'referrer_id' => $referrerId,
            'booking_id' => $data['booking_id'] ?? null,
            'referral_id' => $data['referral_id'] ?? null,
            'country_id' => $countryId,
            'total_amount' => $amount,
            'subtotal' => $data['subtotal'] ?? $amount,
            'currency' => $data['currency'] ?? 'INR',
            'company_share' => $companyShare,
            'practitioner_share' => $practitionerShare,
            'referrer_share' => $referrerShare,
            'company_commission_percent' => $companyPercent,
            'referrer_commission_percent' => $referrerPercent,
            'payment_id' => $data['payment_id'] ?? null,
            'coins_used' => $data['coins_used'] ?? 0,
            'coin_discount' => $data['coin_discount'] ?? 0,
            'status' => 'completed',
            'type' => $type,
        ]);
    }

    private function resolveCountryIdFromProfile(User $user): ?int
    {
        $countryName = null;
        $profile = $user->profile;
        
        if ($profile) {
            $countryName = $profile->country ?? null;
        }

        if ($countryName) {
            $dbCountry = \App\Models\Country::where('name', $countryName)
                ->orWhere('code', strtoupper($countryName))
                ->first();
            
            if ($dbCountry) {
                return $dbCountry->id;
            }
        }

        // Fallback to user-level national_id if profile country is missing or unmapped
        return $this->resolveCountryIdFromUser($user);
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

    /**
     * Award referral bonus coins to the referrer of the given user.
     * Aligned with Scenario 2: awarded on first confirmed booking.
     */
    public function awardReferralBonus(User $referredUser)
    {
        if (!$referredUser->referred_by) {
            return;
        }

        $referrer = User::find($referredUser->referred_by);
        if (!$referrer) {
            return;
        }

        // Check if bonus already awarded for this referred user (prevent multiple bonuses)
        $alreadyAwarded = \App\Models\CoinTransaction::where('user_id', $referrer->id)
            ->where('type', 'referral_bonus')
            ->where('metadata->referred_user_id', $referredUser->id)
            ->exists();

        if ($alreadyAwarded) {
            return;
        }

        $referrerCurrency = $referrer->profile ? $referrer->profile->payout_currency : config('currencies.default', 'INR');
        $coinSetting = \App\Models\CoinSetting::where('currency_code', $referrerCurrency)->where('status', true)->first();

        if ($coinSetting && $coinSetting->referral_coins > 0) {
            $referrer->increment('coins', $coinSetting->referral_coins);

            \App\Models\CoinTransaction::create([
                'user_id' => $referrer->id,
                'amount' => $coinSetting->referral_coins,
                'type' => 'referral_bonus',
                'description' => 'Referral bonus for ' . $referredUser->name . ' (Confirmed Booking)',
                'metadata' => [
                    'referred_user_id' => $referredUser->id,
                    'referred_user_name' => $referredUser->name,
                ]
            ]);
        }
    }
}
