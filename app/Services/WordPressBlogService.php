<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WordPressBlogService
{
    protected $cacheDuration = 60; // 60 minutes
    protected $cacheVersionKey = 'wp_blog_cache_version';

    /**
     * Get the current cache version.
     * Incrementing this invalidates all blog cache.
     */
    protected function getCacheVersion()
    {
        return Cache::rememberForever($this->cacheVersionKey, function () {
            return time();
        });
    }

    /**
     * Clear all blog cache by updating the version key.
     */
    public function clearCache()
    {
        Cache::put($this->cacheVersionKey, time());
        Log::info('WordPress Blog Cache Cleared (Version Updated)');
    }

    /**
     * WordPress API Base URL
     */
    protected function getWordPressApiUrl()
    {
        return config('services.wordpress.api_url');
    }

    /**
     * Fetch data from WordPress REST API with caching.
     */
    public function fetchFromWordPress($endpoint, $params = [], $withHeaders = false)
    {
        $version = $this->getCacheVersion();

        // Create a unique cache key based on version, endpoint, and params
        $keyPayload = [
            'endpoint' => $endpoint,
            'params' => $params,
            'withHeaders' => $withHeaders,
            'version' => $version // Include version in key
        ];

        $cacheKey = 'wp_blog_' . md5(json_encode($keyPayload));

        return Cache::remember($cacheKey, now()->addMinutes($this->cacheDuration), function () use ($endpoint, $params, $withHeaders) {
            try {
                $url = $this->getWordPressApiUrl() . '/' . $endpoint;
                $verifySsl = config('services.wordpress.verify_ssl', true);

                $request = Http::withHeaders([
                    'User-Agent' => 'ZayaWellness/1.0',
                    'Accept' => 'application/json',
                ])->timeout(10); // 10 second timeout

                if (!$verifySsl) {
                    $request->withoutVerifying();
                }

                $response = $request->get($url, $params);

                if ($response->failed()) {
                    Log::error('WordPress API Error: ' . $response->body());
                    return $withHeaders ? ['data' => [], 'headers' => []] : [];
                }

                $data = json_decode($response->body());

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('WordPress API JSON Decode Error: ' . json_last_error_msg());
                    return $withHeaders ? ['data' => [], 'headers' => []] : [];
                }

                if ($withHeaders) {
                    $headers = [
                        'total' => (int) ($response->header('X-WP-Total') ?? 0),
                        'totalPages' => (int) ($response->header('X-WP-TotalPages') ?? 0),
                    ];
                    return ['data' => $data, 'headers' => $headers];
                }

                return $data;
            } catch (\Exception $e) {
                Log::error('WordPress API Exception: ' . $e->getMessage());
                return $withHeaders ? ['data' => [], 'headers' => []] : [];
            }
        });
    }

    /**
     * Fetch single post by slug with caching
     */
    public function getPostBySlug($slug)
    {
        // Cache indefinitely until cleared, or use the standard duration
        // Using standard fetchFromWordPress handles the caching
        $posts = $this->fetchFromWordPress('posts', [
            'slug' => $slug,
            '_embed' => 1
        ]);

        return ($posts && count($posts) > 0) ? $posts[0] : null;
    }
}
