<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    /**
     * WordPress API Base URL
     */
    private function getWordPressApiUrl()
    {
        return config('services.wordpress.api_url', 'http://localhost/blog-zaya/wp-json/wp/v2');
    }

    /**
     * Fetch data from WordPress REST API
     */
    private function fetchFromWordPress($endpoint, $params = [])
    {
        try {
            $url = $this->getWordPressApiUrl() . '/' . $endpoint;

            if (!empty($params)) {
                $url .= '?' . http_build_query($params);
            }

            $context = stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'ignore_errors' => true
                ]
            ]);

            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                return null;
            }

            return json_decode($response);
        } catch (\Exception $e) {
            \Log::error('WordPress API Error: ' . $e->getMessage());
            return null;
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

        // Build API params
        $params = [
            'per_page' => 9,
            'page' => $request->get('page', 1),
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
        if ($request->filled('search')) {
            $params['search'] = $request->search;
        }

        // Fetch posts from WordPress
        $posts = $this->fetchFromWordPress('posts', $params);

        // Fetch all categories for the dropdown
        $categories = $this->fetchFromWordPress('categories', ['per_page' => 50]);

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
                    $categoryName = $post->_embedded->{'wp:term'}[0][0]->name;
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

        return view('blogs', compact('settings', 'processedPosts', 'categories'));
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
            $categoryName = $post->_embedded->{'wp:term'}[0][0]->name;
        }

        $blogPost = [
            'id' => $post->id,
            'title' => html_entity_decode($post->title->rendered),
            'content' => $post->content->rendered,
            'slug' => $post->slug,
            'date' => \Carbon\Carbon::parse($post->date)->format('M d, Y'),
            'featured_image' => $featuredImage,
            'category' => $categoryName,
        ];

        // Fetch related posts (latest 3 excluding current)
        $relatedPosts = [];
        $related = $this->fetchFromWordPress('posts', [
            'per_page' => 4,
            'exclude' => $post->id,
            '_embed' => 1
        ]);

        if ($related) {
            foreach (array_slice($related, 0, 3) as $relPost) {
                $relFeaturedImage = null;
                $relCategoryName = 'Uncategorized';

                if (isset($relPost->_embedded->{'wp:featuredmedia'}[0]->source_url)) {
                    $relFeaturedImage = $relPost->_embedded->{'wp:featuredmedia'}[0]->source_url;
                }

                if (isset($relPost->_embedded->{'wp:term'}[0][0]->name)) {
                    $relCategoryName = $relPost->_embedded->{'wp:term'}[0][0]->name;
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

        return view('blog-detail', compact('settings', 'blogPost', 'relatedPosts'));
    }
}
