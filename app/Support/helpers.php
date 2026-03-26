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
