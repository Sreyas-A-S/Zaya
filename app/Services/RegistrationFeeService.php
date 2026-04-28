<?php

namespace App\Services;

use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Http;

class RegistrationFeeService
{
    public function createPaymentLink($user, string $role, ?float $amountOverride = null, array $extraNotes = []): ?array
    {
        $map = [
            'practitioner' => ['fee' => 'practitioner_registration_fee', 'enabled' => 'practitioner_registration_fee_enabled', 'label' => 'Practitioner'],
            'doctor' => ['fee' => 'doctor_registration_fee', 'enabled' => 'doctor_registration_fee_enabled', 'label' => 'Doctor'],
            'mindfulness_practitioner' => ['fee' => 'mindfulness_registration_fee', 'enabled' => 'mindfulness_registration_fee_enabled', 'label' => 'Mindfulness Counsellor'],
            'yoga_therapist' => ['fee' => 'yoga_registration_fee', 'enabled' => 'yoga_registration_fee_enabled', 'label' => 'Yoga Therapist'],
            'translator' => ['fee' => 'translator_registration_fee', 'enabled' => 'translator_registration_fee_enabled', 'label' => 'Translator'],
            'client' => ['fee' => 'client_registration_fee', 'enabled' => 'client_registration_fee_enabled', 'label' => 'Client'],
        ];

        if (!isset($map[$role])) {
            return null;
        }

        $language = session('locale', 'en');
        $countryCode = $this->deriveCountryCodeFromUser($user);
        $settings = HomepageSetting::getSectionValues('finance', $language, $countryCode);
        
        $fee = (float) ($settings[$map[$role]['fee']] ?? 0);
        if (is_numeric($amountOverride)) {
            $fee = max(0.0, (float) $amountOverride);
        }

        $currencyKey = $map[$role]['fee'] . '_currency';
        $feeCurrency = strtoupper($settings[$currencyKey] ?? config('currencies.default', 'EUR'));
        $enabled = filter_var($settings[$map[$role]['enabled']] ?? '1', FILTER_VALIDATE_BOOLEAN);

        if (!$enabled || $fee <= 0) {
            return null;
        }

        $razorpayKey = config('services.razorpay.key');
        $razorpaySecret = config('services.razorpay.secret');
        $paymentConfigured = $razorpayKey && $razorpaySecret && !str_contains((string) $razorpayKey, 'dummy');
        if (!$paymentConfigured) {
            return null;
        }

        $verifySsl = config('services.razorpay.verify_ssl');
        if ($verifySsl === null) {
            $verifySsl = !app()->environment('local');
        }

        $orderId = 'mock_plink_' . uniqid();
        $paymentUrl = null;

        // Use fee currency if available, otherwise derive from user country
        $currency = $feeCurrency;
// Fetch phone and currency from related profiles if not on user
$phone = $user->phone ?? $user->mobile ?? '';
$payoutCurrency = $user->payout_currency ?? $user->currency ?? $currency;

if (!$phone) {
    if (($role === 'practitioner' || ($user->role ?? '') === 'practitioner') && $user->practitioner) {
        $phone = $user->practitioner->phone;
        $payoutCurrency = $user->practitioner->payout_currency ?? $payoutCurrency;
    } elseif (($role === 'client' || ($user->role ?? '') === 'patient' || ($user->role ?? '') === 'client') && $user->patient) {
        $phone = $user->patient->phone;
    }
}

$notes = array_merge([
    'user_id' => $user->id,
    'role' => $role,
    'source' => 'registration',
    'payout_currency' => (string) $payoutCurrency,
], $extraNotes);

$paymentUrl = null;
$orderId = null;

try {
    $response = Http::withOptions(['verify' => (bool) $verifySsl])
        ->withBasicAuth($razorpayKey, $razorpaySecret)
        ->post('https://api.razorpay.com/v1/payment_links', [
            'amount' => (int) round($fee * 100),
            'currency' => $currency,
            'description' => 'Registration Fee - ' . ($map[$role]['label'] ?? ucfirst($role)),
            'customer' => [
                'name' => (string) ($user->name ?? 'User'),
                'email' => (string) $user->email,
                'contact' => (string) ($phone ?: ''),
            ],
            'notify' => [
                'sms' => false,
                'email' => true,
            ],
            'callback_url' => route('registration-fees.callback'),
            'callback_method' => 'get',
            'notes' => array_map('strval', $notes),
        ]);

        if ($response->successful()) {
            $paymentUrl = $response->json('short_url');
            $orderId = $response->json('id');
        } else {
            \Log::error('Razorpay Payment Link Error: ' . $response->body());
        }
    } catch (\Exception $e) {
        \Log::error('Razorpay Connection Error: ' . $e->getMessage());
    }

        if (!$paymentUrl) {
            return null;
        }

        return [
            'payment_url' => $paymentUrl,
            'order_id' => $orderId,
            'amount' => $fee,
            'currency' => $currency,
            'role_label' => $map[$role]['label'],
        ];
    }

    private function deriveCountryCodeFromUser($user): string
    {
        // Try to get country name from user or their related profile
        $countryName = $user->country;

        if (!$countryName) {
            if ($user->role === 'practitioner' && $user->practitioner) {
                $countryName = $user->practitioner->country;
            } elseif ($user->role === 'doctor' && $user->doctor) {
                $countryName = $user->doctor->country;
            } elseif ($user->role === 'mindfulness_practitioner' && $user->mindfulnessPractitioner) {
                $countryName = $user->mindfulnessPractitioner->country;
            } elseif ($user->role === 'yoga_therapist' && $user->yogaTherapist) {
                $countryName = $user->yogaTherapist->country;
            } elseif ($user->role === 'translator' && $user->translator) {
                $countryName = $user->translator->country;
            } elseif (($user->role === 'client' || $user->role === 'patient') && $user->patient) {
                $countryName = $user->patient->country;
            }
        }

        if ($countryName) {
            $dbCountry = \App\Models\Country::where('name', $countryName)
                ->orWhere('code', strtoupper($countryName))
                ->first();
            if ($dbCountry) {
                return strtoupper($dbCountry->code);
            }
        }
        return 'all';
    }

    private function deriveCurrencyFromUser($user): string
    {
        $country = null;
        if (isset($user->country)) {
            $country = $user->country;
        } elseif (isset($user->patient) && $user->patient) {
            $country = $user->patient->country;
        } elseif (isset($user->practitioner) && $user->practitioner) {
            $country = $user->practitioner->country;
        }

        $map = config('currencies.country_to_currency', []);
        if ($country) {
            $code = strtoupper(trim($country));
            return $map[$code] ?? $map[substr($code, 0, 2)] ?? config('currencies.default', 'INR');
        }
        return config('currencies.default', 'INR');
    }
}
