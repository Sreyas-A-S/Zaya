<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyConversionService
{
    /**
     * Convert an amount using a free rates endpoint.
     *
     * Note: truly "realtime" FX without an external data source is not possible.
     * This uses a free public endpoint and caches results to reduce calls.
     */
    public function convert(float $amount, string $from, string $to): ?array
    {
        $from = strtoupper(trim($from));
        $to = strtoupper(trim($to));

        if ($from === '' || $to === '') return null;
        if ($from === $to) {
            return [
                'from' => $from,
                'to' => $to,
                'rate' => 1.0,
                'converted' => $amount,
                'provider' => 'identity',
                'as_of' => now()->toDateString(),
            ];
        }

        $cacheKey = "fx:rate:v1:{$from}:{$to}";

        try {
            $rateData = Cache::remember($cacheKey, now()->addHours(12), function () use ($from, $to) {
                // Provider: open.er-api.com (free, no key). Returns:
                // { "result":"success", "time_last_update_utc": "...", "rates": { "INR": 83.1, ... } }
                $url = "https://open.er-api.com/v6/latest/{$from}";
                $response = Http::timeout(8)->get($url);

                if (!$response->successful()) return null;

                $json = $response->json();
                $rates = $json['rates'] ?? null;
                if (!is_array($rates) || !isset($rates[$to]) || !is_numeric($rates[$to])) return null;

                return [
                    'rate' => (float) $rates[$to],
                    'as_of' => $json['time_last_update_utc'] ?? ($json['time_last_update_unix'] ?? null),
                    'provider' => 'open.er-api.com',
                ];
            });

            if (!$rateData || !isset($rateData['rate'])) return null;

            $rate = (float) $rateData['rate'];
            if ($rate <= 0) return null;

            return [
                'from' => $from,
                'to' => $to,
                'rate' => $rate,
                'converted' => $amount * $rate,
                'provider' => $rateData['provider'] ?? 'open.er-api.com',
                'as_of' => $rateData['as_of'] ?? null,
            ];
        } catch (\Throwable $e) {
            return null;
        }
    }
}

