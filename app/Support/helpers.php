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
