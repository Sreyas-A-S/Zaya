<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Conference;
use App\Models\Country;
use App\Models\ConsultationForm;
use App\Models\Service;
use App\Models\UserService;
use App\Models\PractitionerGallery;
use App\Traits\ImageUploadTrait;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class ProfileController extends Controller
{
    use ImageUploadTrait;

    private function getBookingQuery($user, $forceClientView = false)
    {
        $role = $user->role;
        $profileId = $user->profile_id;
        $query = Booking::with(['practitioner.user', 'user']);

        if ($forceClientView || $role === 'client' || $role === 'patient') {
            $query->where('user_id', $user->id);
        } elseif ($role === 'translator') {
            $query->where('translator_id', $profileId);
        } else {
            // Practitioners, Doctors, Mindfulness, Yoga
            $query->where('practitioner_id', $profileId);
        }

        return $query;
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Dynamic loading based on role
        $relation = match ($user->role) {
            'client', 'patient' => 'patient',
            'practitioner' => 'practitioner',
            'doctor' => 'doctor',
            'mindfulness_practitioner' => 'mindfulnessPractitioner',
            'yoga_therapist' => 'yogaTherapist',
            'translator' => 'translator',
            default => null,
        };
        if ($relation) $user->load($relation);

        $bookingQuery = $this->getBookingQuery($user);

        $upcomingBookings = (clone $bookingQuery)
            ->where(function ($query) {
                $query->where('booking_date', '>', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('booking_date', now()->toDateString())
                            ->where('status', '!=', 'completed');
                    });
            })
            ->where('status', '!=', 'cancelled')
            ->orderBy('booking_date', 'asc')
            ->orderBy('booking_time', 'asc')
            ->take(5)
            ->get();

        $completedBookings = (clone $bookingQuery)
            ->where(function ($query) {
                $query->where('status', 'completed')
                    ->orWhere('booking_date', '<', now()->toDateString());
            })
            ->latest('booking_date')
            ->take(10)
            ->get();

        $reviews = \App\Models\PractitionerReview::with('practitioner.user')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Invoices are usually client-centric (payments they made)
        $invoices = \App\Models\Booking::where('user_id', $user->id)
            ->whereNotNull('razorpay_payment_id')
            ->latest()
            ->take(5)
            ->get();

        $allServices = \App\Models\Service::whereIn('id', collect($upcomingBookings->pluck('service_ids'))->collapse()->unique())->get()->keyBy('id');
        $allServices = $allServices->merge(\App\Models\Service::whereIn('id', collect($completedBookings->pluck('service_ids'))->collapse()->unique())->get()->keyBy('id'));

        $clinicalDocuments = $user->clinicalDocuments()->latest()->get();

        $referrals = [];
        $dataAccessRequests = [];
        if (in_array($user->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist'])) {
            $referrals = \App\Models\Referral::with(['user', 'referredTo'])
                ->where('referred_by_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
            
            $dataAccessRequests = \App\Models\DataAccessRequest::with(['client'])
                ->where('requester_id', $user->id)
                ->where('status', 'approved')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', compact('user', 'upcomingBookings', 'completedBookings', 'reviews', 'invoices', 'allServices', 'clinicalDocuments', 'referrals', 'dataAccessRequests'));
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
        ]);

        $user = Auth::user();
        $file = $request->file('document');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize();

        $path = $file->store('clinical_documents/' . $user->id, 'public');

        $document = $user->clinicalDocuments()->create([
            'file_name' => $originalName,
            'file_path' => $path,
            'file_type' => $extension,
            'file_size' => $size,
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully',
            'document' => $document,
            'url' => asset('storage/' . $path)
        ]);
    }

    public function deleteDocument($id)
    {
        $user = Auth::user();
        $document = $user->clinicalDocuments()->findOrFail($id);

        if (\Storage::disk('public')->exists($document->file_path)) {
            \Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return response()->json(['message' => 'Document deleted successfully']);
    }

    public function updateConsent(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;
        if (!$profile || !method_exists($profile, 'update')) {
            return response()->json(['message' => 'Profile not supported'], 404);
        }

        $profile->update([
            'data_sharing_consent' => $request->consent ? 1 : 0
        ]);

        return response()->json(['message' => 'Consent updated successfully', 'consent' => $profile->data_sharing_consent]);
    }

    public function conferences(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $isConsultations = $request->routeIs('consultations.index');

        // Show actual recorded conference sessions
        $query = Conference::with(['booking.practitioner.user', 'booking.user'])
            ->whereHas('booking', function($q) use ($user, $isConsultations) {
                if ($isConsultations) {
                    $q->where('practitioner_id', $user->profile_id);
                } else {
                    $q->where('user_id', $user->id);
                }
            });

        $conferences = $query->latest()->paginate(15);

        if ($request->ajax()) {
            return view('partials.conferences-table', compact('user', 'conferences'))->render();
        }

        return view('bookings', compact('user', 'conferences'));
    }

    public function storeConference(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'provider' => 'required|string',
            'room_name' => 'nullable|string',
        ]);

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $duration = $start->diffInMinutes($end);

        $conference = Conference::create([
            'booking_id' => $request->booking_id,
            'start_time' => $start,
            'end_time' => $end,
            'duration_minutes' => $duration,
            'provider' => $request->provider,
            'room_name' => $request->room_name,
            'metadata' => $request->metadata ?? [],
        ]);

        return response()->json(['success' => true, 'conference' => $conference]);
    }

    public function showConsultationForm(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::with(['practitioner.user', 'user', 'translator.user'])->findOrFail($id);

        $consultationFormRole = in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist'], true)
            ? $user->role
            : null;
        $isOwner = $booking->user_id === $user->id || $booking->practitioner_id === $user->profile_id;

        if (!$consultationFormRole || !$isOwner) {
            abort(403);
        }

        $consultationSchema = [];
        $schemaPath = resource_path('schemas/consultation/' . $consultationFormRole . '.json');
        if (file_exists($schemaPath)) {
            $decoded = json_decode((string) file_get_contents($schemaPath), true);
            if (is_array($decoded)) {
                $consultationSchema = $decoded;
            }
        }

        $existingForm = ConsultationForm::where('booking_id', $booking->id)
            ->where('doctor_id', $user->id)
            ->latest('created_at')
            ->first();

        $consultationPayload = $existingForm->payload ?? [];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'payload' => $consultationPayload,
            ]);
        }

        return view('consultation-form', compact(
            'user',
            'booking',
            'consultationSchema',
            'consultationPayload',
            'existingForm',
            'consultationFormRole'
        ));
    }

    public function storeConsultationForm(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::with(['practitioner.user', 'user'])->findOrFail($id);

        $consultationFormRole = in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist'], true)
            ? $user->role
            : null;
        $isOwner = $booking->user_id === $user->id || $booking->practitioner_id === $user->profile_id;

        if (!$consultationFormRole || !$isOwner) {
            abort(403);
        }

        $payload = Arr::except($request->all(), ['_token', '_method']);

        ConsultationForm::create([
            'booking_id' => $booking->id,
            'doctor_id' => $user->id,
            'payload' => $payload,
        ]);

        return redirect()
            ->route('bookings.consultation-form.show', $booking->id)
            ->with('status', 'Consultation form saved successfully.');
    }

    public function transactions(Request $request)
    {
        $user = Auth::user();
        $invoices = \App\Models\Booking::with(['practitioner.user', 'translator.user'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->practitioner) {
                    $q->orWhere('practitioner_id', $user->practitioner->id);
                }
                if ($user->translator) {
                    $q->orWhere('translator_id', $user->translator->id);
                }
            })
            ->latest()
            ->paginate(15);

        if ($request->ajax()) {
            return view('partials.transactions-table', compact('user', 'invoices'))->render();
        }

        return view('transactions', compact('user', 'invoices'));
    }

    public function healthJourney()
    {
        $user = Auth::user();
        $clinicalDocuments = $user->clinicalDocuments()->latest()->get();
        
        // Only show bookings that have a consultation form attached
        $consultations = Booking::with(['practitioner.user', 'consultationForm'])
            ->where('user_id', $user->id)
            ->whereHas('consultationForm')
            ->latest()
            ->get();

        return view('health-journey', compact('user', 'clinicalDocuments', 'consultations'));
    }

    public function conferences(Request $request)
    {
        $user = Auth::user();
        $conferences = $this->getBookingQuery($user)
            ->where('mode', 'online')
            ->latest()
            ->paginate(15);

        if ($request->ajax()) {
            return view('partials.conferences-table', compact('user', 'conferences'))->render();
        }

        return view('conference-history', compact('user', 'conferences'));
    }

    public function showRecording($id)
    {
        $user = Auth::user();
        
        // Find the booking
        $booking = Booking::with(['practitioner.user', 'user'])->findOrFail($id);

        // Check if user is the client or the original practitioner
        $isParticipant = ($booking->user_id === $user->id) || 
                         ($booking->practitioner && $booking->practitioner->user_id === $user->id) ||
                         ($booking->translator && $booking->translator->user_id === $user->id);

        if (!$isParticipant) {
            // Check if this is a practitioner with OTP-verified access to this client
            $hasAccess = \App\Http\Controllers\DataAccessController::hasAccess($user->id, $booking->user_id);
            if (!$hasAccess) {
                abort(403);
            }
        }

        if (!$booking->recording_url) {
            return back()->with('error', 'No recording available for this session.');
        }

        return view('recordings.show', compact('user', 'booking'));
    }

    public function showDetails($id)
    {
        $user = Auth::user();
        $booking = $this->getBookingQuery($user)
            ->with(['language', 'translator.user'])
            ->findOrFail($id);

        $serviceIds = is_array($booking->service_ids) ? $booking->service_ids : [];
        $services = Service::whereIn('id', $serviceIds)->get();

        return view('partials.booking-details-modal-content', compact('user', 'booking', 'services'))->render();
    }

    public function viewClientProfile($id)
    {
        $practitioner = Auth::user();
        
        // Ensure only practitioners/doctors can access this
        if (in_array($practitioner->role, ['client', 'patient'])) {
            abort(403);
        }

        // Check for OTP-verified access
        $hasAccess = \App\Http\Controllers\DataAccessController::hasAccess($practitioner->id, $id);
        
        if (!$hasAccess) {
            return redirect()->route('bookings.index')->with('error', 'You do not have permission to view this client\'s profile. Please verify via OTP first.');
        }

        $client = \App\Models\User::with(['patient'])->findOrFail($id);
        
        // Get all recordings for this client
        $recordings = Booking::where('user_id', $client->id)
            ->whereNotNull('recording_url')
            ->latest()
            ->get();

        // Get booking history
        $bookings = Booking::with(['practitioner.user'])
            ->where('user_id', $client->id)
            ->latest()
            ->get();

        $user = Auth::user();

        return view('practitioner.client-profile', compact('user', 'client', 'recordings', 'bookings'));
    }

    public function myServices()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Get services already linked to this user
        $myServices = UserService::with('service')
            ->where('user_id', $user->id)
            ->get()
            ->groupBy('service_id');

        $defaultCurrency = $this->deriveCurrency($user);
        $profile = $user->practitioner ?? $user->doctor ?? $user->mindfulnessPractitioner ?? $user->yogaTherapist ?? null;
        $reminderLeadTime = $profile->reminder_lead_time ?? 60;

        $nextOnlineBooking = Booking::where('practitioner_id', $user->profile_id)
            ->where('mode', 'online')
            ->where('status', 'confirmed')
            ->whereDate('booking_date', '>=', now()->toDateString())
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->first();

        return view('client.my-services', compact('user', 'myServices', 'defaultCurrency', 'reminderLeadTime', 'nextOnlineBooking'));
    }

    public function updateReminderSettings(Request $request)
    {
        return back()->with('status', 'Reminder emails are sent 60 minutes before the session.');
    }

    public function getAvailableServices()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Fetch all active services (allow reselection of already added services for additional rate sets)
        $availableServices = Service::where('status', '!=', 0)
            ->orderBy('title')
            ->get(['id', 'title']);

        return response()->json([
            'status' => true,
            'data' => $availableServices
        ]);
    }
    public function storeService(Request $request)
    {
        $request->validate([
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.rates' => 'required|array|min:1',
            'services.*.rates.*.rate' => 'required|numeric|min:0',
            'services.*.rates.*.duration' => 'required|integer|min:1',
            'services.*.currency' => 'nullable|string|size:3',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        foreach ($request->services as $serviceData) {
            $currency = $serviceData['currency'] ?? $this->deriveCurrency($user);
            foreach ($serviceData['rates'] as $rateData) {
                UserService::updateOrCreate(
                    [
                        'user_id' => $user->id, 
                        'service_id' => $serviceData['service_id'],
                        'duration' => $rateData['duration']
                    ],
                    [
                        'rate' => $rateData['rate'], 
                        'status' => 'active',
                        'currency' => strtoupper($currency)
                    ]
                );
            }
        }

        return back()->with('status', 'Services and rates added successfully!');
    }

    public function deleteService($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        UserService::where('user_id', $user->id)->where('id', $id)->delete();

        return back()->with('status', 'Rate removed successfully!');
    }

    public function deleteServiceGroup($service_id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        UserService::where('user_id', $user->id)->where('service_id', $service_id)->delete();

        return back()->with('status', 'Service and all its rates removed successfully!');
    }

    private function deriveCurrency($user)
    {
        $country = $user->country ?? $user->practitioner->country ?? $user->doctor->country ?? null;
        $map = config('currencies.country_to_currency', []);
        $fallback = config('currencies.default', config('app.currency', 'INR'));

        if ($country) {
            $code = strtoupper(trim($country));
            if (isset($map[$code])) {
                return $map[$code];
            }
            $alpha2 = strtoupper(substr($code, 0, 2));
            if (isset($map[$alpha2])) {
                return $map[$alpha2];
            }
        }

        return $fallback;
    }

    public function updateProfilePic(Request $request)
    {
        $request->validate([
            'cropped_image' => 'required|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Delete old profile pic if exists
        if ($user->profile_pic && !str_starts_with($user->profile_pic, 'http')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_pic);
        }

        $path = $this->uploadBase64($request->cropped_image, 'profiles');
        $user->profile_pic = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile picture updated successfully!',
            'path' => asset('storage/' . $path)
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Password updated successfully!']);
        }

        return back()->with('status', 'Password updated successfully!');
    }

    public function updatePersonalDetails(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,transgender,other',
            'dob' => 'nullable|date|before:today',
            'nationality' => 'nullable|string|max:255|exists:countries,name',
            'address_line_1' => 'nullable|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return back()->with('error', 'Profile not found.');
        }

        $user->phone = $validated['phone'] ?? null;
        $user->gender = $validated['gender'] ?? null;
        $user->national_id = !empty($validated['nationality'])
            ? Country::where('name', $validated['nationality'])->value('id')
            : null;
        $user->save();

        $profileData = [];
        foreach (['phone', 'gender', 'dob', 'nationality', 'address_line_1', 'address_line_2', 'city', 'state', 'zip_code', 'country'] as $field) {
            if (\Schema::hasColumn($profile->getTable(), $field)) {
                $profileData[$field] = $validated[$field] ?? null;
            }
        }

        if (!empty($profileData)) {
            $profile->update($profileData);
        }

        return back()->with('status', 'Personal details updated successfully!');
    }

    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $relation = match ($user->role) {
            'client', 'patient' => 'patient',
            'practitioner' => 'practitioner',
            'doctor' => 'doctor',
            'mindfulness_practitioner' => 'mindfulnessPractitioner',
            'yoga_therapist' => 'yogaTherapist',
            'translator' => 'translator',
            default => null,
        };
        if ($relation) $user->load([$relation, 'nationality']);

        $profile = $user->profile;

        // Stats
        $bookingQuery = $this->getBookingQuery($user);
        $totalSessions = (clone $bookingQuery)->where('status', 'completed')->count();
        $totalClients = (clone $bookingQuery)->distinct('user_id')->count();
        $todaySessions = (clone $bookingQuery)->where('booking_date', now()->toDateString())->count();
        $upcomingSessions = (clone $bookingQuery)->where('booking_date', '>', now()->toDateString())->count();

        // History
        $servicesHistory = (clone $bookingQuery)
            ->with('user')
            ->where('status', 'completed')
            ->latest('booking_date')
            ->take(5)
            ->get();
        
        $upcomingServices = (clone $bookingQuery)
            ->with('user')
            ->where('booking_date', '>=', now()->toDateString())
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->orderBy('booking_date', 'asc')
            ->orderBy('booking_time', 'asc')
            ->take(5)
            ->get();

        $gallery = PractitionerGallery::where('user_id', $user->id)->get()->groupBy('category');

        // Master Data for Specialities & Conditions
        $allSpecialities = [];
        $allConditions = [];

        switch ($user->role) {
            case 'practitioner':
                $allSpecialities = \App\Models\WellnessConsultation::where('status', true)->pluck('name');
                $allConditions = \App\Models\BodyTherapy::where('status', true)->pluck('name');
                break;
            case 'doctor':
                $allSpecialities = \App\Models\Specialization::where('status', true)->pluck('name');
                $allConditions = \App\Models\HealthCondition::where('status', true)->pluck('name');
                break;
            case 'mindfulness_practitioner':
                $allSpecialities = \App\Models\MindfulnessService::where('status', true)->pluck('name');
                $allConditions = \App\Models\ClientConcern::where('status', true)->pluck('name');
                break;
            case 'yoga_therapist':
                // Yoga therapist specialities are currently free text or generic
                $allConditions = \App\Models\YogaExpertise::pluck('name');
                break;
            case 'translator':
                $allSpecialities = \App\Models\TranslatorSpecialization::where('status', true)->pluck('name');
                $allConditions = \App\Models\TranslatorService::where('status', true)->pluck('name');
                break;
        }

        $countries = Country::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return view('client.profile', compact(
            'user',
            'profile',
            'countries',
            'totalSessions',
            'totalClients',
            'todaySessions',
            'upcomingSessions',
            'servicesHistory', 
            'upcomingServices',
            'gallery',
            'allSpecialities',
            'allConditions'
        ));
        }
    public function uploadGalleryImage(Request $request)
    {
        $request->validate([
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'cropped_image' => 'nullable|string', // single base64
            'cropped_images.*' => 'string', // array of base64
            'category' => 'nullable|string|in:sanctuary,rituals,soul,moments'
        ]);

        $user = Auth::user();
        $paths = [];
        $category = $request->category ?? 'sanctuary';

        // Handle single cropped image
        if ($request->filled('cropped_image')) {
            $paths[] = $this->uploadBase64($request->cropped_image, 'practitioner_galleries/' . $user->id);
        }
        
        // Handle multiple cropped images
        if ($request->has('cropped_images')) {
            foreach ($request->cropped_images as $base64) {
                $paths[] = $this->uploadBase64($base64, 'practitioner_galleries/' . $user->id);
            }
        }

        // Handle raw image uploads (single or multiple)
        if ($request->hasFile('image')) {
            $files = is_array($request->file('image')) ? $request->file('image') : [$request->file('image')];
            foreach ($files as $file) {
                $paths[] = $file->store('practitioner_galleries/' . $user->id, 'public');
            }
        }

        if (!empty($paths)) {
            foreach ($paths as $path) {
                PractitionerGallery::create([
                    'user_id' => $user->id,
                    'image_path' => $path,
                    'category' => $category
                ]);
            }

            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'message' => count($paths) . ' image(s) added to gallery!']);
            }
            return back()->with('status', count($paths) . ' image(s) added to gallery!');
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'error', 'message' => 'No images uploaded.'], 400);
        }
        return back()->with('error', 'No images uploaded.');
    }

    public function deleteGalleryImage($id)
    {
        $user = Auth::user();
        $image = PractitionerGallery::where('user_id', $user->id)->findOrFail($id);

        if (\Storage::disk('public')->exists($image->image_path)) {
            \Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return back()->with('status', 'Image removed from gallery.');
    }

    public function updateProfessionalDetails(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;
        if (!$profile) return back()->with('error', 'Profile not found.');

        $updateType = $request->input('update_type');
        $data = [];

        if ($updateType === 'specialities') {
            $specialities = $request->input('specialities', []);
            switch ($user->role) {
                case 'practitioner': $data['consultations'] = $specialities; break;
                case 'doctor': $data['specialization'] = $specialities; break;
                case 'mindfulness_practitioner': $data['practitioner_type'] = $specialities; break;
                case 'yoga_therapist': $data['yoga_therapist_type'] = $specialities; break;
                case 'translator': $data['fields_of_specialization'] = $specialities; break;
            }
        } elseif ($updateType === 'conditions') {
            $conditions = $request->input('conditions', []);
            switch ($user->role) {
                case 'practitioner': $data['body_therapies'] = $conditions; break;
                case 'doctor': $data['health_conditions_treated'] = $conditions; break;
                case 'mindfulness_practitioner': $data['client_concerns'] = $conditions; break;
                case 'yoga_therapist': $data['areas_of_expertise'] = $conditions; break;
                case 'translator': $data['services_offered'] = $conditions; break;
            }
        }

        if (!empty($data)) {
            $profile->update($data);
        }

        $label = $updateType === 'specialities' ? 'Specialities' : 'Conditions';
        $items = ($updateType === 'specialities') ? ($data['consultations'] ?? $data['specialization'] ?? $data['practitioner_type'] ?? $data['yoga_therapist_type'] ?? $data['fields_of_specialization'] ?? []) : ($data['body_therapies'] ?? $data['health_conditions_treated'] ?? $data['client_concerns'] ?? $data['areas_of_expertise'] ?? $data['services_offered'] ?? []);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$label} updated successfully!",
                'items' => $items,
                'update_type' => $updateType
            ]);
        }

        return back()->with('status', "{$label} updated successfully!");
    }

    public function joinSession(Request $request, $channel, bool $isPublicMeeting = false)
    {
        $user = Auth::user();
        $isPublicMeeting = $isPublicMeeting || !$user;
        if (!$user) {
            $guestName = trim((string) $request->query('name', 'Guest'));
            $user = (object) [
                'id' => 0,
                'name' => $guestName !== '' ? $guestName : 'Guest',
                'email' => null,
                'role' => 'guest',
                'profile_pic' => null,
                'profile' => null,
                'unreadNotifications' => collect(),
            ];
        }
        $appId = preg_replace('/[^a-f0-9]/i', '', (string)config('services.agora.app_id'));
        $provider = strtolower((string) $request->query('provider', 'agora'));
        $provider = in_array($provider, ['agora', 'jaas', 'jitsi']) ? $provider : 'agora';
        $provider = $provider === 'jitsi' ? 'jaas' : $provider;
        $isMeetingPopout = $request->query('popout') === '1';
        $agoraAvailable = !empty($appId);
        $jaasDomain = rtrim((string) config('services.jaas.domain', '8x8.vc'), '/');
        $jaasAppId = trim((string) config('services.jaas.app_id'));
        $jaasRoomSlug = preg_replace('/[^A-Za-z0-9_-]+/', '-', 'zaya-' . $channel);
        $jaasRoomSlug = trim($jaasRoomSlug, '-');
        if ($jaasRoomSlug === '') {
            $jaasRoomSlug = 'zaya-meeting';
        }
        $jaasRoomName = $jaasAppId !== '' ? $jaasAppId . '/' . $jaasRoomSlug : $jaasRoomSlug;
        $jaasToken = $provider === 'jaas' ? $this->buildJaasToken($user, $jaasRoomSlug) : null;
        $jaasError = null;

        if ($provider === 'jaas' && empty($jaasAppId)) {
            $jaasError = 'JaaS App ID is missing. Set JAAS_APP_ID in your .env file.';
        } elseif ($provider === 'jaas' && empty($jaasToken)) {
            $jaasError = 'JaaS token could not be generated. Check the JaaS private key and API key ID.';
        }
        
        \Log::info("User joining session:", [
            'user' => $user->id,
            'channel' => $channel,
            'provider' => $provider,
            'appId_length' => strlen($appId),
            'appId_preview' => substr($appId, 0, 5) . '...'
        ]);

        return view('conference.session', compact(
            'user',
            'channel',
            'appId',
            'provider',
            'isMeetingPopout',
            'isPublicMeeting',
            'agoraAvailable',
            'jaasDomain',
            'jaasAppId',
            'jaasRoomSlug',
            'jaasRoomName',
            'jaasToken',
            'jaasError'
        ));
    }

    public function publicJoinSession(Request $request, $channel)
    {
        return $this->joinSession($request, $channel, true);
    }

    private function buildJaasToken($user, string $roomSlug): ?string
    {
        $jaasAppId = trim((string) config('services.jaas.app_id'));
        $kid = trim((string) config('services.jaas.kid'));
        $privateKey = trim((string) config('services.jaas.private_key'));
        $privateKeyPath = trim((string) config('services.jaas.private_key_path'));

        if ($privateKey === '' && $privateKeyPath !== '' && file_exists($privateKeyPath)) {
            $privateKey = trim((string) file_get_contents($privateKeyPath));
        }

        if ($privateKey !== '') {
            $privateKey = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $privateKey);
            $privateKey = str_replace("\r\n", "\n", $privateKey);
            $privateKey = trim($privateKey);
        }

        if ($jaasAppId === '' || $kid === '' || $privateKey === '') {
            return null;
        }

        $now = now()->getTimestamp();
        $isModerator = in_array($user->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist'], true);

        $payload = [
            'aud' => 'jitsi',
            'iss' => 'chat',
            'sub' => $jaasAppId,
                    'room' => $roomSlug,
                    'nbf' => $now - 30,
                    'exp' => $now + 7200,
                    'context' => [
                'user' => [
                    'id' => (string) $user->id,
                    'name' => (string) $user->name,
                    'email' => (string) ($user->email ?? ''),
                    'moderator' => $isModerator ? 'true' : 'false',
                ],
                'features' => [
                    'livestreaming' => 'false',
                    'outbound-call' => 'false',
                    'transcription' => 'false',
                    'recording' => 'false',
                ],
                'room' => [
                    'regex' => false,
                ],
            ],
        ];

        try {
            $key = openssl_pkey_get_private($privateKey);
            if ($key === false) {
                \Log::error('Failed to parse JaaS private key', [
                    'user_id' => $user->id ?? null,
                    'room' => $roomSlug,
                ]);
                return null;
            }

            return JWT::encode($payload, $key, 'RS256', $kid);
        } catch (\Throwable $e) {
            \Log::error('Failed to build JaaS token', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
                'room' => $roomSlug,
            ]);

            return null;
        }
    }

    public function generateToken(Request $request)
    {
        $appId = preg_replace('/[^a-f0-9]/i', '', (string)config('services.agora.app_id'));
        $appCertificate = preg_replace('/[^a-f0-9]/i', '', (string)config('services.agora.app_certificate'));
        
        $validated = $request->validate([
            'channel' => ['required', 'string', 'max:64', 'regex:/^[A-Za-z0-9_-]+$/'],
            'uid' => ['nullable', 'integer', 'min:1'],
        ]);

        $channelName = $validated['channel'];
        $uid = $validated['uid'] ?? Auth::id() ?? 1;
        
        \Log::info("Agora Token Request DEBUG:", [
            'channel' => $channelName,
            'uid' => $uid,
            'appId_length' => strlen($appId),
            'has_cert' => !empty($appCertificate)
        ]);

        if (empty($appId) || empty($appCertificate)) {
            return response()->json(['token' => null, 'error' => 'Agora credentials missing or invalid']);
        }

        $role = \App\Services\Agora\RtcTokenBuilder::ROLE_PUBLISHER;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = \App\Services\Agora\RtcTokenBuilder::buildTokenWithUid(
            $appId, 
            $appCertificate, 
            $channelName, 
            $uid, 
            $role, 
            $expireTimeInSeconds,
            $privilegeExpiredTs
        );

        \Log::info("Generated Token DEBUG: " . $token);

        return response()->json([
            'token' => $token, 
            'expire' => $privilegeExpiredTs,
            'uid' => $uid,
            'channel' => $channelName,
        ]);
    }
}
