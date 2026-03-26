<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\BlogLike;
use App\Models\ContactUs;
use App\Models\HomepageSetting;
use App\Models\Language;
use App\Models\PromoCode;
use App\Models\Practitioner;
use App\Models\Specialization;
use App\Models\AyurvedaExpertise;
use App\Models\HealthCondition;
use App\Models\ExternalTherapy;
use App\Models\YogaExpertise;
use App\Models\MindfulnessService;
use App\Models\ClientConcern;
use App\Models\TranslatorService;
use App\Models\TranslatorSpecialization;
use App\Models\Country;
use App\Models\Service;
use App\Models\Testimonial;
use App\Mail\ContactUsMail;
use App\Services\WordPressBlogService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class WebController extends Controller
{
    protected $blogService;
    private function deriveCurrencyFromCountry(?string $country): string
    {
        $map = config('currencies.country_to_currency', []);
        if ($country) {
            $code = strtoupper(trim($country));
            return $map[$code] ?? $map[substr($code, 0, 2)] ?? config('currencies.default', 'INR');
        }
        return config('currencies.default', 'INR');
    }

    public function __construct(WordPressBlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    //
    public function index()
    {
        $language = App::getLocale();
        $languages = Language::all();
        $practitioners = Practitioner::with(['user', 'reviews'])
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();
        $testimonials = Testimonial::withCount(['likes', 'replies'])->where('status', 'approved')->latest()->get();
        $ip = request()->ip();
        $testimonials->each(function ($testimonial) use ($ip) {
            $testimonial->is_liked = $testimonial->likes()->where('ip_address', $ip)->exists();
        });
        $services = Service::where('status', true)->orderBy('order_column')->get();
        $settings = HomepageSetting::getAllSettings($language);

        return view('index', compact('practitioners', 'testimonials', 'services', 'settings', 'language', 'languages'));
    }

    public function comingSoon()
    {
        return view('coming-soon');
    }

    public function aboutUs()
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);

        $testimonials = Testimonial::withCount(['likes', 'replies'])->where('status', 'approved')->latest()->get();
        $ip = request()->ip();
        $testimonials->each(function ($testimonial) use ($ip) {
            $testimonial->is_liked = $testimonial->likes()->where('ip_address', $ip)->exists();
        });
        return view('about', compact('settings', 'testimonials'));
    }

    public function services(Request $request)
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getSectionValues('services_page', $language);

        $query = Service::where('status', true);

        $categoryName = $request->query('category') ?? $request->query('servicescategory');

        if ($categoryName) {
            $query->where(function ($q) use ($categoryName) {
                // Check in categories
                $q->whereHas('categories', function ($sq) use ($categoryName) {
                    $sq->where('name', 'like', "%$categoryName%");
                })
                // OR check in service title (fallback)
                ->orWhere('title', 'like', "%$categoryName%");
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

    public function gallery()
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);

        return view('gallery', compact('settings'));
    }

    public function findPractitioner(Request $request)
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);

        $pincode = trim((string) $request->query('pincode', ''));
        $query = Practitioner::with(['user', 'reviews'])
            ->where('status', 'active');

        $selectedService = null;
        if ($pincode !== '') {
            $query->where('zip_code', 'LIKE', "%{$pincode}%");
        }

        if ($request->filled('service')) {
            $service = $request->service;
            $selectedService = is_numeric($service)
                ? Service::find($service)
                : Service::where('slug', $service)->first();
            $query->whereHas('userServices', function ($q) use ($service) {
                if (is_numeric($service)) {
                    $q->where('service_id', $service);
                } else {
                    $q->whereHas('service', function ($sq) use ($service) {
                        $sq->where('slug', $service)
                           ->orWhereHas('categories', function ($cq) use ($service) {
                               $cq->where('slug', $service);
                           });
                    });
                }
            });
        }

        if ($request->filled('mode')) {
            $mode = $request->mode;
            // Mode filtering can be added here if practitioners have a mode field
        }

        $practitioners = $query->paginate(12)->onEachSide(1)->withQueryString();
        $services = Service::where('status', true)->orderBy('title')->get();

        if ($request->ajax()) {
            return view('partials.frontend.practitioner-grid', compact('practitioners', 'pincode'))->render();
        }

        return view('find-practitioner', compact('settings', 'practitioners', 'pincode', 'services', 'selectedService'));
    }

    public function findPractitionerPost(Request $request)
    {
        $pincode = trim((string) $request->input('pincode', ''));
        $params = [];
        if ($pincode !== '') {
            $params['pincode'] = $pincode;
        }
        return redirect()->route('find-practitioner', $params);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        // Search Practitioners
        $practitioners = Practitioner::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->orWhereHas('userServices.service', function($sq) use ($query) {
                        $sq->where('title', 'LIKE', "%{$query}%");
                    });
            })
            ->take(5)
            ->get();

        // Search Services (Treatments)
        $services = Service::with('categories')->where('status', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhereHas('categories', function ($cq) use ($query) {
                        $cq->where('name', 'LIKE', "%{$query}%");
                    });
            })
            ->limit(4)
            ->get();

        $results = [
            'practitioners' => [],
            'treatments' => []
        ];

        foreach ($practitioners as $p) {
            $specialty = 'Practitioner';
            if ($p->user && $p->user->userServices->first()) {
                $specialty = $p->user->userServices->first()->service->title;
            }
            $results['practitioners'][] = [
                'name' => ($p->first_name ?? '') . ' ' . ($p->last_name ?? ''),
                'slug' => $p->slug,
                'image' => $p->user && $p->user->profile_pic ? (str_starts_with($p->user->profile_pic, 'http') ? $p->user->profile_pic : asset('storage/' . $p->user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png'),
                'subtitle' => $specialty . ($p->city ? ' • ' . $p->city : '')
            ];
        }

        foreach ($services as $s) {
            $category = $s->categories->first() ? $s->categories->first()->name : 'Treatment';
            $results['treatments'][] = [
                'name' => $s->title,
                'slug' => $s->slug,
                'image' => $s->image ? (str_starts_with($s->image, 'frontend/') ? asset($s->image) : asset('storage/' . $s->image)) : asset('frontend/assets/service-placeholder.png'),
                'subtitle' => $category
            ];
        }

        return response()->json($results);
    }

    public function searchLocations(Request $request)
    {
        $query = $request->get('query');
        if (empty($query)) return response()->json([]);

        $cities = Practitioner::where('status', 'active')
            ->where('city', 'LIKE', "%{$query}%")
            ->distinct()
            ->pluck('city')
            ->map(function ($city) {
                return ['name' => $city, 'type' => 'city'];
            });

        $zips = Practitioner::where('status', 'active')
            ->where('zip_code', 'LIKE', "%{$query}%")
            ->distinct()
            ->pluck('zip_code')
            ->map(function ($zip) {
                return ['name' => $zip, 'type' => 'zip'];
            });

        return response()->json($cities->concat($zips)->take(8));
    }

    public function practitionerDetail($slug)
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);

        $practitioner = Practitioner::with(['user', 'reviews'])->where('slug', $slug)->firstOrFail();
        return view('practitioner-detail', compact('practitioner', 'settings'));
    }

    public function filterPractitioners(Request $request)
    {
        $query = $request->get('query');
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);

        $practitioners = Practitioner::with(['user', 'reviews'])
            ->where('status', 'active')
            ->whereHas('userServices', function ($q) {
                $q->where('status', 'active');
            });

        if (!empty($query)) {
            $practitioners->where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('consultations', 'LIKE', "%{$query}%")
                    ->orWhere('other_modalities', 'LIKE', "%{$query}%");
            });
        }

        $practitioners = $practitioners->get();

        return view('partials.frontend.practitioner-slides', compact('practitioners', 'settings'))->render();
    }

    public function zayaLogin()
    {
        $available_languages = \App\Models\Language::where('status', 'active')->get();
        return view('zaya-login', compact('available_languages'));
    }

    public function clientRegister(Request $request)
    {
        $redirect = $request->query('redirect');
        $consultationPreferences = \App\Models\ClientConsultationPreference::all();
        $languages = \App\Models\Language::where('status', 'active')->get();

        $language = session('locale', 'en');
        $financeSettings = HomepageSetting::getSectionValues('finance', $language);
        $clientRegistrationFee = $financeSettings['client_registration_fee'] ?? '0';
        $clientRegistrationFee = is_numeric($clientRegistrationFee) ? (float) $clientRegistrationFee : 0.0;
        $clientRegistrationFeeEnabled = filter_var($financeSettings['client_registration_fee_enabled'] ?? '1', FILTER_VALIDATE_BOOLEAN);

        $defaultCurrency = $this->deriveCurrencyFromCountry($request->get('country', ''));

        return view('client-register', compact('redirect', 'consultationPreferences', 'languages', 'clientRegistrationFee', 'clientRegistrationFeeEnabled', 'defaultCurrency'));
    }

    public function practitionerRegister()
    {
        $wellnessConsultations = \App\Models\WellnessConsultation::where('status', true)->get();
        $bodyTherapies = \App\Models\BodyTherapy::where('status', true)->get();
        $otherModalities = \App\Models\PractitionerModality::where('status', true)->get();

        $language = session('locale', 'en');
        $financeSettings = HomepageSetting::getSectionValues('finance', $language);
        $practitionerRegistrationFee = $financeSettings['practitioner_registration_fee'] ?? '0';
        $practitionerRegistrationFee = is_numeric($practitionerRegistrationFee) ? (float) $practitionerRegistrationFee : 0.0;
        $practitionerRegistrationFeeEnabled = filter_var($financeSettings['practitioner_registration_fee_enabled'] ?? '1', FILTER_VALIDATE_BOOLEAN);

        return view('practitioner-register', compact(
            'wellnessConsultations',
            'bodyTherapies',
            'otherModalities',
            'practitionerRegistrationFee',
            'practitionerRegistrationFeeEnabled'
        ));
    }

    public function joinRegister(string $role)
    {
        $normalized = str_replace('_', '-', strtolower(trim($role)));
        $map = [
            'doctor' => ['role' => 'doctor', 'label' => 'Ayurvedic Doctor'],
            'mindfulness-practitioner' => ['role' => 'mindfulness_practitioner', 'label' => 'Mindfulness Practitioner'],
            'yoga-therapist' => ['role' => 'yoga_therapist', 'label' => 'Yoga Therapist'],
            'translator' => ['role' => 'translator', 'label' => 'Translator'],
        ];

        if (!isset($map[$normalized])) {
            abort(404);
        }

        $joinRole = $map[$normalized]['role'];
        $language = session('locale', 'en');

        $viewData = [
            'joinRole' => $joinRole,
            'joinRoleLabel' => $map[$normalized]['label'],
            'languages' => Language::where('status', 'active')->get(),
            'countries' => Country::where('status', 'active')->orderBy('name')->get(),
        ];

        if ($joinRole === 'doctor') {
            $viewData['specializations'] = Specialization::where('status', 1)->get();
            $viewData['consultationExpertise'] = AyurvedaExpertise::where('status', 1)->get();
            $viewData['healthConditions'] = HealthCondition::where('status', 1)->get();
            $viewData['externalTherapies'] = ExternalTherapy::where('status', 1)->get();
            $viewData['financeSettings'] = HomepageSetting::getSectionValues('finance', $language);
        } elseif ($joinRole === 'mindfulness_practitioner') {
            $viewData['mindfulnessServices'] = MindfulnessService::where('status', 1)->get();
            $viewData['clientConcerns'] = ClientConcern::where('status', 1)->get();
            $viewData['consultationModes'] = ["Video", "Audio", "Chat", "Group Sessions"];
        } elseif ($joinRole === 'yoga_therapist') {
            $viewData['areasOfExpertise'] = YogaExpertise::where('status', 1)->get();
            $viewData['consultationModes'] = ["Video", "Audio", "Chat", "Group Sessions"];
        } elseif ($joinRole === 'translator') {
            $viewData['translatorServices'] = TranslatorService::where('status', 1)->get();
            $viewData['translatorSpecializations'] = TranslatorSpecialization::where('status', 1)->get();
        }

        return view('team-register', $viewData);
    }

    public function validatePromoCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:255'],
        ]);

        $role = $request->input('role', 'practitioner');
        $feeKey = match ($role) {
            'client' => 'client_registration_fee',
            'doctor' => 'doctor_registration_fee',
            default => 'practitioner_registration_fee',
        };

        $language = session('locale', 'en');
        $financeSettings = HomepageSetting::getSectionValues('finance', $language);
        $baseFee = $financeSettings[$feeKey] ?? '0';
        $baseFee = is_numeric($baseFee) ? (float) $baseFee : 0.0;
        $feeEnabledKey = $feeKey . '_enabled';
        $feeEnabled = filter_var($financeSettings[$feeEnabledKey] ?? '1', FILTER_VALIDATE_BOOLEAN);
        if (!$feeEnabled) {
            return response()->json(['message' => 'Fee is currently disabled.'], 422);
        }

        $code = trim((string) $request->input('code'));
        $promo = PromoCode::whereRaw('LOWER(code) = ?', [mb_strtolower($code)])->first();

        if (!$promo || !$promo->status) {
            return response()->json(['message' => 'Invalid promo code.'], 422);
        }

        if ($promo->expiry_date && $promo->expiry_date->isPast()) {
            return response()->json(['message' => 'Promo code has expired.'], 422);
        }

        if (!is_null($promo->usage_limit) && (int) $promo->used_count >= (int) $promo->usage_limit) {
            return response()->json(['message' => 'Promo code usage limit reached.'], 422);
        }

        $reward = is_numeric($promo->reward) ? (float) $promo->reward : 0.0;

        $discountAmount = 0.0;
        $discountPercentage = 0.0;
        if ($promo->type === 'percentage') {
            $discountPercentage = max(0.0, min(100.0, $reward));
            $discountAmount = $baseFee * ($discountPercentage / 100.0);
        } else { // fixed
            $discountAmount = max(0.0, $reward);
            $discountAmount = min($discountAmount, $baseFee);
            $discountPercentage = $baseFee > 0 ? ($discountAmount / $baseFee) * 100.0 : 0.0;
        }

        $discountAmount = min($discountAmount, $baseFee);
        $totalFee = max(0.0, $baseFee - $discountAmount);

        return response()->json([
            'code' => $promo->code,
            'type' => $promo->type,
            'reward' => (float) $reward,
            'base_fee' => number_format($baseFee, 2, '.', ''),
            'discount_percentage' => number_format($discountPercentage, 2, '.', ''),
            'discount_amount' => number_format($discountAmount, 2, '.', ''),
            'total_fee' => number_format($totalFee, 2, '.', ''),
        ]);
    }

    public function serviceDetail($slug)
    {
        $service = Service::with('images')->where('slug', $slug)->where('status', true)->first();

        if (!$service) {
            // Dummy Data Support for UI Design
            $dummyServices = [
                'wellness-based-ayurveda-consultation' => 'Wellness based Ayurveda consultation',
                'ayurvedic-diet-nutrition-guidance' => 'Ayurvedic diet & nutrition guidance',
                'herbal-wellness-support' => 'Herbal wellness support',
                'abhyanga-ayurvedic-oil-massage' => 'Abhyanga (Ayurvedic Oil Massage)',
                'shirodhara' => 'Shirodhara',
                'panchakarma-inspired-detox-programs-light-versions' => 'Panchakarma-inspired detox programs (light versions)'
            ];

            if (isset($dummyServices[$slug])) {
                $service = new \App\Models\Service();
                $service->title = $dummyServices[$slug];
                $service->slug = $slug;
                $service->image = 'frontend/assets/' . $slug . '.png';
                $service->description = '<p>Experience a premium holistic session designed perfectly for your lifestyle and energetic pathway.</p><ul><li>Natural rejuvenation</li><li>Mindful balancing approach</li></ul>';
                $service->setRelation('images', collect([])); // empty relation so gallery check doesn't fail
            } else {
                abort(404);
            }
        }

        $otherServices = Service::where('slug', '!=', $slug)->where('status', true)->inRandomOrder()->take(4)->get();

        if ($otherServices->isEmpty()) {
            $otherServices = collect([
                (object) [
                    'title' => 'Yoga Therapy Session',
                    'slug' => 'yoga-therapy',
                    'image' => 'frontend/assets/yoga-service.png',
                    'description' => 'Realign your body and energetic pathways with our expert yoga guidance.'
                ],
                (object) [
                    'title' => 'Counseling Session',
                    'slug' => 'counseling',
                    'image' => 'frontend/assets/counselling-service.png',
                    'description' => 'Nurture your mental well-being with our holistic approaches.'
                ]
            ]);
        }

        return view('service-detail', compact('service', 'otherServices'));
    }

    public function bookSession(Request $request, $practitioner = null)
    {
        $practitioners = Practitioner::with(['user', 'reviews'])
            ->where('status', 'active')
            ->whereHas('userServices', function ($q) {
                $q->where('status', 'active');
            })
            ->get();
        $selectedPractitioner = null;

        if ($practitioner) {
            $selectedPractitioner = Practitioner::with(['user', 'reviews'])->where('slug', $practitioner)->first();
        }

        if (!$selectedPractitioner && $request->filled('practitioner_id')) {
            $selectedPractitioner = Practitioner::with(['user', 'reviews'])->find($request->practitioner_id);
        }

        if (!$selectedPractitioner && $practitioners->isNotEmpty()) {
            $selectedPractitioner = $practitioners->first();
        }

        // Filter services based on selected practitioner using the new user_services table
        if ($selectedPractitioner && $selectedPractitioner->user) {
            $services = Service::where('status', true)
                ->whereHas('userServices', function($q) use ($selectedPractitioner) {
                    $q->where('user_id', $selectedPractitioner->user_id);
                })
                ->get();
        } else {
            $services = Service::where('status', true)->get();
        }

        // Handle prefilled service from request
        $prefilledService = null;
        if ($request->filled('service_id')) {
            $prefilledService = Service::find($request->service_id);
        }

        $languages = \App\Models\Language::where('status', 'active')
            ->orderBy('name')
            ->get();
        $consultationPreferences = \App\Models\ClientConsultationPreference::all();

        return view('book-session', compact('practitioners', 'selectedPractitioner', 'services', 'languages', 'consultationPreferences', 'prefilledService'));
    }

    public function contactUs()
    {
        $language = \Illuminate\Support\Facades\App::getLocale();
        $settings = HomepageSetting::getAllSettings($language, 'contact_');

        $faqs = \App\Models\Faq::where('language', $language)->where('status', true)->get();

        return view('contact-us', compact('settings', 'faqs'));
    }

    public function storeContact(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|min:1|max:50',
            'last_name' => 'required|string|min:1|max:50',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => 'required|string|min:7|max:15|regex:/^\+?[0-9\s\-\+\(\)]+$/',
            'user_type' => 'nullable|array',
            'message' => 'required|string|min:10|max:2000',
        ]);

        $message = ContactUs::create($validatedData);

        // Send Email to Admin
        $to = 'info@zayawellness.com';

        // SECURITY CHECK: Only allow sending if email exists in our DB (users table)
        $recipientExists = \App\Models\User::where('email', $to)->exists();

        if (!$recipientExists) {
            Log::warning("Email delivery blocked: {$to} is not in our database.");
            \App\Services\EmailLoggerService::log($to, 'Blocked: ' . $message->first_name, null, 'error', 'Recipient not in database');
            return response()->json(['success' => 'Thank you! Your message has been recorded.']);
        }

        $mailable = new ContactUsMail($message);
        $subject = 'New Contact Us Message - ' . $message->first_name . ' ' . $message->last_name;
        $startTime = microtime(true);

        try {
            Mail::mailer('noreply')->to($to)->send($mailable);
            $duration = round(microtime(true) - $startTime, 2);
            \App\Services\EmailLoggerService::log($to, $subject, null, 'success', null, $duration);
        } catch (\Exception $e) {
            $duration = round(microtime(true) - $startTime, 2);
            Log::error('Failed to send contact email: ' . $e->getMessage());
            \App\Services\EmailLoggerService::log($to, $subject, null, 'error', $e->getMessage(), $duration);
        }

        return response()->json(['success' => 'Thank you for contacting us! Your message has been sent successfully.']);
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
    /**
     * Fetch data from WordPress REST API (Delegated to Service)
     */
    protected function fetchFromWordPress($endpoint, $params = [], $withHeaders = false)
    {
        return $this->blogService->fetchFromWordPress($endpoint, $params, $withHeaders);
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
     * Extract optimized image data from embedded WP post
     */
    private function extractOptimizedImage($post)
    {
        $imageData = [
            'url' => null,
            'srcset' => null,
            'sizes' => null,
        ];

        if (isset($post->_embedded->{'wp:featuredmedia'}[0])) {
            $media = $post->_embedded->{'wp:featuredmedia'}[0];

            if (isset($media->source_url)) {
                $imageData['url'] = $media->source_url;
            }

            if (isset($media->media_details->sizes)) {
                $srcset = [];
                // Include full size if possible
                if (isset($media->media_details->width) && isset($media->source_url)) {
                    $srcset[] = $media->source_url . ' ' . $media->media_details->width . 'w';
                }

                // Add all other sizes
                foreach (get_object_vars($media->media_details->sizes) as $sizeName => $size) {
                    if (isset($size->source_url) && isset($size->width)) {
                        $srcset[] = $size->source_url . ' ' . $size->width . 'w';
                    }
                }

                if (!empty($srcset)) {
                    $imageData['srcset'] = implode(', ', array_unique($srcset));
                    $imageData['sizes'] = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw';
                }

                // Set a smaller default image for 'src' fallback to speed up initial load
                if (isset($media->media_details->sizes->medium_large->source_url)) {
                    $imageData['url'] = $media->media_details->sizes->medium_large->source_url;
                } elseif (isset($media->media_details->sizes->large->source_url)) {
                    $imageData['url'] = $media->media_details->sizes->large->source_url;
                } elseif (isset($media->media_details->sizes->medium->source_url)) {
                    $imageData['url'] = $media->media_details->sizes->medium->source_url;
                }
            }
        }

        return $imageData;
    }

    /**
     * Blogs listing page - fetches posts from WordPress
     */
    public function blogs(Request $request)
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);

        // Fetch authors for the filter dropdown
        $authors = [];
        try {
            // Fetch users who have published posts
            $rawAuthors = $this->fetchFromWordPress('users', [
                'has_published_posts' => true,
                'per_page' => 100 // Get enough authors
            ]);

            if ($rawAuthors && is_array($rawAuthors)) {
                $authors = array_map(function ($author) {
                    return [
                        'id' => $author->id,
                        'name' => $author->name,
                        'slug' => $author->slug
                    ];
                }, $rawAuthors);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching authors: ' . $e->getMessage());
        }

        $currentPage = (int) $request->get('page', 1);
        $perPage = 9;

        // Build API params
        // Build API params
        $params = [
            'per_page' => $perPage,
            'page' => $currentPage,
            '_embed' => 1, // Include featured media and categories
        ];

        // Author filter
        $selectedAuthorId = null;
        if ($request->filled('author')) {
            $selectedAuthorId = $request->author;
            $params['author'] = $selectedAuthorId;
        }

        // Category filter (Sidebar)
        if ($request->filled('category')) {
            // First get category ID by name
            $categories = $this->fetchFromWordPress('categories', ['search' => $request->category]);
            if ($categories && count($categories) > 0) {
                $params['categories'] = $categories[0]->id;
            }
        }

        // Comprehensive Search Logic (Text + Tags + Categories)
        $searchQuery = '';
        if ($request->filled('search')) {
            $searchQuery = $request->search;

            // 1. Standard Text Search
            $params['search'] = $searchQuery;
            $textSearchResponse = $this->fetchFromWordPress('posts', $params, true);
            $posts = $textSearchResponse['data'] ?? [];
            $headers = $textSearchResponse['headers'] ?? [];

            // 2. Search for matching Categories
            $catParams = ['search' => $searchQuery];
            $matchedCats = $this->fetchFromWordPress('categories', $catParams);

            // 3. Search for matching Tags
            $tagParams = ['search' => $searchQuery];
            $matchedTags = $this->fetchFromWordPress('tags', $tagParams);

            $additionalPosts = [];

            // Fetch posts from matched categories
            if ($matchedCats && count($matchedCats) > 0) {
                $catIds = array_column($matchedCats, 'id');
                // Only if we found categories, fetch posts for them
                // We use a separate query without the 'search' param to avoid AND logic
                // Copy base params but remove search
                $catPostParams = $params;
                unset($catPostParams['search']);
                $catPostParams['categories'] = implode(',', $catIds);

                $catPosts = $this->fetchFromWordPress('posts', $catPostParams);
                if ($catPosts && is_array($catPosts)) {
                    $additionalPosts = array_merge($additionalPosts, $catPosts);
                }
            }

            // Fetch posts from matched tags 
            if ($matchedTags && count($matchedTags) > 0) {
                $tagIds = array_column($matchedTags, 'id');
                // We use a separate query without the 'search' param
                $tagPostParams = $params;
                unset($tagPostParams['search']);
                $tagPostParams['tags'] = implode(',', $tagIds);

                $tagPosts = $this->fetchFromWordPress('posts', $tagPostParams);
                if ($tagPosts && is_array($tagPosts)) {
                    $additionalPosts = array_merge($additionalPosts, $tagPosts);
                }
            }

            // Merge and Unique
            if (!empty($additionalPosts)) {
                // Merge initial search results with category/tag results
                $allPosts = array_merge($posts, $additionalPosts);

                // Deduplicate by ID
                $uniquePosts = [];
                $seenIds = [];
                foreach ($allPosts as $post) {
                    if (!in_array($post->id, $seenIds)) {
                        $uniquePosts[] = $post;
                        $seenIds[] = $post->id;
                    }
                }

                // Re-assign to posts
                $posts = $uniquePosts;

                // Recalculate total (approximate)
                // We can't easily know true total without fetching all, but we update the count for current view
                $headers['total'] = count($uniquePosts);
                // Fix totalPages if count > perPage
                $headers['totalPages'] = ceil(count($uniquePosts) / $perPage);

                // Since we merged multiple pages of results potentially, we might want to sort by date
                usort($posts, function ($a, $b) {
                    return strtotime($b->date) - strtotime($a->date);
                });

                // Manual Pagination Slice (if we have too many checks)
                // Note: This is an approximation. True pagination across merged queries needs more complex logic.
                // For now, we only show the first page logic or simple merge. 
                // If page > 1, the API calls above fetched page X of each.
                // Merging Page X of A + Page X of B is a reasonable "Combined Page X".
            }
        } else {
            // No search, just fetch
            $response = $this->fetchFromWordPress('posts', $params, true);
            $posts = $response['data'];
            $headers = $response['headers'];
        }

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
                // Get image data
                $imageData = $this->extractOptimizedImage($post);
                $featuredImage = $imageData['url'];
                $featuredImageSrcset = $imageData['srcset'];
                $featuredImageSizes = $imageData['sizes'];

                $categoryName = 'Uncategorized';

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
                    'date' => \Carbon\Carbon::parse($post->date)->translatedFormat('M d, Y'),
                    'featured_image' => $featuredImage,
                    'featured_image_srcset' => $featuredImageSrcset,
                    'featured_image_sizes' => $featuredImageSizes,
                    'category' => __($categoryName),
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

        return view('blogs', compact('settings', 'processedPosts', 'categories', 'pagination', 'searchQuery', 'authors', 'selectedAuthorId'));
    }

    /**
     * Announcements listing page - fetches announcements CPT from WordPress
     */
    public function announcements(Request $request)
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);

        $currentPage = (int) $request->get('page', 1);
        $perPage = 9;

        // Build API params
        $params = [
            'per_page' => $perPage,
            'page' => $currentPage,
            '_embed' => 1,
        ];

        // Fetch Announcements - specific CPT endpoint
        // 1. Try 'announcement' (singular)
        $endpoint = 'announcement';
        $response = $this->fetchFromWordPress($endpoint, $params, true);

        // 2. If empty, try 'announcements' (plural) - assuming plural CPT name
        if (empty($response['data'])) {
            $endpoint = 'announcements';
            $response = $this->fetchFromWordPress($endpoint, $params, true);
        }

        $posts = $response['data'] ?? [];
        $headers = $response['headers'] ?? [];

        // Pagination data
        $totalPosts = $headers['total'] ?? 0;
        $totalPages = $headers['totalPages'] ?? 1;

        // Process posts
        $processedPosts = [];
        if ($posts) {
            foreach ($posts as $post) {
                $imageData = $this->extractOptimizedImage($post);
                $featuredImage = $imageData['url'];
                $featuredImageSrcset = $imageData['srcset'];
                $featuredImageSizes = $imageData['sizes'];

                $processedPosts[] = [
                    'id' => $post->id,
                    'title' => html_entity_decode($post->title->rendered),
                    'excerpt' => strip_tags(html_entity_decode($post->excerpt->rendered ?? '')),
                    'content' => $post->content->rendered,
                    'slug' => $post->slug,
                    'date' => \Carbon\Carbon::parse($post->date)->format('M d, Y'),
                    'featured_image' => $featuredImage,
                    'featured_image_srcset' => $featuredImageSrcset,
                    'featured_image_sizes' => $featuredImageSizes,
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

        return view('announcements', compact('settings', 'processedPosts', 'pagination'));
    }

    /**
     * Single announcement detail page
     */
    public function announcementDetail($slug)
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);

        // Fetch single announcement by slug
        // 1. Try singular
        $endpoint = 'announcement';
        $posts = $this->fetchFromWordPress($endpoint, [
            'slug' => $slug,
            '_embed' => 1
        ]);

        // 2. Try plural if not found
        if (!$posts || count($posts) === 0) {
            $endpoint = 'announcements';
            $posts = $this->fetchFromWordPress($endpoint, [
                'slug' => $slug,
                '_embed' => 1
            ]);
        }

        if (!$posts || count($posts) === 0) {
            abort(404);
        }

        $post = $posts[0];

        // Process the post data
        $imageData = $this->extractOptimizedImage($post);
        $featuredImage = $imageData['url'];
        $featuredImageSrcset = $imageData['srcset'];
        $featuredImageSizes = '(max-width: 1024px) 100vw, 66vw';

        $announcement = [
            'id' => $post->id,
            'title' => html_entity_decode($post->title->rendered),
            'content' => $post->content->rendered,
            'slug' => $post->slug,
            'date' => \Carbon\Carbon::parse($post->date)->format('M d, Y'),
            'featured_image' => $featuredImage,
            'featured_image_srcset' => $featuredImageSrcset,
            'featured_image_sizes' => $featuredImageSizes,
        ];

        // Fetch related posts using the SAME endpoint regarding singular/plural
        $relatedPosts = [];
        $related = $this->fetchFromWordPress($endpoint, [
            'per_page' => 4,
            'exclude' => $post->id,
            '_embed' => 1
        ]);

        if ($related) {
            foreach ($related as $relPost) {
                $relImageData = $this->extractOptimizedImage($relPost);
                $relFeaturedImage = $relImageData['url'];
                $relFeaturedImageSrcset = $relImageData['srcset'];
                $relFeaturedImageSizes = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 25vw';

                $relatedPosts[] = [
                    'id' => $relPost->id,
                    'title' => html_entity_decode($relPost->title->rendered),
                    'excerpt' => strip_tags(html_entity_decode($relPost->excerpt->rendered ?? '')),
                    'slug' => $relPost->slug,
                    'date' => \Carbon\Carbon::parse($relPost->date)->format('M d, Y'),
                    'featured_image' => $relFeaturedImage,
                    'featured_image_srcset' => $relFeaturedImageSrcset,
                    'featured_image_sizes' => $relFeaturedImageSizes,
                ];
            }
        }

        return view('announcement-detail', compact('settings', 'announcement', 'relatedPosts'));
    }

    /**
     * Single blog post detail page
     */
    public function blogDetail($slug)
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);

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
        $imageData = $this->extractOptimizedImage($post);
        $featuredImage = $imageData['url'];
        $featuredImageSrcset = $imageData['srcset'];
        $featuredImageSizes = '(max-width: 1024px) 100vw, 66vw';
        $categoryName = 'Uncategorized';

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
            'featured_image_srcset' => $featuredImageSrcset,
            'featured_image_sizes' => $featuredImageSizes,
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
                $relImageData = $this->extractOptimizedImage($relPost);
                $relFeaturedImage = $relImageData['url'];
                $relFeaturedImageSrcset = $relImageData['srcset'];
                $relFeaturedImageSizes = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 25vw';
                $relCategoryName = 'Uncategorized';

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
                    'featured_image_srcset' => $relFeaturedImageSrcset,
                    'featured_image_sizes' => $relFeaturedImageSizes,
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

            /** @var \Illuminate\Http\Client\Response $result */
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
