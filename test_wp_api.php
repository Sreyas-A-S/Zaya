<?php
use Illuminate\Support\Facades\Http;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

echo "Testing WordPress API connection...\n";

try {
    $url = 'https://blog.zayawellness.com/wp-json/wp/v2/posts?per_page=1';
    $response = Http::withoutVerifying()->timeout(10)->get($url);

    if ($response->successful()) {
        echo "Success! Status Code: " . $response->status() . "\n";
        $data = $response->json();
        if (!empty($data)) {
            echo "First post title: " . $data[0]['title']['rendered'] . "\n";
            echo "Headers:\n";
            echo "X-WP-Total: " . $response->header('X-WP-Total') . "\n";
            echo "X-WP-TotalPages: " . $response->header('X-WP-TotalPages') . "\n";
        } else {
            echo "Response successful but no posts found.\n";
        }
    } else {
        echo "Failed. Status Code: " . $response->status() . "\n";
        echo "Body: " . substr($response->body(), 0, 200) . "...\n";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
