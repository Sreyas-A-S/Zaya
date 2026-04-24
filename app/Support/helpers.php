<?php

use Illuminate\Contracts\Encryption\DecryptException;

if (! function_exists('zaya_encrypt')) {
    /**
     * Encrypt arbitrary data using Laravel's encrypter
     * backed by APP_KEY and AES-256-CBC.
     */
    function zaya_encrypt(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return encrypt($value);
    }
}

if (! function_exists('zaya_decrypt')) {
    /**
     * Decrypt data previously encrypted with zaya_encrypt().
     */
    function zaya_decrypt(?string $payload, mixed $default = null): mixed
    {
        if ($payload === null || $payload === '') {
            return $default;
        }

        try {
            return decrypt($payload);
        } catch (DecryptException) {
            return $default;
        }
    }
}

if (! function_exists('get_currency_symbol')) {
    /**
     * Get currency symbol from code.
     */
    function get_currency_symbol(string $code): string
    {
        $symbols = config('currencies.symbols', []);
        return $symbols[strtoupper($code)] ?? $code;
    }
}

if (! function_exists('derive_currency_from_user')) {
    /**
     * Derive a best-guess currency (ISO 4217) from user/profile country codes.
     */
    function derive_currency_from_user($user): string
    {
        $country = null;

        try {
            if (isset($user->country)) {
                $country = $user->country;
            } elseif (isset($user->patient) && $user->patient) {
                $country = $user->patient->country ?? null;
            } elseif (isset($user->practitioner) && $user->practitioner) {
                $country = $user->practitioner->country ?? null;
            } elseif (isset($user->doctor) && $user->doctor) {
                $country = $user->doctor->country ?? null;
            } elseif (isset($user->profile) && $user->profile) {
                $country = $user->profile->country ?? null;
            }
        } catch (\Throwable $e) {
            $country = null;
        }

        $map = config('currencies.country_to_currency', []);
        $fallback = config('currencies.default', config('app.currency', 'INR'));

        if (!$country) return strtoupper($fallback);

        $code = strtoupper(trim((string) $country));
        if (isset($map[$code])) return strtoupper($map[$code]);

        $alpha2 = strtoupper(substr($code, 0, 2));
        if (isset($map[$alpha2])) return strtoupper($map[$alpha2]);

        return strtoupper($fallback);
    }
}

if (! function_exists('get_timezone_from_country')) {
    /**
     * Get primary timezone from country code.
     */
    function get_timezone_from_country(?string $country): string
    {
        if (!$country) return config('app.timezone', 'UTC');

        $country = trim($country);
        $code = null;

        if (strlen($country) === 2) {
            $code = strtoupper($country);
        } else {
            // Try to find code from name if the input is a full name (e.g. "India")
            try {
                $countryModel = \App\Models\Country::where('name', $country)
                    ->orWhere('code', $country)
                    ->first();
                
                if ($countryModel && strlen(trim($countryModel->code)) === 2) {
                    $code = strtoupper(trim($countryModel->code));
                }
            } catch (\Throwable $e) {
                $code = null;
            }
        }

        if ($code && strlen($code) === 2) {
            try {
                // Argument #2 must be a two-letter ISO 3166-1 compatible country code
                $tzs = @DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $code);

                if (!empty($tzs)) {
                    // Special cases for some countries if needed
                    if ($code === 'IN') return 'Asia/Kolkata';
                    if ($code === 'AE') return 'Asia/Dubai';
                    if ($code === 'GB') return 'Europe/London';
                    if ($code === 'US') return 'America/New_York'; // Default to East Coast for US

                    return $tzs[0];
                }
            } catch (\Throwable $e) {
                // Fallback to default on any error
            }
        }

        return config('app.timezone', 'UTC');
    }
}

if (! function_exists('derive_timezone_from_user')) {
    /**
     * Derive timezone from user/profile country.
     */
    function derive_timezone_from_user($user): string
    {
        $country = null;

        try {
            if (isset($user->country)) {
                $country = $user->country;
            } elseif (isset($user->patient) && $user->patient) {
                $country = $user->patient->country ?? null;
            } elseif (isset($user->practitioner) && $user->practitioner) {
                $country = $user->practitioner->country ?? null;
            } elseif (isset($user->doctor) && $user->doctor) {
                $country = $user->doctor->country ?? null;
            } elseif (isset($user->profile) && $user->profile) {
                $country = $user->profile->country ?? null;
            }
        } catch (\Throwable $e) {
            $country = null;
        }

        return get_timezone_from_country($country);
    }
}
