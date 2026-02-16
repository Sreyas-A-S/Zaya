<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WordPressBlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogCacheController extends Controller
{
    protected $blogService;

    public function __construct(WordPressBlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    /**
     * Clear the blog cache via API.
     * Validates the request using a secret header.
     */
    public function clear(Request $request)
    {
        $secret = $request->header('X-WP-Cache-Secret');
        $configuredSecret = config('services.wordpress.cache_secret');

        if (!$configuredSecret) {
            Log::warning('WordPress Cache Secret not configured in Laravel.');
            return response()->json(['error' => 'Server configuration error'], 500);
        }

        if ($secret !== $configuredSecret) {
            Log::warning('Invalid Blog Cache Clear Attempt', ['ip' => $request->ip()]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $this->blogService->clearCache();
            return response()->json(['message' => 'Blog cache cleared successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to clear blog cache: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to clear cache'], 500);
        }
    }
}
