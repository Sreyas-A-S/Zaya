<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeoIpController extends Controller
{
    public function country(Request $request)
    {
        $ip = (string) $request->ip();

        $country = Cache::remember('geoip_country_' . sha1($ip), now()->addHours(6), function () use ($ip) {
            // Skip lookups for local/private IPs.
            if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.') || str_starts_with($ip, '172.16.')) {
                return null;
            }

            try {
                $response = Http::timeout(3)->get('https://ipapi.co/' . urlencode($ip) . '/json/');
                if (!$response->successful()) {
                    return null;
                }
                $code = strtoupper((string) ($response->json('country_code') ?? ''));
                if (preg_match('/^[A-Z]{2}$/', $code) !== 1) {
                    return null;
                }
                return $code;
            } catch (\Exception $e) {
                return null;
            }
        });

        return response()->json([
            'country_code' => $country ?: 'IN',
        ]);
    }
}

