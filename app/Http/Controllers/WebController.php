<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\OpenRegisterLink;
use App\Models\BlogLike;
use App\Models\ContactUs;
use App\Models\Country;
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
use App\Models\Qualification;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\Testimonial;
use App\Mail\ContactUsMail;

use App\Models\Doctor;
use App\Models\MindfulnessPractitioner;
use App\Models\YogaTherapist;
use App\Services\WordPressBlogService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

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
        if (auth()->check()) {
            $user = auth()->user();
            $adminRoles = ['super-admin', 'admin', 'country-admin', 'financial-manager', 'content-manager', 'user-manager'];
            if (in_array($user->role, $adminRoles)) {
                return redirect()->route('admin.dashboard');
            }
        }
        $language = App::getLocale();
        $languages = Language::where('status', 'active')->get();
        $practitioners = Practitioner::with(['user', 'reviews'])
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();
        $testimonials = Testimonial::withCount(['likes', 'replies'])->where('status', 'approved')->latest()->get();
        $ip = request()->ip();
        $testimonials->each(function ($testimonial) use ($ip) {
            if (is_object($testimonial) && method_exists($testimonial, 'likes')) {
                $testimonial->is_liked = $testimonial->likes()->where('ip_address', $ip)->exists();
            }
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

    public function privacyPolicy()
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);
        return view('privacy-policy', compact('settings'));
    }

    public function cookiePolicy()
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);
        return view('cookie-policy', compact('settings'));
    }

    public function termsAndConditions()
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);
        return view('terms-and-conditions', compact('settings'));
    }

    public function services(Request $request)
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getSectionValues('services_page', $language);
        $categoryName = $request->query('category') ?? $request->query('servicescategory');
        $servicePackages = collect();

        if ($categoryName === 'Packages') {
            $packageQuery = ServicePackage::where('status', true)
                ->orderBy('order_column', 'asc')
                ->orderByDesc('id');

            if ($request->filled('search')) {
                $search = $request->search;
                $packageQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                });
            }

            $servicePackages = $packageQuery->get()->map(function ($package) {
                $services = $package->services;
                $imageUrl = asset('frontend/assets/wellness-based-ayurveda-consultation.png');

                if ($package->cover_image) {
                    $imageUrl = asset('storage/' . $package->cover_image);
                } elseif ($services->isNotEmpty()) {
                    $coverService = $services->first();
                    if ($coverService->image) {
                        if (str_starts_with($coverService->image, 'http')) {
                            $imageUrl = $coverService->image;
                        } elseif (file_exists(public_path('storage/' . $coverService->image))) {
                            $imageUrl = asset('storage/' . $coverService->image);
                        } elseif (file_exists(public_path($coverService->image))) {
                            $imageUrl = asset($coverService->image);
                        }
                    }
                }

                $package->cover_image_url = $imageUrl;
                $package->service_titles = $services->pluck('title')->values();

                return $package;
            })->filter(function ($package) use ($request) {
                if (! $request->filled('search')) {
                    return true;
                }

                $search = mb_strtolower($request->search);

                return $package->service_titles->contains(function ($title) use ($search) {
                    return str_contains(mb_strtolower($title), $search);
                }) || str_contains(mb_strtolower((string) $package->title), $search)
                    || str_contains(mb_strtolower((string) $package->description), $search);
            })->values();

            $services = collect();
        } else {
            $query = Service::where('status', true);

            if ($categoryName) {
                $query->where(function ($q) use ($categoryName) {
                    $q->whereHas('categories', function ($sq) use ($categoryName) {
                        $sq->where('name', 'like', "%$categoryName%");
                    })
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
        }

        if ($request->ajax()) {
            return view('partials.frontend.services-grid', compact('services'))->render();
        }

        return view('services', compact('settings', 'services', 'servicePackages'));
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

        $zipcode = trim((string) $request->query('zipcode', $request->query('pincode', session('global_zipcode', session('global_pincode', '')))));
        $searchQuery = trim((string) $request->query('query', ''));

        $practitionerQuery = Practitioner::with(['user', 'reviews', 'userServices.service'])->where('status', 'active');
        $doctorQuery = Doctor::with(['user', 'reviews', 'userServices.service'])->where('status', 'active');
        $mindfulnessQuery = MindfulnessPractitioner::with(['user', 'reviews', 'userServices.service'])->where('status', 'active');
        $yogaQuery = YogaTherapist::with(['user', 'reviews', 'userServices.service'])->where('status', 'active');

        if ($zipcode !== '') {
            $practitionerQuery->where('zip_code', 'LIKE', "%{$zipcode}%");
            $doctorQuery->where('zip_code', 'LIKE', "%{$zipcode}%");
            $mindfulnessQuery->where('zip_code', 'LIKE', "%{$zipcode}%");
            $yogaQuery->where('zip_code', 'LIKE', "%{$zipcode}%");
        }

        $selectedService = null;
        $serviceForFilter = null;
        if ($request->filled('service')) {
            $service = $request->query('service');
            $serviceForFilter = $service;

            if (is_numeric($service)) {
                $selectedService = Service::find($service);
            } else {
                $selectedService = Service::where('slug', $service)->first();
                if (!$selectedService && is_string($service) && preg_match('/-(en|fr|de|es|it|pt|nl)$/', $service)) {
                    $baseSlug = preg_replace('/-(en|fr|de|es|it|pt|nl)$/', '', $service);
                    $selectedService = Service::where('slug', $baseSlug)->first();
                    if ($selectedService) {
                        $serviceForFilter = $baseSlug;
                    }
                }
            }
        }

        $serviceTitle = $selectedService ? $selectedService->title : null;

        if ($selectedService || $searchQuery !== '') {
            $applyFilters = function($query, $modelType, $searchQuery, $serviceForFilter, $serviceTitle) {
                $query->where(function($q) use ($modelType, $searchQuery, $serviceForFilter, $serviceTitle) {
                    // 1. Service Filter
                    if ($serviceForFilter) {
                        $q->where(function($sq) use ($modelType, $serviceForFilter, $serviceTitle) {
                            $sq->whereHas('userServices', function ($usq) use ($serviceForFilter) {
                                if (is_numeric($serviceForFilter)) {
                                    $usq->where('service_id', $serviceForFilter);
                                } else {
                                    $usq->whereHas('service', function ($tsq) use ($serviceForFilter) {
                                        $tsq->where('slug', $serviceForFilter)
                                           ->orWhereHas('categories', function ($cq) use ($serviceForFilter) {
                                               $cq->where('slug', $serviceForFilter);
                                           });
                                    });
                                }
                            });

                            if ($serviceTitle) {
                                $sq->orWhere(function($ssq) use ($modelType, $serviceTitle) {
                                    $like = "%{$serviceTitle}%";
                                    if ($modelType === 'practitioner') {
                                        $ssq->where('body_therapies', 'LIKE', $like)->orWhere('other_modalities', 'LIKE', $like)->orWhere('consultations', 'LIKE', $like);
                                    } elseif ($modelType === 'doctor') {
                                        $ssq->where('specialization', 'LIKE', $like)->orWhere('consultation_expertise', 'LIKE', $like)->orWhere('health_conditions_treated', 'LIKE', $like)->orWhere('panchakarma_procedures', 'LIKE', $like)->orWhere('external_therapies', 'LIKE', $like);
                                    } elseif ($modelType === 'mindfulness') {
                                        $ssq->where('practitioner_type', 'LIKE', $like)->orWhere('services_offered', 'LIKE', $like)->orWhere('client_concerns', 'LIKE', $like);
                                    } elseif ($modelType === 'yoga') {
                                        $ssq->where('yoga_therapist_type', 'LIKE', $like)->orWhere('areas_of_expertise', 'LIKE', $like);
                                    }
                                });
                            }
                        });
                    }

                    // 2. Query Filter
                    if ($searchQuery !== '') {
                        $q->where(function($qq) use ($modelType, $searchQuery) {
                            $like = "%{$searchQuery}%";
                            $qq->where('first_name', 'LIKE', $like)
                               ->orWhere('last_name', 'LIKE', $like)
                               ->orWhereHas('userServices.service', function($usq) use ($like) {
                                   $usq->where('title', 'LIKE', $like);
                               });

                            if ($modelType === 'practitioner') {
                                $qq->orWhere('body_therapies', 'LIKE', $like)->orWhere('other_modalities', 'LIKE', $like)->orWhere('consultations', 'LIKE', $like);
                            } elseif ($modelType === 'doctor') {
                                $qq->orWhere('specialization', 'LIKE', $like)->orWhere('consultation_expertise', 'LIKE', $like)->orWhere('health_conditions_treated', 'LIKE', $like)->orWhere('panchakarma_procedures', 'LIKE', $like)->orWhere('external_therapies', 'LIKE', $like);
                            } elseif ($modelType === 'mindfulness') {
                                $qq->orWhere('practitioner_type', 'LIKE', $like)->orWhere('services_offered', 'LIKE', $like)->orWhere('client_concerns', 'LIKE', $like);
                            } elseif ($modelType === 'yoga') {
                                $qq->orWhere('yoga_therapist_type', 'LIKE', $like)->orWhere('areas_of_expertise', 'LIKE', $like);
                            }
                        });
                    }
                });
            };

            $applyFilters($practitionerQuery, 'practitioner', $searchQuery, $serviceForFilter, $serviceTitle);
            $applyFilters($doctorQuery, 'doctor', $searchQuery, $serviceForFilter, $serviceTitle);
            $applyFilters($mindfulnessQuery, 'mindfulness', $searchQuery, $serviceForFilter, $serviceTitle);
            $applyFilters($yogaQuery, 'yoga', $searchQuery, $serviceForFilter, $serviceTitle);
        }

        // Combine all results using a manual union-like approach for pagination or just merge for small datasets.
        // For proper pagination across models, we might need a more complex approach or just merge if results are reasonable.
        // Let's use a simple merge and paginate the collection for now, or pick one as primary.
        
        $results = $practitionerQuery->get()
            ->merge($doctorQuery->get())
            ->merge($mindfulnessQuery->get())
            ->merge($yogaQuery->get());

        $perPage = 12;
        $page = $request->get('page', 1);
        $practitioners = new \Illuminate\Pagination\LengthAwarePaginator(
            $results->forPage($page, $perPage),
            $results->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        $services = Service::where('status', true)->orderBy('title')->get();

        if ($request->ajax()) {
            return view('partials.frontend.practitioner-grid', compact('practitioners', 'zipcode', 'selectedService'))->render();
        }

        return view('find-practitioner', compact('settings', 'practitioners', 'zipcode', 'services', 'selectedService', 'searchQuery'));
    }

    public function findPractitionerPost(Request $request)
    {
        $zipcode = trim((string) $request->input('zipcode', $request->input('pincode', '')));
        $params = [];
        if ($zipcode !== '') {
            $params['zipcode'] = $zipcode;
        }
        return redirect()->route('find-practitioner', $params);
    }

    public function zipcodeConditions(Request $request)
    {
        $raw = trim((string) $request->query('zipcode', $request->query('pincode', session('global_zipcode', session('global_pincode', '')))));
        $zipcode = substr(preg_replace('/[^0-9]/', '', $raw), 0, 6);

        $conditions = [];

        $rowsQuery = Practitioner::query()->where('status', 'active');
        if (strlen($zipcode) === 6) {
            $rowsQuery->where('zip_code', 'LIKE', "%{$zipcode}%");
        } else {
            $zipcode = null;
        }

        $rows = $rowsQuery->limit(200)->get(['body_therapies']);

        foreach ($rows as $p) {
            $arr = $p->body_therapies ?? [];
            if (!is_array($arr)) $arr = [$arr];

            foreach ($arr as $v) {
                $v = trim((string) $v);
                if ($v === '') continue;
                $conditions[$v] = true;
                if (count($conditions) >= 6) break 2;
            }
        }

        return response()->json([
            'success' => true,
            'zipcode' => $zipcode,
            'conditions' => array_keys($conditions),
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        if (empty($query)) {
            return response()->json(['practitioners' => [], 'treatments' => []]);
        }

        // Search Practitioners, Doctors, etc.
        $applySearchFilter = function ($q, $modelType, $query) {
            $q->where('status', 'active')
              ->where(function ($sq) use ($modelType, $query) {
                  $like = "%{$query}%";
                  $sq->where('first_name', 'LIKE', $like)
                    ->orWhere('last_name', 'LIKE', $like)
                    ->orWhereHas('userServices.service', function($usq) use ($like) {
                        $usq->where('title', 'LIKE', $like);
                    });

                  if ($modelType === 'practitioner') {
                      $sq->orWhere('body_therapies', 'LIKE', $like)->orWhere('other_modalities', 'LIKE', $like)->orWhere('consultations', 'LIKE', $like);
                  } elseif ($modelType === 'doctor') {
                      $sq->orWhere('specialization', 'LIKE', $like)->orWhere('consultation_expertise', 'LIKE', $like)->orWhere('health_conditions_treated', 'LIKE', $like)->orWhere('panchakarma_procedures', 'LIKE', $like)->orWhere('external_therapies', 'LIKE', $like);
                  } elseif ($modelType === 'mindfulness') {
                      $sq->orWhere('practitioner_type', 'LIKE', $like)->orWhere('services_offered', 'LIKE', $like)->orWhere('client_concerns', 'LIKE', $like);
                  } elseif ($modelType === 'yoga') {
                      $sq->orWhere('yoga_therapist_type', 'LIKE', $like)->orWhere('areas_of_expertise', 'LIKE', $like);
                  }
              });
        };

        $practitioners = Practitioner::where(function($q) use ($applySearchFilter, $query) { $applySearchFilter($q, 'practitioner', $query); })->with('user')->take(5)->get();
        $doctors = Doctor::where(function($q) use ($applySearchFilter, $query) { $applySearchFilter($q, 'doctor', $query); })->with('user')->take(5)->get();
        $mindfulness = MindfulnessPractitioner::where(function($q) use ($applySearchFilter, $query) { $applySearchFilter($q, 'mindfulness', $query); })->with('user')->take(5)->get();
        $yoga = YogaTherapist::where(function($q) use ($applySearchFilter, $query) { $applySearchFilter($q, 'yoga', $query); })->with('user')->take(5)->get();

        $allPractitioners = $practitioners->merge($doctors)->merge($mindfulness)->merge($yoga)->take(10);

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

        foreach ($allPractitioners as $p) {
            $specialty = 'Professional';
            if ($p->user && $p->user->userServices->first()) {
                $specialty = $p->user->userServices->first()->service->title;
            } else {
                $specialties = array_merge((array)($p->body_therapies ?? []), (array)($p->consultations ?? []), (array)($p->other_modalities ?? []));
                if (!empty($specialties)) {
                    $specialty = $specialties[0];
                }
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

    public function searchServices(Request $request)
    {
        $query = $request->get('query');
        $practitionerId = $request->get('practitioner_id');
        
        $servicesQuery = Service::where('status', true);
        
        if ($practitionerId) {
            $practitioner = Practitioner::find($practitionerId);
            if ($practitioner && $practitioner->user_id) {
                $servicesQuery->whereHas('userServices', function($q) use ($practitioner) {
                    $q->where('user_id', $practitioner->user_id);
                });
            }
        }
        
        if (!empty($query)) {
            $servicesQuery->where('title', 'LIKE', "%{$query}%");
        }
        
        $services = $servicesQuery->limit(10)->get();
        
        return response()->json($services);
    }

    public function fetchServiceScheduleForm(Request $request)
    {
        $serviceId = $request->get('service_id');
        $practitionerId = $request->get('practitioner_id');
        $iteration = $request->get('iteration', 1);

        $service = Service::find($serviceId);
        $activePractitioner = Practitioner::find($practitionerId);

        if (!$service || !$activePractitioner) {
            return response()->json(['error' => 'Service or Practitioner not found'], 404);
        }

        $practitionerServices = $activePractitioner->user
            ? $activePractitioner->user->userServices()->with('service')->where('status', 'active')->get()->groupBy('service_id')
            : collect();

        $practitionerCountry = $activePractitioner->country ?? $activePractitioner->user->country ?? null;
        $countryCurrencyMap = [
            'IN' => 'INR','IND' => 'INR','INDIA' => 'INR',
            'US' => 'USD','USA' => 'USD','UNITED STATES' => 'USD',
            'GB' => 'GBP','UK' => 'GBP','UNITED KINGDOM' => 'GBP',
            'AE' => 'AED','UAE' => 'AED',
            'EU' => 'EUR','FR' => 'EUR','DE' => 'EUR','ES' => 'EUR','IT' => 'EUR',
        ];
        $derivedCurrency = $practitionerCountry ? ($countryCurrencyMap[strtoupper($practitionerCountry)] ?? config('app.currency', 'INR')) : config('app.currency', 'INR');

        return view('partials.frontend.service-schedule-item', compact('service', 'practitionerServices', 'derivedCurrency', 'iteration'))->render();
    }

    public function practitionerDetail(Request $request, $slug)
    {
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);

        // Try lookup by slug across all professional profile models
        $practitioner = Practitioner::with(['user', 'reviews', 'userServices.service'])->where('slug', $slug)->first();

        if (!$practitioner) {
            $practitioner = Doctor::with(['user', 'reviews', 'userServices.service'])->where('slug', $slug)->first();
        }
        if (!$practitioner) {
            $practitioner = MindfulnessPractitioner::with(['user', 'reviews', 'userServices.service'])->where('slug', $slug)->first();
        }
        if (!$practitioner) {
            $practitioner = YogaTherapist::with(['user', 'reviews', 'userServices.service'])->where('slug', $slug)->first();
        }

        // Fallback to ID-based lookup if slug didn't match and it's numeric
        if (!$practitioner && is_numeric($slug)) {
            $practitioner = Practitioner::with(['user', 'reviews', 'userServices.service'])->find($slug)
                ?? Doctor::with(['user', 'reviews', 'userServices.service'])->find($slug)
                ?? MindfulnessPractitioner::with(['user', 'reviews', 'userServices.service'])->find($slug)
                ?? YogaTherapist::with(['user', 'reviews', 'userServices.service'])->find($slug);
        }
        if (!$practitioner) {
            abort(404);
        }

        $selectedService = null;
        if ($request->filled('service')) {
            $service = $request->query('service');
            if (is_numeric($service)) {
                $selectedService = Service::find($service);
            } else {
                $selectedService = Service::where('slug', $service)->first();
                if (!$selectedService && is_string($service) && preg_match('/-(en|fr|de|es|it|pt|nl)$/', $service)) {
                    $baseSlug = preg_replace('/-(en|fr|de|es|it|pt|nl)$/', '', $service);
                    $selectedService = Service::where('slug', $baseSlug)->first();
                }
            }
        }

        // Add a helper attribute for easy access to bio and name across different models if needed
        // but for now we'll rely on the blade handling it or common naming conventions
        
        return view('practitioner-detail', compact('practitioner', 'settings', 'selectedService'));
    }

    public function filterPractitioners(Request $request)
    {
        $query = $request->get('query');
        $language = App::getLocale();
        $settings = HomepageSetting::getAllSettings($language);

        $pQuery = Practitioner::with(['user', 'reviews'])->where('status', 'active');
        $dQuery = Doctor::with(['user', 'reviews'])->where('status', 'active');
        $mQuery = MindfulnessPractitioner::with(['user', 'reviews'])->where('status', 'active');
        $yQuery = YogaTherapist::with(['user', 'reviews'])->where('status', 'active');

        // Only show practitioners who offer some service
        $serviceCheck = function ($q) {
            $q->whereHas('userServices', function ($sq) {
                $sq->where('status', 'active');
            });
        };
        $pQuery->where($serviceCheck);
        $dQuery->where($serviceCheck);
        $mQuery->where($serviceCheck);
        $yQuery->where($serviceCheck);

        if (!empty($query)) {
            $pQuery->where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('consultations', 'LIKE', "%{$query}%")
                    ->orWhere('other_modalities', 'LIKE', "%{$query}%");
            });
            $dQuery->where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('specialization', 'LIKE', "%{$query}%")
                    ->orWhere('consultation_expertise', 'LIKE', "%{$query}%");
            });
            $mQuery->where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('practitioner_type', 'LIKE', "%{$query}%")
                    ->orWhere('client_concerns', 'LIKE', "%{$query}%");
            });
            $yQuery->where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('yoga_therapist_type', 'LIKE', "%{$query}%")
                    ->orWhere('areas_of_expertise', 'LIKE', "%{$query}%");
            });
        }

        $practitioners = $pQuery->get()
            ->merge($dQuery->get())
            ->merge($mQuery->get())
            ->merge($yQuery->get());

        return view('partials.frontend.practitioner-slides', compact('practitioners', 'settings'))->render();
    }

    public function zayaLogin(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $adminRoles = ['super-admin', 'admin', 'country-admin', 'financial-manager', 'content-manager', 'user-manager'];
            if (in_array($user->role, $adminRoles)) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        $redirect = $request->query('redirect');
        $available_languages = \App\Models\Language::where('status', 'active')->get();
        return view('zaya-login', compact('available_languages', 'redirect'));
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
        $clientRegistrationCurrency = strtoupper($financeSettings['client_registration_fee_currency'] ?? 'EUR');
        $countryNameToCode = \App\Models\Country::pluck('code', 'name')->map(fn($c) => strtoupper($c));

        $defaultCurrency = $this->deriveCurrencyFromCountry($request->get('country', ''));

        return view('client-register', compact('redirect', 'consultationPreferences', 'languages', 'clientRegistrationFee', 'clientRegistrationFeeEnabled', 'defaultCurrency', 'clientRegistrationCurrency', 'countryNameToCode'));
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
        $practitionerRegistrationCurrency = strtoupper($financeSettings['practitioner_registration_fee_currency'] ?? config('currencies.default', 'EUR'));
        $practitionerRegistrationCurrencySymbol = config('currencies.symbols')[$practitionerRegistrationCurrency] ?? $practitionerRegistrationCurrency;

        $languages = \App\Models\Language::where('status', 'active')->orderBy('name')->get();

        return view('practitioner-register', compact(
            'wellnessConsultations',
            'bodyTherapies',
            'otherModalities',
            'practitionerRegistrationFee',
            'practitionerRegistrationFeeEnabled',
            'practitionerRegistrationCurrency',
            'practitionerRegistrationCurrencySymbol',
            'languages'
        ));
    }

    public function joinRegister(string $role, string $token = null)
    {
        $normalized = str_replace('_', '-', strtolower(trim($role)));
        $map = [
            'doctor' => ['role' => 'doctor', 'label' => 'Ayurvedic Doctor'],
            'mindfulness-practitioner' => ['role' => 'mindfulness_practitioner', 'label' => 'Mindfulness Counsellor'],
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
            'languages' => Language::orderBy('name')->get(), // Fetch all languages sorted A-Z
            'countries' => Country::orderBy('name')->get(), // Fetch all countries sorted A-Z
            'openRegisterToken' => $token,
        ];

        $viewData['financeSettings'] = HomepageSetting::getSectionValues('finance', $language);
        if ($joinRole === 'doctor') {
            $viewData['specializations'] = Specialization::where('status', 1)->get();
            $viewData['consultationExpertise'] = AyurvedaExpertise::where('status', 1)->get();
            $viewData['healthConditions'] = HealthCondition::where('status', 1)->get();
            $viewData['externalTherapies'] = ExternalTherapy::where('status', 1)->get();
            $viewData['qualifications'] = Qualification::where('status', 1)->get();
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

    public function getRegistrationFee(Request $request)
    {
        $role = $request->input('role', 'client');
        $countryName = $request->input('country');
        $token = $request->input('token');
        
        $countryCode = 'all';
        if ($countryName) {
            $dbCountry = \App\Models\Country::where('name', $countryName)->first();
            if ($dbCountry) {
                $countryCode = strtoupper($dbCountry->code);
            }
        }
        $language = session('locale', 'en');

        $map = [
            'practitioner' => 'practitioner_registration_fee',
            'doctor' => 'doctor_registration_fee',
            'mindfulness_practitioner' => 'mindfulness_registration_fee',
            'yoga_therapist' => 'yoga_registration_fee',
            'translator' => 'translator_registration_fee',
            'client' => 'client_registration_fee',
        ];

        if (!isset($map[$role])) {
            return response()->json(['error' => 'Invalid role'], 400);
        }

        $settings = HomepageSetting::getSectionValues('finance', $language, $countryCode);
        $feeKey = $map[$role];
        $currencyKey = $feeKey . '_currency';

        $fee = (float) ($settings[$feeKey] ?? 0);
        $currency = $settings[$currencyKey] ?? 'EUR';

        // Override with token currency if available
        if ($token) {
            $link = OpenRegisterLink::where('token', $token)->first();
            if ($link && $link->currency) {
                $currency = $link->currency;
                // If the currency is different from the mapped one, we might need to fetch the fee for that specific currency
                // But according to the requirement, the fee is fetched based on the user type and currency.
                // In our system, fees are set PER COUNTRY (which maps to a currency).
                // If a link is generated with a specific currency, we should probably find a country that uses that currency to get the fee.
                // Or if the fee is already set for that currency globally.
                
                // Let's check if there's a country setting for this currency
                $countryToCurrency = config('currencies.country_to_currency', []);
                $revMap = array_flip($countryToCurrency);
                if (isset($revMap[$currency])) {
                    $targetCountryCode = $revMap[$currency];
                    $regionalSettings = HomepageSetting::getSectionValues('finance', $language, $targetCountryCode);
                    if (isset($regionalSettings[$feeKey])) {
                        $fee = (float) $regionalSettings[$feeKey];
                    }
                }
            }
        }

        return response()->json([
            'fee' => $fee,
            'currency' => $currency,
            'enabled' => filter_var($settings[$feeKey . '_enabled'] ?? '1', FILTER_VALIDATE_BOOLEAN)
        ]);
    }

    public function validatePromoCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'usage_type' => ['nullable', 'string', 'in:registration,booking'],
            'country' => ['nullable', 'string'],
        ]);

        $usageType = $request->input('usage_type', 'registration');
        $baseFee = 0.0;

        if ($usageType === 'registration') {
            $role = $request->input('role', 'practitioner');
            $countryName = $request->input('country');
            $countryCode = 'all';
            if ($countryName) {
                $dbCountry = \App\Models\Country::where('name', $countryName)->first();
                if ($dbCountry) {
                    $countryCode = strtoupper($dbCountry->code);
                }
            }

            $feeKey = match ($role) {
                'client' => 'client_registration_fee',
                'patient' => 'client_registration_fee',
                'doctor' => 'doctor_registration_fee',
                'mindfulness_practitioner' => 'mindfulness_registration_fee',
                'yoga_therapist' => 'yoga_registration_fee',
                'translator' => 'translator_registration_fee',
                default => 'practitioner_registration_fee',
            };

            $language = session('locale', 'en');
            $financeSettings = HomepageSetting::getSectionValues('finance', $language, $countryCode);
            $baseFee = $financeSettings[$feeKey] ?? '0';
            $baseFee = is_numeric($baseFee) ? (float) $baseFee : 0.0;

            $feeEnabledKey = $feeKey . '_enabled';
            $feeEnabled = filter_var($financeSettings[$feeEnabledKey] ?? '1', FILTER_VALIDATE_BOOLEAN);
            if (!$feeEnabled) {
                return response()->json(['message' => 'Fee is currently disabled.'], 422);
            }
        } else {            // For bookings, we use the amount passed from frontend
            $baseFee = (float) $request->input('amount', 0);
        }

        $code = trim((string) $request->input('code'));
        $promo = PromoCode::whereRaw('LOWER(code) = ?', [mb_strtolower($code)])->first();

        if (!$promo || !$promo->status) {
            return response()->json(['message' => 'Invalid promo code.'], 422);
        }

        // Check usage type
        if ($promo->usage_type !== 'both' && $promo->usage_type !== $usageType) {
            // User requested to allow promo codes on all registration pages regardless of their saved usage_type
            if ($usageType !== 'registration') {
                return response()->json(['message' => 'This promo code is not applicable for ' . $usageType . '.'], 422);
            }
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
        if ($request->has('service') && trim((string) $request->query('service')) === '') {
            $queryParams = $request->query();
            unset($queryParams['service']);
            $routeParams = [];
            if ($practitioner) {
                $routeParams['practitioner'] = $practitioner;
            }
            return redirect()->route('book-session', array_merge($routeParams, $queryParams));
        }

        // Handle prefilled service from request
        $prefilledService = null;
        if ($request->filled('service')) {
            $serviceSlug = trim((string) $request->query('service'));
            $prefilledService = $serviceSlug !== '' ? Service::where('slug', $serviceSlug)->first() : null;

            if (
                !$prefilledService
                && $serviceSlug !== ''
                && preg_match('/-(en|fr|de|es|it|pt|nl)$/', $serviceSlug)
            ) {
                $baseSlug = preg_replace('/-(en|fr|de|es|it|pt|nl)$/', '', $serviceSlug);
                $prefilledService = Service::where('slug', $baseSlug)->first();
            }
        } elseif ($request->filled('service_id')) {
            $prefilledService = Service::find($request->query('service_id'));
        }

        $pQuery = Practitioner::with(['user', 'reviews'])->where('status', 'active');
        $dQuery = Doctor::with(['user', 'reviews'])->where('status', 'active');
        $mQuery = MindfulnessPractitioner::with(['user', 'reviews'])->where('status', 'active');
        $yQuery = YogaTherapist::with(['user', 'reviews'])->where('status', 'active');

        // Only show practitioners who offer some service
        $serviceCheck = function ($q) {
            $q->whereHas('userServices', function ($sq) {
                $sq->where('status', 'active');
            });
        };
        $pQuery->where($serviceCheck);
        $dQuery->where($serviceCheck);
        $mQuery->where($serviceCheck);
        $yQuery->where($serviceCheck);

        // If service is provided, only show practitioners who offer that specific service.
        if ($prefilledService) {
            $specificServiceCheck = function ($q) use ($prefilledService) {
                $q->whereHas('userServices', function ($sq) use ($prefilledService) {
                    $sq->where('status', 'active')->where('service_id', $prefilledService->id);
                });
            };
            $pQuery->where($specificServiceCheck);
            $dQuery->where($specificServiceCheck);
            $mQuery->where($specificServiceCheck);
            $yQuery->where($specificServiceCheck);
        }

        $results = $pQuery->get()
            ->merge($dQuery->get())
            ->merge($mQuery->get())
            ->merge($yQuery->get());

        $selectedPractitioner = null;
        if ($practitioner) {
            $selectedPractitioner = $results->where('slug', $practitioner)->first();
        }

        if (!$selectedPractitioner && $request->filled('practitioner_id')) {
            $selectedPractitioner = $results->where('id', $request->query('practitioner_id'))->first();
        }

        $practitioners = $results;

        if (!$selectedPractitioner && $practitioners->isNotEmpty()) {
            $selectedPractitioner = $practitioners->first();
        }

        // Filter services based on selected practitioner using the new user_services table
        if ($selectedPractitioner && $selectedPractitioner->user) {
            $services = Service::where('status', true)
                ->whereHas('userServices', function($q) use ($selectedPractitioner) {
                    $q->where('user_id', $selectedPractitioner->user_id);
                })
                ->limit(15)
                ->get();
        } else {
            $services = Service::where('status', true)->limit(15)->get();
        }

        $languages = \App\Models\Language::where('status', 'active')
            ->orderBy('name')
            ->get();
        $consultationPreferences = \App\Models\ClientConsultationPreference::all();

        $userPromoCodes = collect();
        if (auth()->check() && Schema::hasTable('user_promo_codes')) {
            $userPromoCodes = auth()->user()->userPromoCodes()->latest()->get();
        }

        return view('book-session', compact('practitioners', 'selectedPractitioner', 'services', 'languages', 'consultationPreferences', 'prefilledService', 'userPromoCodes'));
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
