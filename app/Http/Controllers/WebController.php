<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\BlogLike;

class WebController extends Controller
{
    //
    public function index()
    {
        $practitioners = \App\Models\Practitioner::with(['user', 'reviews'])
            ->latest()
            ->take(8)
            ->get();
        $testimonials = \App\Models\Testimonial::where('status', true)->latest()->get();
        $services = \App\Models\Service::where('status', true)->orderBy('order_column')->get();
        $settings = \App\Models\HomepageSetting::pluck('value', 'key');

        return view('index', compact('practitioners', 'testimonials', 'services', 'settings'));
    }

    public function comingSoon()
    {
        return view('coming-soon');
    }

    public function aboutUs()
    {
        $settings = \App\Models\HomepageSetting::pluck('value', 'key');
        return view('about', compact('settings'));
    }

    public function services(Request $request)
    {
        $settings = \App\Models\HomepageSetting::where('section', 'services_page')->pluck('value', 'key');
        $query = \App\Models\Service::where('status', true);

        if ($request->filled('category')) {
            $categoryName = $request->category;
            $query->whereHas('categories', function ($q) use ($categoryName) {
                $q->where('name', $categoryName);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        $services = $query->orderBy('order_column', 'asc')->get();

        if ($request->ajax()) {
            return view('partials.frontend.services-grid', compact('services'))->render();
        }

        return view('services', compact('settings', 'services'));
    }

    public function practitionerDetail($id)
    {
        $practitioner = \App\Models\Practitioner::with(['user', 'reviews'])->findOrFail($id);
        return view('practitioner-detail', compact('practitioner'));
    }

    public function zayaLogin()
    {
        return view('zaya-login');
    }

    public function clientRegister()
    {
        return view('client-register');
    }

    public function practitionerRegister()
    {
        return view('practitioner-register');
    }

    public function serviceDetail($slug)
    {
        $service = \App\Models\Service::with('images')->where('slug', $slug)->where('status', true)->firstOrFail();
        $otherServices = \App\Models\Service::where('slug', '!=', $slug)->where('status', true)->inRandomOrder()->take(4)->get();

        return view('service-detail', compact('service', 'otherServices'));
    }

    public function bookSession()
    {
        return view('book-session');
    }

    public function contactUs()
    {
        return view('contact-us');
    }

    /**
     * WordPress API Base URL
     */
    protected function getWordPressApiUrl()
    {
        return config('services.wordpress.api_url');
    }

    /**
     * Fetch data from WordPress REST API
     */
    protected function fetchFromWordPress($endpoint, $params = [], $withHeaders = false)
    {
        try {
            $url = $this->getWordPressApiUrl() . '/' . $endpoint;
            $verifySsl = config('services.wordpress.verify_ssl', true);

            $request = Http::withHeaders([
                'User-Agent' => 'ZayaWellness/1.0',
                'Accept' => 'application/json',
            ])->timeout(10);

            if (!$verifySsl) {
                $request->withoutVerifying();
            }

            $response = $request->get($url, $params);

            if ($response->failed()) {
                Log::error('WordPress API Error: ' . $response->body());
                return $withHeaders ? ['data' => [], 'headers' => []] : [];
            }

            $data = $response->json(); // Returns array by default, we might need object for current logic

            // Convert to object to match existing logic if needed, or update logic.
            // existing logic uses $post->title->rendered. json_decode default is object.
            // Http::json() returns array. json_decode($response->body()) returns object.
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
    }

    /**
     * Get featured image URL from WordPress media
     */
    private function getFeaturedImage($mediaId)
    {
        if (!$mediaId) {
            return null;
        }

        $media = $this->fetchFromWordPress('media/' . $mediaId);

        if ($media && isset($media->source_url)) {
            return $media->source_url;
        }

        return null;
    }

    /**
     * Blogs listing page - fetches posts from WordPress
     */
    public function blogs(Request $request)
    {
        $settings = \App\Models\HomepageSetting::pluck('value', 'key');

        $currentPage = (int) $request->get('page', 1);
        $perPage = 9;

        // Build API params
        $params = [
            'per_page' => $perPage,
            'page' => $currentPage,
            '_embed' => 1, // Include featured media and categories
        ];

        // Category filter
        if ($request->filled('category')) {
            // First get category ID by name
            $categories = $this->fetchFromWordPress('categories', ['search' => $request->category]);
            if ($categories && count($categories) > 0) {
                $params['categories'] = $categories[0]->id;
            }
        }

        // Search filter
        $searchQuery = '';
        if ($request->filled('search')) {
            $searchQuery = $request->search;
            $params['search'] = $searchQuery;
        }

        // Fetch posts from WordPress with headers for pagination
        $response = $this->fetchFromWordPress('posts', $params, true);
        $posts = $response['data'];
        $headers = $response['headers'];

        // Pagination data
        $totalPosts = $headers['total'] ?? 0;
        $totalPages = $headers['totalPages'] ?? 1;

        // Fetch all categories for the sidebar and decode HTML entities
        $rawCategories = $this->fetchFromWordPress('categories', ['per_page' => 50]);
        $categories = [];
        if ($rawCategories) {
            foreach ($rawCategories as $cat) {
                $cat->name = html_entity_decode($cat->name);
                $categories[] = $cat;
            }
        }

        // Process posts to extract embedded data
        $processedPosts = [];
        if ($posts) {
            foreach ($posts as $post) {
                $featuredImage = null;
                $categoryName = 'Uncategorized';

                // Get featured image from embedded data
                if (isset($post->_embedded->{'wp:featuredmedia'}[0]->source_url)) {
                    $featuredImage = $post->_embedded->{'wp:featuredmedia'}[0]->source_url;
                }

                // Get category name from embedded data
                if (isset($post->_embedded->{'wp:term'}[0][0]->name)) {
                    $categoryName = html_entity_decode($post->_embedded->{'wp:term'}[0][0]->name);
                }

                $processedPosts[] = [
                    'id' => $post->id,
                    'title' => html_entity_decode($post->title->rendered),
                    'excerpt' => strip_tags(html_entity_decode($post->excerpt->rendered)),
                    'content' => $post->content->rendered,
                    'slug' => $post->slug,
                    'date' => \Carbon\Carbon::parse($post->date)->format('M d, Y'),
                    'featured_image' => $featuredImage,
                    'category' => $categoryName,
                    'link' => $post->link,
                ];
            }
        }

        // Pagination info
        $pagination = [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts,
            'perPage' => $perPage,
        ];

        return view('blogs', compact('settings', 'processedPosts', 'categories', 'pagination', 'searchQuery'));
    }

    /**
     * Single blog post detail page
     */
    public function blogDetail($slug)
    {
        $settings = \App\Models\HomepageSetting::pluck('value', 'key');

        // Fetch single post by slug
        $posts = $this->fetchFromWordPress('posts', [
            'slug' => $slug,
            '_embed' => 1
        ]);

        if (!$posts || count($posts) === 0) {
            abort(404);
        }

        $post = $posts[0];

        // Process the post data
        $featuredImage = null;
        $categoryName = 'Uncategorized';

        if (isset($post->_embedded->{'wp:featuredmedia'}[0]->source_url)) {
            $featuredImage = $post->_embedded->{'wp:featuredmedia'}[0]->source_url;
        }

        if (isset($post->_embedded->{'wp:term'}[0][0]->name)) {
            $categoryName = html_entity_decode($post->_embedded->{'wp:term'}[0][0]->name);
        }

        $authorName = 'Unknown';
        $authorImage = null;
        if (isset($post->_embedded->author[0])) {
            $authorData = $post->_embedded->author[0];
            $authorName = html_entity_decode($authorData->name);
            if (isset($authorData->avatar_urls)) {
                // Get the largest avatar usually '96'
                $avatars = (array) $authorData->avatar_urls;
                $authorImage = end($avatars); // Get the last one which is usually the largest
            }
        }


        // Get Likes
        $likeCount = BlogLike::where('post_id', $post->id)->count();
        $hasLiked = BlogLike::where('post_id', $post->id)->where('ip_address', request()->ip())->exists();

        $blogPost = [
            'id' => $post->id,
            'title' => html_entity_decode($post->title->rendered),
            'content' => $post->content->rendered,
            'slug' => $post->slug,
            'date' => \Carbon\Carbon::parse($post->date)->format('M d, Y'),
            'featured_image' => $featuredImage,
            'category' => $categoryName,
            'author' => $authorName,
            'author_image' => $authorImage,
            'likes' => $likeCount,
            'liked' => $hasLiked,
            'comment_status' => $post->comment_status ?? 'closed',
        ];

        // Fetch related posts (8 posts excluding current for sidebar)
        $relatedPosts = [];
        $related = $this->fetchFromWordPress('posts', [
            'per_page' => 9,
            'exclude' => $post->id,
            '_embed' => 1
        ]);

        if ($related) {
            foreach (array_slice($related, 0, 8) as $relPost) {
                $relFeaturedImage = null;
                $relCategoryName = 'Uncategorized';

                if (isset($relPost->_embedded->{'wp:featuredmedia'}[0]->source_url)) {
                    $relFeaturedImage = $relPost->_embedded->{'wp:featuredmedia'}[0]->source_url;
                }

                if (isset($relPost->_embedded->{'wp:term'}[0][0]->name)) {
                    $relCategoryName = html_entity_decode($relPost->_embedded->{'wp:term'}[0][0]->name);
                }

                $relatedPosts[] = [
                    'id' => $relPost->id,
                    'title' => html_entity_decode($relPost->title->rendered),
                    'excerpt' => strip_tags(html_entity_decode($relPost->excerpt->rendered)),
                    'slug' => $relPost->slug,
                    'date' => \Carbon\Carbon::parse($relPost->date)->format('M d, Y'),
                    'featured_image' => $relFeaturedImage,
                    'category' => $relCategoryName,
                ];
            }
        }

        // Fetch all categories for sidebar and decode HTML entities
        $rawCategories = $this->fetchFromWordPress('categories', ['per_page' => 50]);
        $categories = [];
        if ($rawCategories) {
            foreach ($rawCategories as $cat) {
                $cat->name = html_entity_decode($cat->name);
                $categories[] = $cat;
            }
        }

        return view('blog-detail', compact('settings', 'blogPost', 'relatedPosts', 'categories'));
    }

    /**
     * Handle Blog Like Toggle
     */
    public function toggleLike(Request $request)
    {
        try {
            $request->validate([
                'post_id' => 'required|integer'
            ]);

            $postId = $request->post_id;
            $ip = $request->ip();
            $userAgent = $request->userAgent();

            $like = BlogLike::where('post_id', $postId)->where('ip_address', $ip)->first();
            $isLiked = false;

            if ($like) {
                $like->delete();
                $isLiked = false;
                Log::info("Like removed for Post ID: {$postId} from IP: {$ip}");
            } else {
                BlogLike::create([
                    'post_id' => $postId,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent
                ]);
                $isLiked = true;
                Log::info("Like added for Post ID: {$postId} from IP: {$ip}");
            }

            $count = BlogLike::where('post_id', $postId)->count();
            Log::info("New Like Count for Post ID {$postId}: {$count}");

            return response()->json(['success' => true, 'liked' => $isLiked, 'count' => $count]);
        } catch (\Exception $e) {
            Log::error('Blog Like Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error processing like'], 500);
        }
    }

    /**
     * Handle Blog Comment Submission
     */
    public function postComment(Request $request)
    {
        $request->validate([
            'post_id' => 'required|integer',
            'author_name' => 'required|string',
            'author_email' => 'required|email',
            'content' => 'required|string',
            'parent' => 'nullable|integer'
        ]);

        try {
            $url = $this->getWordPressApiUrl() . '/comments';
            $verifySsl = config('services.wordpress.verify_ssl', true);

            $data = [
                'post' => $request->input('post_id'),
                'author_name' => $request->input('author_name'),
                'author_email' => $request->input('author_email'),
                'content' => $request->input('content'),
                'parent' => $request->input('parent', 0),
                'author_ip' => $request->ip(),
                'author_user_agent' => $request->userAgent(),
            ];

            // Some WP setups STRICTLY require Auth for REST API comments, even if "Users must be registered" is unchecked.
            // We can try to use Application Passwords if available, or just rely on public access.
            // If you have an Application Password set up in .env, utilize it here.
            $username = config('services.wordpress.username');
            $appPassword = config('services.wordpress.application_password');

            $headers = [
                'User-Agent' => 'ZayaWellness/1.0',
                'Accept' => 'application/json',
            ];

            $response = Http::withHeaders($headers);

            if ($username && $appPassword) {
                $response->withBasicAuth($username, $appPassword);
            }

            if (!$verifySsl) {
                $response->withoutVerifying();
            }

            $result = $response->post($url, $data);

            if ($result->successful()) {
                $responseData = $result->json();
                $message = 'Comment submitted successfully!';
                if (isset($responseData['status']) && $responseData['status'] === 'hold') {
                    $message = 'Your comment is awaiting moderation.';
                }
                return response()->json(['success' => true, 'message' => $message]);
            } else {
                $errorBody = $result->json();
                Log::error('WP Comment Post Error: ', $errorBody);

                $message = 'Unable to post comment.';
                if (isset($errorBody['code'])) {
                    if ($errorBody['code'] === 'rest_comment_login_required') {
                        $message = 'WordPress blocked this comment. If you are using an email address associated with an Admin or Registered User, please try a different email address.';
                    } elseif ($errorBody['code'] === 'rest_invalid_param') {
                        $message = 'Invalid comment data provided.';
                    } elseif ($errorBody['code'] === 'comment_duplicate') {
                        $message = 'You have already submitted this comment.';
                    }
                }

                return response()->json(['success' => false, 'message' => $message], 400);
            }

        } catch (\Exception $e) {
            Log::error('WP Comment Exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
        }
    }

    /**
     * Fetch Blog Comments
     */
    public function getComments($postId)
    {
        // Get approved comments only
        try {
            $comments = $this->fetchFromWordPress('comments', [
                'post' => $postId,
                'order' => 'asc',
                'status' => 'approve'
            ]);

            // Ensure we have an array
            if (!is_array($comments)) {
                Log::warning('WP Comments Invalid Response for Post ' . $postId, ['response' => $comments]);
                return response()->json([]);
            }

            return response()->json($comments);
        } catch (\Exception $e) {
            Log::error('WP Comment Fetch Error: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}
