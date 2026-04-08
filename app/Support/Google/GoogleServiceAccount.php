<?php

namespace App\Support\Google;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoogleServiceAccount
{
    public static function accessToken(array $scopes, int $ttlSeconds = 3500, ?string $impersonate = null): string
    {
        $cacheKey = 'google:sa_access_token:' . sha1(json_encode($scopes) . ($impersonate ?? ''));

        return Cache::remember($cacheKey, $ttlSeconds, function () use ($scopes, $impersonate) {
            $email = (string) config('services.google_drive.service_account_email');
            $privateKey = (string) config('services.google_drive.service_account_private_key');
            $privateKey = str_replace("\\n", "\n", $privateKey);

            if ($email === '' || $privateKey === '') {
                throw new \RuntimeException('Google service account is not configured.');
            }

            $now = time();
            $claims = [
                'iss' => $email,
                'scope' => implode(' ', $scopes),
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ];

            if ($impersonate) {
                $claims['sub'] = $impersonate;
            }

            $jwt = self::jwt($claims, $privateKey);

            $response = Http::asForm()
                ->timeout(20)
                ->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

            if (!$response->successful()) {
                throw new \RuntimeException('Failed to obtain Google access token: ' . $response->body());
            }

            $json = $response->json();
            $token = (string) ($json['access_token'] ?? '');
            if ($token === '') {
                throw new \RuntimeException('Google access token missing in response.');
            }

            return $token;
        });
    }

    private static function jwt(array $claims, string $privateKey): string
    {
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $segments = [
            self::base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES)),
            self::base64UrlEncode(json_encode($claims, JSON_UNESCAPED_SLASHES)),
        ];
        $signingInput = implode('.', $segments);

        $signature = '';
        $ok = openssl_sign($signingInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        if (!$ok) {
            throw new \RuntimeException('Failed to sign JWT for Google service account.');
        }

        $segments[] = self::base64UrlEncode($signature);

        return implode('.', $segments);
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

