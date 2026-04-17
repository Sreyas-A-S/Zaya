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

        $currency = $feeCurrency ?? $this->deriveCurrencyFromUser($user);

        $notes = array_merge([
            'user_id' => $user->id,
            'role' => $role,
            'source' => 'admin_registration',
        ], $extraNotes);

        try {
            $response = Http::withOptions(['verify' => (bool) $verifySsl])
                ->withBasicAuth($razorpayKey, $razorpaySecret)
                ->post('https://api.razorpay.com/v1/payment_links', [
                    'amount' => (int) round($fee * 100),
                    'currency' => $currency,
                    'description' => 'Registration Fee - ' . $map[$role]['label'],
                    'customer' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'contact' => $user->phone ?? '',
                    ],
                    'callback_url' => route('admin.registration-fees.callback'),
                    'callback_method' => 'get',
                    'notes' => $notes,
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
        $country = null;
        if (isset($user->country)) {
            $country = $user->country;
        } elseif (isset($user->patient) && $user->patient) {
            $country = $user->patient->country;
        } elseif (isset($user->practitioner) && $user->practitioner) {
            $country = $user->practitioner->country;
        }

        if ($country) {
            // If it's a name, we might need to map it back to a code
            // For now, let's assume it's a code if length is 2, or try to find it
            if (strlen($country) === 2) {
                return strtoupper($country);
            }
            
            $dbCountry = \App\Models\Country::where('name', $country)->first();
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
