<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Referral;
use App\Models\Conference;
use App\Models\Country;
use App\Models\ConsultationForm;
use App\Models\Service;
use App\Models\UserService;
use App\Models\Practitioner;
use App\Models\PractitionerReview;
use App\Models\PractitionerGallery;
use App\Models\PromoCode;
use App\Models\UserPromoCode;
use App\Mail\BookingMail;
use App\Traits\ImageUploadTrait;
use Carbon\Carbon;
use App\Models\Specialization;
use App\Models\Qualification;
use App\Models\ClientConsultationPreference;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use ImageUploadTrait;

    private function getBookingQuery($user, $forceClientView = false)
    {
        $role = $user->role;
        $profileId = $user->profile_id;
        $query = Booking::with(['practitioner.user', 'user', 'transactions']);

        if ($forceClientView || $role === 'client' || $role === 'patient') {
            $query->where('user_id', $user->id);
        } elseif ($role === 'translator') {
            $query->where('translator_id', $profileId);
        } else {
            // Practitioners, Doctors, Mindfulness, Yoga
            $morphClass = $user->profile ? $user->profile->getMorphClass() : $role;
            $query->where(function($q) use ($profileId, $morphClass, $user) {
                // 1. Direct bookings
                $q->where(function($sq) use ($profileId, $morphClass) {
                    $sq->where('profile_id', $profileId)
                       ->where('practitioner_type', $morphClass);
                });

                // 2. Referred bookings (only show if the referral is confirmed/paid)
                $q->orWhereHas('referralsFromThisSession', function($sq) use ($user) {
                    $sq->where('referred_to_id', $user->id)
                       ->where('status', 'paid');
                });
            });
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

        $reviews =  PractitionerReview::with('practitioner.user')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Transactions / Invoices
        $invoices = \App\Models\Transaction::with(['user', 'practitioner', 'booking', 'referral'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('practitioner_id', $user->id)
                  ->orWhere('referrer_id', $user->id);
            })
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

            $pendingReferralRequests = \App\Models\ReferralRequest::with(['requester', 'booking.user'])
                ->where('recipient_id', $user->id)
                ->where('status', 'pending')
                ->latest()
                ->get();
        }

        return view('dashboard', compact('user', 'upcomingBookings', 'completedBookings', 'reviews', 'invoices', 'allServices', 'clinicalDocuments', 'referrals', 'dataAccessRequests', 'pendingReferralRequests'));
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

        $consent = $request->consent ? 1 : 0;

        $profile->update([
            'data_sharing_consent' => $consent
        ]);

        // Bulk update individual access requests to match global preference
        \App\Models\DataAccessRequest::where('client_id', $user->id)
            ->whereIn('status', ['approved', 'revoked'])
            ->update([
                'status' => $consent ? 'approved' : 'revoked'
            ]);

        return response()->json([
            'message' => $consent ? 'All professional access enabled.' : 'All professional access revoked.',
            'consent' => $profile->data_sharing_consent
        ]);
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

    public function uploadConferenceRecording(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'provider' => 'required|string|max:50',
            'room_name' => 'nullable|string|max:255',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'recording' => 'required|file|mimetypes:video/webm,video/mp4,video/x-matroska|max:512000',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);
        $start = !empty($validated['start_time']) ? Carbon::parse($validated['start_time']) : now();
        $end = !empty($validated['end_time']) ? Carbon::parse($validated['end_time']) : now();
        $duration = max($start->diffInMinutes($end), 0);

        $recordingPath = $request->file('recording')->store('conference-recordings/' . now()->format('Y/m'), 'public');
        $recordingUrl = Storage::disk('public')->url($recordingPath);

        $conference = Conference::where('booking_id', $booking->id)
            ->where('provider', $validated['provider'])
            ->when(!empty($validated['room_name']), function ($query) use ($validated) {
                $query->where('room_name', $validated['room_name']);
            })
            ->latest('id')
            ->first();

        if ($conference) {
            $conference->update([
                'start_time' => $conference->start_time ?? $start,
                'end_time' => $end,
                'duration_minutes' => $duration,
                'recording_url' => $recordingUrl,
                'metadata' => array_merge($conference->metadata ?? [], [
                    'recording_disk_path' => $recordingPath,
                    'recording_uploaded_at' => now()->toIso8601String(),
                ]),
            ]);
        } else {
            $conference = Conference::create([
                'booking_id' => $booking->id,
                'start_time' => $start,
                'end_time' => $end,
                'duration_minutes' => $duration,
                'provider' => $validated['provider'],
                'room_name' => $validated['room_name'] ?? null,
                'recording_url' => $recordingUrl,
                'metadata' => [
                    'recording_disk_path' => $recordingPath,
                    'recording_uploaded_at' => now()->toIso8601String(),
                ],
            ]);
        }

        $booking->update(['recording_url' => $recordingUrl]);

        return response()->json([
            'success' => true,
            'recording_url' => $recordingUrl,
            'conference_id' => $conference->id,
        ]);
    }

    public function showConsultationForm(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::findOrFail($id);
        $booking->load(['practitioner.user', 'user', 'translator.user', 'consultationForms.doctor', 'referralRequests.requester']);
        
        $isPractitioner = ($booking->practitioner && $booking->practitioner->user_id === $user->id);
        $isTranslator = ($booking->translator && $booking->translator->user_id === $user->id);
        $isClient = ($booking->user_id === $user->id);
        
        // Referral access
        $isReferrer = Referral::where('booking_id', $booking->id)->where('referred_by_id', $user->id)->exists();
        $isReferredTo = Referral::where('referral_no', $booking->invoice_no)->where('referred_to_id', $user->id)->exists();

        // Check for OTP-verified data access
        $hasOTPAccess = \App\Http\Controllers\DataAccessController::hasAccess($user->id, $booking->user_id, $booking->id);

        $canEdit = ($isPractitioner || $isTranslator || $isReferredTo) && $hasOTPAccess && $request->query('view') != '1';
        
        // Referred experts and practitioners now require additional OTP verification
        $canView = $isClient || $isReferrer || $hasOTPAccess || $request->query('view') == '1';

        if (!$canView) {
            if ($isPractitioner || $isTranslator || $isReferredTo) {
                return view('consultation-form-locked', compact('user', 'booking'));
            }
            abort(403, 'You do not have permission to access this consultation form.');
        }

        $isMinimal = $request->query('minimal') === '1';

        // Determine which schema to use for the form fields
        // Default to the practitioner's role or the current user's professional role
        $roleForSchema = null;
        if ($isPractitioner) {
            $roleForSchema = $user->role;
        } elseif ($isReferredTo) {
            $roleForSchema = $user->role;
        } else {
            // Fallback to the booking's actual practitioner role
            if ($booking->practitioner && $booking->practitioner->user) {
                $roleForSchema = $booking->practitioner->user->role;
            } else {
                $roleForSchema = 'practitioner';
            }
        }

        $consultationSchema = [];
        $schemaPath = resource_path('schemas/consultation/' . $roleForSchema . '.json');
        if (file_exists($schemaPath)) {
            $decoded = json_decode((string) file_get_contents($schemaPath), true);
            if (is_array($decoded)) {
                $consultationSchema = $decoded;
            }
        }

        $formId = $request->query('form_id');
        $isNew = $request->query('new');
        $allForms = $booking->consultationForms()->latest()->get();
        $referralRequests = $booking->referralRequests()->latest()->get();
        
        $existingForm = null;
        if ($formId) {
            $existingForm = $booking->consultationForms()->find($formId);
        }
        
        if (!$existingForm && !$isNew) {
            $existingForm = $allForms->first();
        }

        $consultationPayload = $existingForm->payload ?? [];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'form_id' => $existingForm->id ?? null,
                'payload' => $consultationPayload,
            ]);
        }

        return view('consultation-form', compact(
            'user', 'booking', 'isPractitioner', 'isTranslator', 'isClient', 'isReferrer', 'isReferredTo',
            'canEdit', 'isMinimal', 'consultationSchema', 'allForms', 'existingForm', 'isNew', 
            'referralRequests', 'consultationPayload', 'roleForSchema'
        ));
    }

    public function storeConsultationForm(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::with(['practitioner.user', 'user', 'translator.user'])->findOrFail($id);

        $isPractitioner = ($booking->practitioner && $booking->practitioner->user_id === $user->id);
        $isTranslator = ($booking->translator && $booking->translator->user_id === $user->id);
        
        if (!$isPractitioner && !$isTranslator) {
            abort(403, 'You do not have permission to update this consultation record.');
        }

        $formId = $request->input('form_id');
        $title = $request->input('form_title');
        $payload = Arr::except($request->all(), ['_token', '_method', 'form_id', 'form_title']);

        if ($formId) {
            $form = ConsultationForm::where('booking_id', $booking->id)->findOrFail($formId);
            $form->update([
                'payload' => $payload,
                'title' => $title ?: $form->title,
            ]);
            $msg = 'Consultation form updated successfully.';
        } else {
            $form = ConsultationForm::create([
                'booking_id' => $booking->id,
                'doctor_id' => $user->id,
                'title' => $title ?: 'Prescription #' . (ConsultationForm::where('booking_id', $booking->id)->count() + 1),
                'payload' => $payload,
            ]);
            $msg = 'New prescription saved successfully.';
        }

        $params = ['id' => $booking->id, 'form_id' => $form->id];
        if ($request->input('minimal') === '1') {
            $params['minimal'] = '1';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'form_id' => $form->id,
                'params' => $params
            ]);
        }

        return redirect()
            ->route('bookings.consultation-form.show', $params)
            ->with('status', $msg);
    }

    public function rescheduleBooking(Request $request, $id)
    {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|string',
        ]);

        $user = Auth::user();
        $booking = Booking::with(['practitioner.user'])->findOrFail($id);

        // Authorization: Only the practitioner assigned to the booking can reschedule
        $isPractitioner = ($booking->practitioner && $booking->practitioner->user_id === $user->id);
        
        if (!$isPractitioner) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Only assigned professionals can reschedule.'], 403);
        }

        // Save original datetime if it's the first reschedule
        if (!$booking->original_booking_date) {
            $booking->original_booking_date = $booking->booking_date;
            $booking->original_booking_time = $booking->booking_time;
        }

        // Update with new values
        $booking->booking_date = $request->booking_date;
        $booking->booking_time = $request->booking_time;
        $booking->rescheduled_at = now();
        $booking->rescheduled_by = $user->role;
        $booking->save();

        return response()->json([
            'success' => true, 
            'message' => 'Consultation rescheduled successfully.',
            'new_date' => $booking->booking_date->format('M d, Y'),
            'new_time' => $booking->booking_time
        ]);
    }

    public function deleteConsultationForm($id, $form_id)
    {
        $user = Auth::user();
        $booking = Booking::findOrFail($id);

        $isPractitioner = ($booking->practitioner && $booking->practitioner->user_id === $user->id);
        
        if (!$isPractitioner) {
            abort(403, 'You do not have permission to delete this consultation record.');
        }

        $form = ConsultationForm::where('booking_id', $booking->id)->findOrFail($form_id);
        $form->delete();

        return back()->with('status', 'Consultation record deleted successfully.');
    }

    public function transactions(Request $request)
    {
        $user = Auth::user();
        
        // Fetch from new Transaction model instead of just Bookings
        $query = \App\Models\Transaction::with(['user', 'practitioner', 'booking', 'referral'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('practitioner_id', $user->id)
                  ->orWhere('referrer_id', $user->id);
            });

        $transactions = $query->latest()->paginate(15);

        // Calculate balance for the summary card
        $earned = \App\Models\Transaction::where('practitioner_id', $user->id)->sum('practitioner_share');
        $referralEarned = \App\Models\Transaction::where('referrer_id', $user->id)->sum('referrer_share');
        $totalBalance = $earned + $referralEarned;

        if ($request->ajax()) {
            return view('partials.transactions-table', compact('user', 'transactions'))->render();
        }

        return view('transactions', compact('user', 'transactions', 'totalBalance'));
    }

    public function promoCodes()
    {
        $user = Auth::user();

        // 1. Get global active promo codes
        $globalPromoCodes = PromoCode::where('status', true)
            ->where(function($q) {
                $q->where('expiry_date', '>=', now()->toDateString())
                  ->orWhereNull('expiry_date');
            })
            ->get();

        // 2. Get codes specifically added by/for this user
        $userLinkedCodes = UserPromoCode::where('user_id', $user->id)->pluck('promo_code')->toArray();
        $specificPromoCodes = PromoCode::whereIn('code', $userLinkedCodes)
            ->where('status', true)
            ->where(function($q) {
                $q->where('expiry_date', '>=', now()->toDateString())
                  ->orWhereNull('expiry_date');
            })
            ->get();

        // 3. Merge and unique
        $activePromoCodes = $globalPromoCodes->concat($specificPromoCodes)->unique('code');

        $usedPromoCodes = Booking::where('user_id', $user->id)
            ->whereNotNull('promo_code')
            ->pluck('promo_code')
            ->toArray();

        // Ensure user has a referral token
        if (!$user->referral_token) {
            $user->generateReferralToken();
        }

        // Get referred users
        $referrals = $user->referrals()->with('patient')->latest()->get();

        // Get coin settings for the user's currency
        $coinSetting = \App\Models\CoinSetting::where('currency_code', $user->currency)->where('status', true)->first();

        if (request()->ajax()) {
            return view('partials.promo-codes-grid', compact('activePromoCodes', 'usedPromoCodes', 'user', 'coinSetting', 'referrals'))->render();
        }

        return view('client.rewards', compact('activePromoCodes', 'usedPromoCodes', 'user', 'coinSetting', 'referrals'));
    }

    public function regenerateReferralToken()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->generateReferralToken();

        return back()->with('success', 'Your referral link has been regenerated. Old links will no longer work.');
    }
    public function healthJourney()
    {
        $user = Auth::user();
        $user->load('patient');
        $clinicalDocuments = $user->clinicalDocuments()->latest()->get();
        
        // Only show bookings that have a consultation form attached
        $consultations = Booking::with(['practitioner.user', 'consultationForms'])
            ->where('user_id', $user->id)
            ->whereHas('consultationForms')
            ->latest()
            ->get();

        $allServiceIds = $consultations->pluck('service_ids')->flatten()->unique()->filter()->toArray();
        $allServices = \App\Models\Service::whereIn('id', $allServiceIds)->get()->keyBy('id');

        $dataAccessRequests = \App\Models\DataAccessRequest::with('requester')
            ->where('client_id', $user->id)
            ->whereIn('status', ['approved', 'revoked'])
            ->latest()
            ->get()
            ->unique('requester_id');

        $prescriptions = \App\Models\Prescription::where('user_id', $user->id)
            ->with(['practitioner.user', 'booking'])
            ->latest()
            ->get();

        return view('health-journey', compact('user', 'clinicalDocuments', 'consultations', 'dataAccessRequests', 'allServices', 'prescriptions'));
    }

    public function bookings(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $query = $this->getBookingQuery($user);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($sub) use ($search) {
                      $sub->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('practitioner.user', function($sub) use ($search) {
                      $sub->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $bookings = $query->latest()
            ->paginate(15)
            ->withQueryString();

        // Fetch all services for these bookings to avoid N+1 in the table
        $allServiceIds = collect($bookings->items())->pluck('service_ids')->flatten()->unique()->filter()->toArray();
        $allServices = \App\Models\Service::whereIn('id', $allServiceIds)->get()->keyBy('id');

        if ($request->ajax()) {
            return view('partials.bookings-table', compact('user', 'bookings', 'allServices'))->render();
        }

        $languages = \App\Models\Language::all();
        return view('bookings', compact('user', 'bookings', 'search', 'allServices', 'languages'));
    }

    public function conferences(Request $request)
    {
        $user = Auth::user();
        $conferenceBookingIds = $this->getBookingQuery($user)
            ->where('mode', 'online')
            ->select('bookings.id');

        $conferences = Conference::with(['booking.practitioner.user', 'booking.user'])
            ->whereIn('booking_id', $conferenceBookingIds)
            ->latest('start_time')
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
            $hasAccess = \App\Http\Controllers\DataAccessController::hasAccess($user->id, $booking->user_id, $booking->id);
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
            ->with(['language', 'translator.user', 'practitioner.user'])
            ->findOrFail($id);

        $serviceIds = is_array($booking->service_ids) ? $booking->service_ids : [];
        $services = Service::whereIn('id', $serviceIds)->get();

        // Services specifically referred to this practitioner (if any)
        $referredServiceIds = [];
        if ($user->id !== ($booking->practitioner->user_id ?? null)) {
            $incomingReferral = \App\Models\Referral::where('booking_id', $booking->id)
                ->where('referred_to_id', $user->id)
                ->first();
            
            if ($incomingReferral && is_array($incomingReferral->service_ids)) {
                $referredServiceIds = $incomingReferral->service_ids;
            }
        }

        return view('partials.booking-details-modal-content', compact('user', 'booking', 'services', 'referredServiceIds'))->render();
    }

    public function showDetailsView($id)
    {
        $user = Auth::user();
        $booking = $this->getBookingQuery($user)
            ->with(['user', 'practitioner.user', 'language', 'translator.user', 'referral.referredBy', 'referralsFromThisSession.referredTo', 'transactions', 'consultationForms', 'prescriptions.practitioner.user'])
            ->findOrFail($id);

        // Permissions check for sensitive data
        $isDirectParticipant = ($booking->user_id === $user->id) || 
                         ($booking->practitioner && $booking->practitioner->user_id === $user->id) ||
                         ($booking->translator && $booking->translator->user_id === $user->id);

        // Consent Status (Checked against the CURRENT viewer)
        $hasConsent = \App\Http\Controllers\DataAccessController::hasAccess($user->id, $booking->user_id, $booking->id);

        $serviceIds = is_array($booking->service_ids) ? $booking->service_ids : [];
        $services = Service::whereIn('id', $serviceIds)->get();

        // Build referral history chain
        $referralChain = [];
        
        // 1. Ancestors (Who referred to this session?)
        $current = $booking;
        while ($current && $current->referral) {
            $parentReferral = $current->referral;
            $parentBooking = Booking::with('practitioner.user')->find($parentReferral->booking_id);
            if ($parentBooking) {
                array_unshift($referralChain, [
                    'type' => 'parent',
                    'booking' => $parentBooking,
                    'practitioner' => $parentBooking->practitioner->user->name ?? 'Unknown',
                    'referred_to' => $current->practitioner->user->name ?? 'Unknown',
                    'date' => $parentReferral->created_at
                ]);
                $current = $parentBooking;
            } else {
                break;
            }
        }

        // 2. This session (Current node in the chain)
        $referralChain[] = [
            'type' => 'current',
            'booking' => $booking,
            'practitioner' => $booking->practitioner->user->name ?? 'Unknown',
            'is_active' => true
        ];

        // 3. Descendants (Who did this session refer to?)
        foreach ($booking->referralsFromThisSession as $childRef) {
            $childBooking = Booking::with('practitioner.user')->where('invoice_no', $childRef->referral_no)->first();
            $referralChain[] = [
                'type' => 'child',
                'referral' => $childRef,
                'booking' => $childBooking,
                'practitioner' => $childRef->referredTo->name ?? 'Unknown',
                'status' => $childRef->status,
                'date' => $childRef->created_at
            ];
        }

        $firstPractitioner = $referralChain[0]['practitioner'] ?? 'Unknown';

        // Financial visibility logic
        $userTransaction = $booking->transactions->first(function($t) use ($user) {
            return $t->practitioner_id === $user->id || $t->referrer_id === $user->id;
        });

        $shareAmount = 0;
        if ($userTransaction) {
            if ($userTransaction->practitioner_id === $user->id) {
                $shareAmount = $userTransaction->practitioner_share;
            } elseif ($userTransaction->referrer_id === $user->id) {
                $shareAmount = $userTransaction->referrer_share;
            }
        }

        // Services specifically referred to this practitioner/doctor (if any)
        $referredServiceIds = [];
        if ($user->id !== ($booking->practitioner->user_id ?? null)) {
            // Check if user is a referred expert for this booking
            $incomingReferral = \App\Models\Referral::where('booking_id', $booking->id)
                ->where('referred_to_id', $user->id)
                ->first();
            
            if ($incomingReferral && is_array($incomingReferral->service_ids)) {
                $referredServiceIds = $incomingReferral->service_ids;
            }
        }

        $languages = \App\Models\Language::all();
        return view('bookings.details', compact('user', 'booking', 'services', 'referralChain', 'hasConsent', 'firstPractitioner', 'userTransaction', 'shareAmount', 'referredServiceIds', 'isDirectParticipant', 'languages'));
    }

    public function viewClientProfile($id)
    {
        $practitioner = Auth::user();
        
        // Strictly restrict to Expert roles (Admins cannot view clinical data)
        $allowedExpertRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist'];
        if (!in_array($practitioner->role, $allowedExpertRoles)) {
            return redirect()->route('dashboard')->with('error', 'Access denied. Only treating experts can view clinical data.');
        }

        // Check for OTP-verified access (Strictly for practitioners only)
        $hasAccess = \App\Http\Controllers\DataAccessController::hasAccess($practitioner->id, $id);

        if (!$hasAccess) {
            return redirect()->route('bookings.index')->with('error', 'You do not have permission to view this client\'s profile. Please verify via OTP first.');
        }
        $client = \App\Models\User::with(['patient'])->findOrFail($id);
        
        // Get all clinical documents for this client
        $documents = \App\Models\ClinicalDocument::where('user_id', $client->id)
            ->latest()
            ->get();

        // Get consultation forms for this client
        $consultationForms = \App\Models\ConsultationForm::whereHas('booking', function($query) use ($client) {
            $query->where('user_id', $client->id);
        })->with(['booking'])->latest()->get();

        // Get prescriptions for this client
        $prescriptions = \App\Models\Prescription::where('user_id', $client->id)
            ->with(['booking', 'practitioner.user'])
            ->latest()
            ->get();

        // Get client concerns (from their bookings)
        $concerns = Booking::where('user_id', $client->id)
            ->whereNotNull('conditions')
            ->latest()
            ->get()
            ->map(function($b) {
                return (object)[
                    'category' => 'Session Concern',
                    'concern' => is_array($b->conditions) ? implode(', ', $b->conditions) : $b->conditions,
                    'created_at' => $b->created_at
                ];
            });
        
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

        return view('practitioner.client-profile', compact('user', 'client', 'recordings', 'bookings', 'documents', 'consultationForms', 'concerns', 'prescriptions'));
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
        $profile = $user->profile;
        if (!$profile) {
            return view('client.my-services', compact('user', 'myServices', 'defaultCurrency'))->with('error', 'Profile not found.');
        }
        $reminderLeadTime = $profile->reminder_lead_time ?? 60;

        $nextOnlineBooking = Booking::with('transactions')
            ->where('profile_id', $user->profile_id)
            ->where('practitioner_type', $profile->getMorphClass())
            ->where('mode', 'online')
            ->where('status', 'confirmed')
            ->whereDate('booking_date', '>=', now()->toDateString())
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->first();

        $reminderLogs = collect();
        if ($nextOnlineBooking) {
            $reminderLogs = \App\Models\EmailLog::where('booking_id', $nextOnlineBooking->id)
                ->where('subject', 'LIKE', '%Session Reminder%')
                ->latest()
                ->get();
        }

        return view('client.my-services', compact('user', 'myServices', 'defaultCurrency', 'reminderLeadTime', 'nextOnlineBooking', 'reminderLogs'));
    }

    public function updateReminderSettings(Request $request)
    {
        $request->validate([
            'reminder_lead_time' => 'required|integer|min:5|max:1440',
        ]);

        $user = Auth::user();
        $profile = $user->practitioner ?? $user->doctor ?? $user->mindfulnessPractitioner ?? $user->yogaTherapist ?? null;

        if (!$profile) {
            return back()->with('error', 'Profile not found.');
        }

        $profile->update([
            'reminder_lead_time' => $request->reminder_lead_time
        ]);

        return back()->with('status', "Reminder timing updated to {$request->reminder_lead_time} minutes before the session.");
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

    public function getBookingDetails($id)
    {
        $booking = Booking::with(['language', 'practitioner', 'user.patient'])->findOrFail($id);
        
        $fromLang = $booking->from_language;
        $toLang = $booking->to_language;

        // Fallback for Source Language
        if (!$fromLang) {
            $patient = $booking->user->patient ?? null;
            if ($patient && !empty($patient->languages_spoken) && is_array($patient->languages_spoken)) {
                $fromLang = $patient->languages_spoken[0];
            } else {
                $fromLang = $booking->language ? $booking->language->display_name : 'English';
            }
        }

        // Fallback for Target Language
        if (!$toLang || strtolower($toLang) === 'any') {
            if ($booking->language) {
                $toLang = $booking->language->display_name;
            } else {
                $practitioner = $booking->practitioner;
                if ($practitioner && !empty($practitioner->languages_spoken) && is_array($practitioner->languages_spoken)) {
                    $toLang = $practitioner->languages_spoken[0];
                }
            }
        }

        if (!$toLang) $toLang = 'Any';

        return response()->json([
            'id' => $booking->id,
            'from_language' => $fromLang,
            'to_language' => $toLang,
            'need_translator' => $booking->need_translator
        ]);
    }

    public function fetchAvailableTranslators(Request $request)
    {
        $query = $request->query('query');
        $fromLang = $request->query('from_lang');
        $toLang = $request->query('to_lang');

        $ignoreLanguages = $request->query('ignore_languages') === 'true';

        $translatorsQuery = \App\Models\Translator::with('user')
            ->where('status', 'active');

        if ($query) {
            $translatorsQuery->where('full_name', 'LIKE', "%{$query}%");
        }

        if (!$ignoreLanguages) {
            if ($fromLang && $toLang && strtolower($toLang) !== 'any') {
                $translatorsQuery->where(function ($q) use ($fromLang, $toLang) {
                    $baseFrom = explode(' (', $fromLang)[0];
                    $baseTo = explode(' (', $toLang)[0];

                    // Match translators who support both languages in any capacity
                    $q->where(function ($sub) use ($fromLang, $baseFrom) {
                        $sub->whereJsonContains('source_languages', $fromLang)
                            ->orWhereJsonContains('target_languages', $fromLang)
                            ->orWhereJsonContains('additional_languages', $fromLang)
                            ->orWhere('native_language', 'LIKE', $fromLang)
                            ->orWhereJsonContains('source_languages', $baseFrom)
                            ->orWhereJsonContains('target_languages', $baseFrom)
                            ->orWhereJsonContains('additional_languages', $baseFrom)
                            ->orWhere('native_language', 'LIKE', $baseFrom)
                            // Also match any variant of the base language
                            ->orWhere('source_languages', 'LIKE', '%"' . $baseFrom . ' (%"')
                            ->orWhere('target_languages', 'LIKE', '%"' . $baseFrom . ' (%"')
                            ->orWhere('additional_languages', 'LIKE', '%"' . $baseFrom . ' (%"')
                            ->orWhere('native_language', 'LIKE', $baseFrom . ' (%');
                    })->where(function ($sub) use ($toLang, $baseTo) {
                        $sub->whereJsonContains('source_languages', $toLang)
                            ->orWhereJsonContains('target_languages', $toLang)
                            ->orWhereJsonContains('additional_languages', $toLang)
                            ->orWhere('native_language', 'LIKE', $toLang)
                            ->orWhereJsonContains('source_languages', $baseTo)
                            ->orWhereJsonContains('target_languages', $baseTo)
                            ->orWhereJsonContains('additional_languages', $baseTo)
                            ->orWhere('native_language', 'LIKE', $baseTo)
                            // Also match any variant of the base language
                            ->orWhere('source_languages', 'LIKE', '%"' . $baseTo . ' (%"')
                            ->orWhere('target_languages', 'LIKE', '%"' . $baseTo . ' (%"')
                            ->orWhere('additional_languages', 'LIKE', '%"' . $baseTo . ' (%"')
                            ->orWhere('native_language', 'LIKE', $baseTo . ' (%');
                    });
                });
            } elseif ($fromLang) {
                // Only match fromLang
                $translatorsQuery->where(function ($q) use ($fromLang) {
                    $baseFrom = explode(' (', $fromLang)[0];
                    $q->whereJsonContains('source_languages', $fromLang)
                      ->orWhereJsonContains('target_languages', $fromLang)
                      ->orWhereJsonContains('additional_languages', $fromLang)
                      ->orWhere('native_language', 'LIKE', $fromLang)
                      ->orWhereJsonContains('source_languages', $baseFrom)
                      ->orWhereJsonContains('target_languages', $baseFrom)
                      ->orWhereJsonContains('additional_languages', $baseFrom)
                      ->orWhere('native_language', 'LIKE', $baseFrom)
                      // Also match any variant of the base language
                      ->orWhere('source_languages', 'LIKE', '%"' . $baseFrom . ' (%"')
                      ->orWhere('target_languages', 'LIKE', '%"' . $baseFrom . ' (%"')
                      ->orWhere('additional_languages', 'LIKE', '%"' . $baseFrom . ' (%"')
                      ->orWhere('native_language', 'LIKE', $baseFrom . ' (%');
                });
            }
        }

        $translators = $translatorsQuery->get()->map(function ($t) {
            return [
                'id' => $t->id,
                'full_name' => $t->full_name,
                'native_language' => $t->native_language,
                'years_of_experience' => $t->years_of_experience,
                'profile_photo_path' => $t->user->profile_pic 
                    ? (str_starts_with($t->user->profile_pic, 'http') ? $t->user->profile_pic : asset('storage/' . $t->user->profile_pic))
                    : asset('frontend/assets/profile-dummy-img.png'),
            ];
        });

        return response()->json($translators);
    }

    public function assignTranslator(Request $request, $id)
    {
        $request->validate([
            'translator_id' => 'required|exists:translators,id',
            'from_language' => 'nullable|string',
            'to_language' => 'nullable|string',
        ]);

        $user = Auth::user();
        $profile = $user->profile;
        $booking = Booking::findOrFail($id);

        // Security check: Only the practitioner assigned to the booking can assign a translator
        if (!$profile || ($booking->profile_id !== $profile->id || $booking->practitioner_type !== $profile->getMorphClass())) {
            if (!in_array($user->role, ['admin', 'super-admin'])) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
        }

        $updateData = [
            'translator_id' => $request->translator_id,
            'need_translator' => true,
            'status' => 'confirmed'
        ];

        if ($request->filled('from_language')) {
            $updateData['from_language'] = $request->from_language;
        }
        if ($request->filled('to_language')) {
            $updateData['to_language'] = $request->to_language;
        }

        $booking->update($updateData);

        // Reload booking with translator info to send mail
        $booking->load('translator.user');
        
        if ($booking->translator && $booking->translator->user) {
            try {
                Mail::to($booking->translator->user->email)->send(new BookingMail($booking, 'translator'));
            } catch (\Exception $e) {
                \Log::error('Failed to send translator assignment email: ' . $e->getMessage());
            }
        }

        return response()->json(['success' => 'Translator assigned successfully!']);
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
        $profile = $user->practitioner ?? $user->doctor ?? $user->mindfulnessPractitioner ?? $user->yogaTherapist ?? $user->translator ?? null;
        
        if ($profile && !empty($profile->payout_currency)) {
            return strtoupper(trim($profile->payout_currency));
        }

        $country = $user->country ?? ($profile ? $profile->country : null);
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Handle profile pic removal
        if ($request->input('remove')) {
            if ($user->profile_pic && !str_starts_with($user->profile_pic, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_pic);
            }
            $user->profile_pic = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture removed successfully!',
                'path' => asset('frontend/assets/profile-dummy-img.png')
            ]);
        }

        $request->validate([
            'cropped_image' => 'required|string',
        ]);

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
            'mobile_country_code' => 'nullable|string|max:10',
            'gender' => 'nullable|in:male,female,transgender,other',
            'dob' => 'nullable|date|before:today',
            'nationality' => 'nullable|string|max:255|exists:countries,name',
            'address_line_1' => 'nullable|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
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
        foreach (['phone', 'mobile_country_code', 'gender', 'dob', 'nationality', 'address_line_1', 'address_line_2', 'city', 'state', 'zip_code', 'country'] as $field) {
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
        $totalSessions = (clone $bookingQuery)->where('status', '!=', 'cancelled')->count();
        $totalClients = (clone $bookingQuery)->where('status', '!=', 'cancelled')->distinct('user_id')->count();
        $todaySessions = (clone $bookingQuery)->where('booking_date', now()->toDateString())->count();
        $upcomingSessions = (clone $bookingQuery)->where('booking_date', '>', now()->toDateString())->count();

        // History
        $servicesHistory = (clone $bookingQuery)
            ->with('user')
            ->where(function($q) {
                $q->where('status', 'completed')
                  ->orWhere('booking_date', '<', now()->toDateString());
            })
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
        $allModalities = [];

        switch ($user->role) {
            case 'practitioner':
                $allSpecialities = \App\Models\Specialization::where('status', true)->pluck('name');
                $allConditions = \App\Models\HealthCondition::where('status', true)->pluck('name');
                $allModalities = \App\Models\PractitionerModality::where('status', true)->pluck('name');
                break;
            case 'doctor':
                $allSpecialities = \App\Models\Specialization::where('status', true)->pluck('name');
                $allConditions = \App\Models\HealthCondition::where('status', true)->pluck('name');
                $allModalities = \App\Models\PractitionerModality::where('status', true)->pluck('name');
                break;
            case 'mindfulness_practitioner':
                $allSpecialities = \App\Models\MindfulnessService::where('status', true)->pluck('name');
                $allConditions = \App\Models\ClientConcern::where('status', true)->pluck('name');
                $allModalities = \App\Models\PractitionerModality::where('status', true)->pluck('name');
                break;
            case 'yoga_therapist':
                // Yoga therapist specialities are currently free text or generic
                $allConditions = \App\Models\YogaExpertise::pluck('name');
                $allModalities = \App\Models\PractitionerModality::where('status', true)->pluck('name');
                break;
            case 'translator':
                $allSpecialities = \App\Models\TranslatorSpecialization::where('status', true)->pluck('name');
                $allConditions = \App\Models\TranslatorService::where('status', true)->pluck('name');
                $allModalities = \App\Models\PractitionerModality::where('status', true)->pluck('name');
                break;
        }

        $countries = Country::query()
            ->where('status', 'active')
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
            'allConditions',
            'allModalities'
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
                case 'practitioner': $data['specialization'] = $specialities; break;
                case 'doctor': $data['specialization'] = $specialities; break;
                case 'mindfulness_practitioner': $data['practitioner_type'] = $specialities; break;
                case 'yoga_therapist': $data['yoga_therapist_type'] = $specialities; break;
                case 'translator': $data['fields_of_specialization'] = $specialities; break;
            }
        } elseif ($updateType === 'conditions') {
            $conditions = $request->input('conditions', []);
            switch ($user->role) {
                case 'practitioner': $data['health_conditions_treated'] = $conditions; break;
                case 'doctor': $data['health_conditions_treated'] = $conditions; break;
                case 'mindfulness_practitioner': $data['client_concerns'] = $conditions; break;
                case 'yoga_therapist': $data['areas_of_expertise'] = $conditions; break;
                case 'translator': $data['services_offered'] = $conditions; break;
            }
        } elseif ($updateType === 'modalities') {
            $modalities = $request->input('modalities', []);
            $data['other_modalities'] = $modalities;
        }

        if (!empty($data)) {
            $profile->update($data);
        }

        $labelMap = [
            'specialities' => 'Specialities',
            'conditions' => 'Conditions',
            'modalities' => 'Modalities'
        ];
        $label = $labelMap[$updateType] ?? 'Details';

        $items = match($updateType) {
            'specialities' => ($data['consultations'] ?? $data['specialization'] ?? $data['practitioner_type'] ?? $data['yoga_therapist_type'] ?? $data['fields_of_specialization'] ?? []),
            'conditions' => ($data['body_therapies'] ?? $data['health_conditions_treated'] ?? $data['client_concerns'] ?? $data['areas_of_expertise'] ?? $data['services_offered'] ?? []),
            'modalities' => ($data['other_modalities'] ?? []),
            default => []
        };

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
        $provider = strtolower((string) $request->query('provider', 'choose'));
        $provider = in_array($provider, ['agora', 'jaas', 'jitsi', 'daily', 'zegocloud', 'google_meet', 'choose']) ? $provider : 'choose';

        if ($provider === 'zegocloud') {
            return redirect()->route('zego.join', ['channel' => $channel]);
        }
        
        $isMeetingPopout = $request->query('popout') === '1';
        $agoraAvailable = !empty($appId);

        // JaaS (8x8) config
        $jaasDomain = rtrim((string) config('services.jaas.domain', '8x8.vc'), '/');
        $jaasAppId = trim((string) config('services.jaas.app_id'));
        $jaasRoomSlug = preg_replace('/[^A-Za-z0-9_-]+/', '-', (string)$channel);
        $jaasRoomSlug = trim($jaasRoomSlug, '-');
        if ($jaasRoomSlug === '') {
            $jaasRoomSlug = 'zaya-meeting';
        }
        $jaasRoomName = $jaasAppId !== '' ? $jaasAppId . '/' . $jaasRoomSlug : $jaasRoomSlug;
        $jaasToken = ($provider === 'jaas' || $provider === 'choose') ? $this->buildJaasToken($user, $jaasRoomSlug) : null;
        $jaasError = null;

        // Free Jitsi config
        $jitsiDomain = rtrim((string) config('services.jitsi.domain', 'meet.jit.si'), '/');
        $jitsiRoom = $jaasRoomSlug;

        if ($provider === 'jaas' && empty($jaasAppId)) {
            $jaasError = 'JaaS App ID is missing. Set JAAS_APP_ID in your .env file.';
        } elseif ($provider === 'jaas' && empty($jaasToken)) {
            $jaasError = 'JaaS token could not be generated. Check the JaaS private key and API key ID.';
        }

        // Daily.co config
        $dailyDomain = rtrim((string) config('services.daily.domain', 'zaya.daily.co'), '/');
        $dailyApiKey = trim((string) config('services.daily.api_key'));
        $dailyRoomName = strtolower($jaasRoomSlug); // Daily prefers lowercase
        $dailyUrl = "https://{$dailyDomain}/{$dailyRoomName}";
        $dailyToken = null;
        $dailyError = null;

        if ($provider === 'daily') {
            if (empty($dailyApiKey)) {
                $dailyError = 'Daily.co API key is missing. Set DAILY_API_KEY in your .env file.';
            } else {
                $dailyError = $this->ensureDailyRoomExists($dailyRoomName);
                if ($dailyError === null) {
                    $dailyToken = $this->buildDailyToken($user, $dailyRoomName);
                }
            }
        }

        // Try to find the booking for session tracking
        // 1. Try direct ID (old method)
        // 2. Try invoice_no (current method)
        $booking = \App\Models\Booking::with(['user', 'practitioner.user'])
            ->where(function($q) use ($channel) {
                $q->where('invoice_no', $channel)
                  ->orWhere('id', (int)str_replace('session-', '', $channel));
            })->first();

        $isInstant = !$booking && str_starts_with((string)$channel, 'zaya-');

        // For instant meetings, we fetch the latest booking to "demo" the consultation form as requested
        if ($isInstant && $user->id > 0) {
            $booking = \App\Models\Booking::with(['user', 'practitioner.user'])
                ->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhereHas('practitioner', fn($p) => $p->where('user_id', $user->id));
                })
                ->latest()
                ->first();
        }
        
        \Log::info("User joining session:", [
            'user' => $user->id,
            'channel' => $channel,
            'provider' => $provider,
            'isInstant' => $isInstant,
            'appId_length' => strlen($appId)
        ]);

        if ($provider === 'daily') {
            return view('conference.daily', compact(
                'user', 'channel', 'dailyUrl', 'dailyToken', 'dailyError', 'booking', 'isInstant'
            ));
        }

        if ($provider === 'google_meet') {
            // For Instant meetings, we redirect to a new meeting
            // For scheduled bookings, we could later integrate with Google Calendar API 
            // to fetch a specific hangoutLink if stored.
            $googleMeetUrl = "https://meet.google.com/new";
            
            // Redirect externally
            return redirect()->away($googleMeetUrl);
        }

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
            'jaasError',
            'jitsiDomain',
            'jitsiRoom',
            'dailyUrl',
            'dailyToken',
            'dailyError',
            'booking',
            'isInstant'
        ));
    }

    private function ensureDailyRoomExists(string $roomName): ?string
    {
        $apiKey = trim((string) config('services.daily.api_key'));
        if ($apiKey === '') {
            return 'Daily.co API key is missing.';
        }

        try {
            // First, try to fetch the room to see if it exists
            $checkResponse = \Illuminate\Support\Facades\Http::withToken($apiKey)
                ->withOptions(['verify' => false])
                ->get("https://api.daily.co/v1/rooms/{$roomName}");

            if ($checkResponse->successful()) {
                return null; // Room already exists
            }

            // If it doesn't exist (404), create it
            if ($checkResponse->status() === 404) {
                $createResponse = \Illuminate\Support\Facades\Http::withToken($apiKey)
                    ->withOptions(['verify' => false])
                    ->post("https://api.daily.co/v1/rooms", [
                        'name' => $roomName,
                        'properties' => [
                            'enable_chat' => true,
                            'enable_knocking' => false,
                            'enable_screenshare' => true,
                            'exp' => now()->addHours(24)->getTimestamp(),
                        ]
                    ]);
                
                if (!$createResponse->successful()) {
                    \Log::error('Daily.co Room Creation Failed', [
                        'status' => $createResponse->status(),
                        'body' => $createResponse->body(),
                        'room' => $roomName
                    ]);
                    return 'Daily.co could not create the meeting room. Check DAILY_DOMAIN and Daily API plan settings.';
                } else {
                    \Log::info('Daily.co Room Created Successfully', ['room' => $roomName]);
                    return null;
                }
            } else if (!$checkResponse->successful()) {
                \Log::error('Daily.co Room Check Failed', [
                    'status' => $checkResponse->status(),
                    'body' => $checkResponse->body()
                ]);
                return 'Daily.co room lookup failed before joining.';
            }
        } catch (\Exception $e) {
            \Log::error('Daily.co Ensure Room Exception: ' . $e->getMessage());
            return 'Daily.co room setup failed: ' . $e->getMessage();
        }

        return null;
    }

    private function buildDailyToken($user, string $roomName): ?string
    {
        $apiKey = trim((string) config('services.daily.api_key'));
        if ($apiKey === '') return null;

        $isModerator = in_array($user->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist', 'translator'], true);

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                ->withOptions(['verify' => false])
                ->post("https://api.daily.co/v1/meeting-tokens", [
                    'properties' => [
                        'room_name' => $roomName,
                        'is_owner' => $isModerator,
                        'user_name' => (string) $user->name,
                        'user_id' => (string) $user->id,
                    ]
                ]);

            if ($response->successful()) {
                return $response->json('token');
            }
            
            \Log::error('Daily.co Token Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            \Log::error('Daily.co Token Exception: ' . $e->getMessage());
        }

        return null;
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
        $isModerator = in_array($user->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist', 'translator'], true);

        $payload = [
            'aud' => 'jitsi',
            'iss' => 'chat',
            'sub' => $jaasAppId,
                    'room' => $roomSlug,
                    'nbf' => $now - 30,
                    'exp' => $now + 7200,
                    'context' => [
                'user' => [
                    'id' => str_pad((string)$user->id, 8, '0', STR_PAD_LEFT),
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

    public function completeProfile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return redirect()->route('dashboard')->with('error', 'Profile not found.');
        }

        $countries = Country::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        $allLanguages = \App\Models\Language::where('status', 'active')->orderBy('name')->get();

        // Functional Country Codes
        $countryCodes = [
            ['code' => '+93', 'name' => 'Afghanistan'], ['code' => '+355', 'name' => 'Albania'], ['code' => '+213', 'name' => 'Algeria'],
            ['code' => '+1', 'name' => 'USA/Canada'], ['code' => '+376', 'name' => 'Andorra'], ['code' => '+244', 'name' => 'Angola'],
            ['code' => '+1264', 'name' => 'Anguilla'], ['code' => '+1268', 'name' => 'Antigua & Barbuda'], ['code' => '+54', 'name' => 'Argentina'],
            ['code' => '+374', 'name' => 'Armenia'], ['code' => '+297', 'name' => 'Aruba'], ['code' => '+61', 'name' => 'Australia'],
            ['code' => '+43', 'name' => 'Austria'], ['code' => '+994', 'name' => 'Azerbaijan'], ['code' => '+1242', 'name' => 'Bahamas'],
            ['code' => '+973', 'name' => 'Bahrain'], ['code' => '+880', 'name' => 'Bangladesh'], ['code' => '+1246', 'name' => 'Barbados'],
            ['code' => '+375', 'name' => 'Belarus'], ['code' => '+32', 'name' => 'Belgium'], ['code' => '+501', 'name' => 'Belize'],
            ['code' => '+229', 'name' => 'Benin'], ['code' => '+1441', 'name' => 'Bermuda'], ['code' => '+975', 'name' => 'Bhutan'],
            ['code' => '+591', 'name' => 'Bolivia'], ['code' => '+387', 'name' => 'Bosnia Herzegovina'], ['code' => '+267', 'name' => 'Botswana'],
            ['code' => '+55', 'name' => 'Brazil'], ['code' => '+673', 'name' => 'Brunei'], ['code' => '+359', 'name' => 'Bulgaria'],
            ['code' => '+226', 'name' => 'Burkina Faso'], ['code' => '+257', 'name' => 'Burundi'], ['code' => '+855', 'name' => 'Cambodia'],
            ['code' => '+237', 'name' => 'Cameroon'], ['code' => '+238', 'name' => 'Cape Verde Islands'], ['code' => '+1345', 'name' => 'Cayman Islands'],
            ['code' => '+236', 'name' => 'Central African Republic'], ['code' => '+56', 'name' => 'Chile'], ['code' => '+86', 'name' => 'China'],
            ['code' => '+57', 'name' => 'Colombia'], ['code' => '+269', 'name' => 'Comoros'], ['code' => '+242', 'name' => 'Congo'],
            ['code' => '+682', 'name' => 'Cook Islands'], ['code' => '+506', 'name' => 'Costa Rica'], ['code' => '+385', 'name' => 'Croatia'],
            ['code' => '+53', 'name' => 'Cuba'], ['code' => '+357', 'name' => 'Cyprus North'], ['code' => '+357', 'name' => 'Cyprus South'],
            ['code' => '+420', 'name' => 'Czech Republic'], ['code' => '+45', 'name' => 'Denmark'], ['code' => '+253', 'name' => 'Djibouti'],
            ['code' => '+1809', 'name' => 'Dominica'], ['code' => '+1809', 'name' => 'Dominican Republic'], ['code' => '+593', 'name' => 'Ecuador'],
            ['code' => '+20', 'name' => 'Egypt'], ['code' => '+503', 'name' => 'El Salvador'], ['code' => '+240', 'name' => 'Equatorial Guinea'],
            ['code' => '+291', 'name' => 'Eritrea'], ['code' => '+372', 'name' => 'Estonia'], ['code' => '+251', 'name' => 'Ethiopia'],
            ['code' => '+500', 'name' => 'Falkland Islands'], ['code' => '+298', 'name' => 'Faroe Islands'], ['code' => '+679', 'name' => 'Fiji'],
            ['code' => '+358', 'name' => 'Finland'], ['code' => '+33', 'name' => 'France'], ['code' => '+594', 'name' => 'French Guiana'],
            ['code' => '+689', 'name' => 'French Polynesia'], ['code' => '+241', 'name' => 'Gabon'], ['code' => '+220', 'name' => 'Gambia'],
            ['code' => '+995', 'name' => 'Georgia'], ['code' => '+49', 'name' => 'Germany'], ['code' => '+233', 'name' => 'Ghana'],
            ['code' => '+350', 'name' => 'Gibraltar'], ['code' => '+30', 'name' => 'Greece'], ['code' => '+299', 'name' => 'Greenland'],
            ['code' => '+1473', 'name' => 'Grenada'], ['code' => '+590', 'name' => 'Guadeloupe'], ['code' => '+671', 'name' => 'Guam'],
            ['code' => '+502', 'name' => 'Guatemala'], ['code' => '+224', 'name' => 'Guinea'], ['code' => '+245', 'name' => 'Guinea - Bissau'],
            ['code' => '+592', 'name' => 'Guyana'], ['code' => '+509', 'name' => 'Haiti'], ['code' => '+504', 'name' => 'Honduras'],
            ['code' => '+852', 'name' => 'Hong Kong'], ['code' => '+36', 'name' => 'Hungary'], ['code' => '+354', 'name' => 'Iceland'],
            ['code' => '+91', 'name' => 'India'], ['code' => '+62', 'name' => 'Indonesia'], ['code' => '+98', 'name' => 'Iran'],
            ['code' => '+964', 'name' => 'Iraq'], ['code' => '+353', 'name' => 'Ireland'], ['code' => '+972', 'name' => 'Israel'],
            ['code' => '+39', 'name' => 'Italy'], ['code' => '+1876', 'name' => 'Jamaica'], ['code' => '+81', 'name' => 'Japan'],
            ['code' => '+962', 'name' => 'Jordan'], ['code' => '+7', 'name' => 'Kazakhstan'], ['code' => '+254', 'name' => 'Kenya'],
            ['code' => '+686', 'name' => 'Kiribati'], ['code' => '+82', 'name' => 'Korea South'], ['code' => '+965', 'name' => 'Kuwait'],
            ['code' => '+996', 'name' => 'Kyrgyzstan'], ['code' => '+856', 'name' => 'Laos'], ['code' => '+371', 'name' => 'Latvia'],
            ['code' => '+961', 'name' => 'Lebanon'], ['code' => '+266', 'name' => 'Lesotho'], ['code' => '+231', 'name' => 'Liberia'],
            ['code' => '+218', 'name' => 'Libya'], ['code' => '+417', 'name' => 'Liechtenstein'], ['code' => '+370', 'name' => 'Lithuania'],
            ['code' => '+352', 'name' => 'Luxembourg'], ['code' => '+853', 'name' => 'Macao'], ['code' => '+389', 'name' => 'Macedonia'],
            ['code' => '+261', 'name' => 'Madagascar'], ['code' => '+265', 'name' => 'Malawi'], ['code' => '+60', 'name' => 'Malaysia'],
            ['code' => '+960', 'name' => 'Maldives'], ['code' => '+223', 'name' => 'Mali'], ['code' => '+356', 'name' => 'Malta'],
            ['code' => '+692', 'name' => 'Marshall Islands'], ['code' => '+596', 'name' => 'Martinique'], ['code' => '+222', 'name' => 'Mauritania'],
            ['code' => '+230', 'name' => 'Mauritius'], ['code' => '+269', 'name' => 'Mayotte'], ['code' => '+52', 'name' => 'Mexico'],
            ['code' => '+691', 'name' => 'Micronesia'], ['code' => '+373', 'name' => 'Moldova'], ['code' => '+377', 'name' => 'Monaco'],
            ['code' => '+976', 'name' => 'Mongolia'], ['code' => '+1664', 'name' => 'Montserrat'], ['code' => '+212', 'name' => 'Morocco'],
            ['code' => '+258', 'name' => 'Mozambique'], ['code' => '+95', 'name' => 'Myanmar'], ['code' => '+264', 'name' => 'Namibia'],
            ['code' => '+674', 'name' => 'Nauru'], ['code' => '+977', 'name' => 'Nepal'], ['code' => '+31', 'name' => 'Netherlands'],
            ['code' => '+687', 'name' => 'New Caledonia'], ['code' => '+64', 'name' => 'New Zealand'], ['code' => '+505', 'name' => 'Nicaragua'],
            ['code' => '+227', 'name' => 'Niger'], ['code' => '+234', 'name' => 'Nigeria'], ['code' => '+683', 'name' => 'Niue'],
            ['code' => '+672', 'name' => 'Norfolk Islands'], ['code' => '+670', 'name' => 'Northern Marianas'], ['code' => '+47', 'name' => 'Norway'],
            ['code' => '+968', 'name' => 'Oman'], ['code' => '+92', 'name' => 'Pakistan'], ['code' => '+680', 'name' => 'Palau'],
            ['code' => '+507', 'name' => 'Panama'], ['code' => '+675', 'name' => 'Papua New Guinea'], ['code' => '+595', 'name' => 'Paraguay'],
            ['code' => '+51', 'name' => 'Peru'], ['code' => '+63', 'name' => 'Philippines'], ['code' => '+48', 'name' => 'Poland'],
            ['code' => '+351', 'name' => 'Portugal'], ['code' => '+1787', 'name' => 'Puerto Rico'], ['code' => '+974', 'name' => 'Qatar'],
            ['code' => '+262', 'name' => 'Reunion'], ['code' => '+40', 'name' => 'Romania'], ['code' => '+7', 'name' => 'Russia'],
            ['code' => '+250', 'name' => 'Rwanda'], ['code' => '+378', 'name' => 'San Marino'], ['code' => '+239', 'name' => 'Sao Tome & Principe'],
            ['code' => '+966', 'name' => 'Saudi Arabia'], ['code' => '+221', 'name' => 'Senegal'], ['code' => '+381', 'name' => 'Serbia'],
            ['code' => '+248', 'name' => 'Seychelles'], ['code' => '+232', 'name' => 'Sierra Leone'], ['code' => '+65', 'name' => 'Singapore'],
            ['code' => '+421', 'name' => 'Slovak Republic'], ['code' => '+386', 'name' => 'Slovenia'], ['code' => '+677', 'name' => 'Solomon Islands'],
            ['code' => '+252', 'name' => 'Somalia'], ['code' => '+27', 'name' => 'South Africa'], ['code' => '+34', 'name' => 'Spain'],
            ['code' => '+94', 'name' => 'Sri Lanka'], ['code' => '+290', 'name' => 'St. Helena'], ['code' => '+1869', 'name' => 'St. Kitts'],
            ['code' => '+1758', 'name' => 'St. Lucia'], ['code' => '+249', 'name' => 'Sudan'], ['code' => '+597', 'name' => 'Suriname'],
            ['code' => '+268', 'name' => 'Swaziland'], ['code' => '+46', 'name' => 'Sweden'], ['code' => '+41', 'name' => 'Switzerland'],
            ['code' => '+963', 'name' => 'Syria'], ['code' => '+886', 'name' => 'Taiwan'], ['code' => '+7', 'name' => 'Tajikstan'],
            ['code' => '+66', 'name' => 'Thailand'], ['code' => '+228', 'name' => 'Togo'], ['code' => '+676', 'name' => 'Tonga'],
            ['code' => '+1868', 'name' => 'Trinidad & Tobago'], ['code' => '+216', 'name' => 'Tunisia'], ['code' => '+90', 'name' => 'Turkey'],
            ['code' => '+993', 'name' => 'Turkmenistan'], ['code' => '+1649', 'name' => 'Turks & Caicos Islands'], ['code' => '+688', 'name' => 'Tuvalu'],
            ['code' => '+256', 'name' => 'Uganda'], ['code' => '+44', 'name' => 'UK'], ['code' => '+380', 'name' => 'Ukraine'],
            ['code' => '+971', 'name' => 'United Arab Emirates'], ['code' => '+598', 'name' => 'Uruguay'], ['code' => '+998', 'name' => 'Uzbekistan'],
            ['code' => '+678', 'name' => 'Vanuatu'], ['code' => '+379', 'name' => 'Vatican City'], ['code' => '+58', 'name' => 'Venezuela'],
            ['code' => '+84', 'name' => 'Vietnam'], ['code' => '+1284', 'name' => 'Virgin Islands - British'], ['code' => '+1340', 'name' => 'Virgin Islands - US'],
            ['code' => '+681', 'name' => 'Wallis & Futuna'], ['code' => '+969', 'name' => 'Yemen'], ['code' => '+967', 'name' => 'Yemen'],
            ['code' => '+260', 'name' => 'Zambia'], ['code' => '+263', 'name' => 'Zimbabwe'],
        ];

        // Load specific lists based on role
        $allSpecialities = [];
        $allConditions = [];
        switch ($user->role) {
            case 'practitioner':
                $allSpecialities = \App\Models\Specialization::where('status', true)->pluck('name');
                $allConditions = \App\Models\HealthCondition::where('status', true)->pluck('name');
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
                $allConditions = \App\Models\YogaExpertise::pluck('name');
                break;
            case 'translator':
                $allSpecialities = \App\Models\TranslatorSpecialization::where('status', true)->pluck('name');
                $allConditions = \App\Models\TranslatorService::where('status', true)->pluck('name');
                break;
            case 'client':
            case 'patient':
                $allSpecialities = ClientConsultationPreference::where('status', true)->pluck('name');
                break;
        }
        
        $allQualifications = Qualification::where('status', true)->pluck('name');

        $currencies = config('currencies.symbols');

        return view('client.complete-profile', compact('user', 'profile', 'countries', 'allLanguages', 'countryCodes', 'allSpecialities', 'allConditions', 'currencies', 'allQualifications'));
    }

    public function storeCompleteProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json(['success' => false, 'message' => 'Profile not found.'], 404);
        }

        // Generic validation for fields that exist across multiple roles
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'mobile_country_code' => 'nullable|string|max:10',
            'gender' => 'nullable|in:male,female,transgender,other',
            'dob' => 'nullable|date|before:today',
            'address_line_1' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'payout_currency' => 'nullable|string|max:10',
            'pan_number' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'swift_code' => 'nullable|string|max:20',
            'bank_holder_name' => 'nullable|string|max:255',
            'bank_account_holder_name' => 'nullable|string|max:255',
            'state_ayurveda_council_name' => 'nullable|string|max:255',
            'short_doctor_bio' => 'nullable|string',
        ]);

        // Update User Model (Global fields)
        if ($request->filled('email')) {
            // Check if email was verified (either in session or already in DB)
            $isAlreadyVerified = $user->email === $request->email && $user->email_verified_at;
            $isSessionVerified = session('verified_email') === $request->email;

            if (!$isAlreadyVerified && !$isSessionVerified) {
                return response()->json(['success' => false, 'message' => 'Please verify your email before saving.'], 422);
            }

            $user->email = $request->email;
        }
        
        if ($request->filled('phone')) $user->phone = $request->phone;
        if ($request->filled('gender')) $user->gender = $request->gender;
        $user->save();

        // Prepare data for the specific profile table - EXCLUDE email here
        $data = $request->except(['_token', 'profile_pic', 'cropped_image', 'email']);
        
        // Handle file uploads based on role requirements
        $fileFields = [
            'reg_certificate_path', 'digital_signature_path', 'pan_upload_path', 'cancelled_cheque_path',
            'registration_certificate_path', 'signature_path', 'pan_card_path', 'aadhaar_card_path',
            'doc_cover_letter', 'doc_certificates', 'doc_experience', 'doc_registration', 'doc_ethics', 
            'doc_contract', 'doc_id_proof', 'gov_id_upload_path', 'registration_proof_path', 'certificates_path'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('profile_docs/' . $user->id, 'public');
            }
        }

        // Handle Array fields
        $arrayFields = [
            'specialization', 'health_conditions_treated', 'consultations', 'body_therapies',
            'practitioner_type', 'client_concerns', 'consultation_modes', 'languages_spoken',
            'source_languages', 'target_languages', 'fields_of_specialization', 'services_offered',
            'areas_of_expertise', 'consultation_preferences'
        ];

        foreach ($arrayFields as $field) {
            if ($request->has($field)) {
                $data[$field] = (array)$request->input($field);
            }
        }

        // Handle Boolean fields (Consents for Doctors)
        if ($user->role === 'doctor') {
            $booleanFields = [
                'ayush_registration_confirmed', 'ayush_guidelines_agreed', 'document_verification_consented',
                'policies_agreed', 'prescription_understanding_agreed', 'confidentiality_consented'
            ];
            foreach ($booleanFields as $field) {
                $data[$field] = $request->has($field);
            }
        }

        // Update the profile model
        $profile->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'redirect' => route('dashboard')
            ]);
        }

        return redirect()->route('dashboard')->with('status', 'Profile completed successfully!');
    }

    public function sendEmailOTP(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if email is already taken by another user
        $exists = \App\Models\User::where('email', $email)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'This email is already in use by another account.'], 422);
        }

        // Generate 6-digit OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));

        // Store OTP in Cache (expire in 10 mins)
        Cache::put('email_otp_' . $email, $otp, now()->addMinutes(10));

        // Send Email
        try {
            Mail::to($email)->send(new \App\Mail\EmailVerificationOTPMail($user->name, $otp));
            return response()->json(['success' => true, 'message' => 'OTP has been sent to your email.']);
        } catch (\Exception $e) {
            \Log::error('OTP Send Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send email. Please try again.'], 500);
        }
    }

    public function verifyEmailOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $cachedOtp = Cache::get('email_otp_' . $request->email);

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 422);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Mark verified
        if ($user->email === $request->email) {
            $user->email_verified_at = now();
            $user->save();
        } else {
            session(['verified_email' => $request->email]);
        }

        // Clean up
        Cache::forget('email_otp_' . $request->email);

        return response()->json(['success' => true, 'message' => 'Email verified successfully!']);
    }

    public function reviews()
    {
        $user = Auth::user();

        // Reviews written by this user (Both Professional and Zaya Reviews)
        $practitionerReviews = PractitionerReview::with('practitioner.user')
            ->where('user_id', $user->id)
            ->get()
            ->map(function($r) {
                $r->review_type = 'Professional Review';
                $r->target_name = $r->practitioner->user->name ?? 'N/A';
                $r->target_role = str_replace('_', ' ', $r->practitioner->user->role);
                $r->target_pic = $r->practitioner->profile_photo_path ? asset('storage/' . $r->practitioner->profile_photo_path) : asset('frontend/assets/profile-dummy-img.png');
                $r->display_status = $r->status ? 'approved' : 'pending';
                return $r;
            });

        $zayaReviews = collect();
        if (\Illuminate\Support\Facades\Schema::hasColumn('testimonials', 'user_id')) {
            $zayaReviews = \App\Models\Testimonial::where('user_id', $user->id)
                ->get()
                ->map(function($t) {
                    $t->review_type = 'Zaya Review';
                    $t->target_name = 'Zaya Wellness';
                    $t->target_role = 'Platform';
                    $t->target_pic = asset('frontend/assets/logo-icon.png');
                    $t->review = $t->message;
                    $t->display_status = $t->status;
                    return $t;
                });
        }

        $myReviews = $practitionerReviews->concat($zayaReviews)->sortByDesc('created_at');
        
        // Manual pagination for combined results
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage('my_page');
        $perPage = 10;
        $currentItems = $myReviews->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $myReviews = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, $myReviews->count(), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'my_page',
        ]);

        // Reviews received by this user (if they are a professional)
        if ($user->profile_id && in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist'])) {
            $receivedReviews = PractitionerReview::with('user')
                ->where('practitioner_id', $user->profile_id)
                ->where('status', true)
                ->latest()
                ->paginate(10, ['*'], 'received_page');
        } else {
            $receivedReviews = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1, ['path' => request()->url(), 'pageName' => 'received_page']);
        }

        // List of professionals this user has had sessions with
        $reviewablePractitioners = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('practitioner.user')
            ->get()
            ->pluck('practitioner')
            ->unique('id')
            ->filter();

        return view('reviews', compact('user', 'myReviews', 'receivedReviews', 'reviewablePractitioners'));
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'practitioner_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Optional: Check if they actually had a session
        $hadSession = Booking::where('user_id', $user->id)
            ->where('profile_id', $request->practitioner_id)
            ->whereIn('status', ['paid', 'completed'])
            ->exists();

        if (!$hadSession) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'You can only review professionals you have had a completed session with.'], 403);
            }
            return back()->with('error', 'You can only review professionals you have had a completed session with.');
        }

        PractitionerReview::create([
            'user_id' => $user->id,
            'practitioner_id' => $request->practitioner_id,
            'rating' => $request->rating,
            'review' => $request->review,
            'status' => false, // Default to pending for moderation
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Review submitted successfully!']);
        }

        return back()->with('status', 'Review submitted successfully!');
    }

    public function storeZayaReview(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();

        \App\Models\Testimonial::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'role' => str_replace('_', ' ', ucfirst($user->role)),
            'message' => $request->message,
            'rating' => $request->rating,
            'image' => $user->profile_pic,
            'status' => 'pending' // Default to pending for moderation
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Thank you for your feedback! Your story has been submitted for review.']);
        }

        return back()->with('status', 'Thank you for your feedback! Your story has been submitted for review.');
    }

    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;
        $currentUser = Auth::user();

        $exists = \App\Models\User::where('email', $email)
            ->where('id', '!=', $currentUser->id)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function storePromoCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50'
        ]);

        $code = strtoupper(trim($request->code));
        $user = Auth::user();

        // 1. Check if the promo code exists and is active
        $promo = PromoCode::where('code', $code)
            ->where('status', true)
            ->where(function($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()->toDateString());
            })->first();

        if (!$promo) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Invalid or expired promo code.'], 422);
            return back()->with('error', 'Invalid or expired promo code.');
        }

        // 2. Check usage limit
        if ($promo->usage_limit !== null && $promo->used_count >= $promo->usage_limit) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'This promo code has reached its usage limit.'], 422);
            return back()->with('error', 'This promo code has reached its usage limit.');
        }

        // 3. Check if user already has it
        $exists = UserPromoCode::where('user_id', $user->id)
            ->where('promo_code', $code)
            ->exists();

        if ($exists) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'This promo code is already in your list.'], 422);
            return back()->with('info', 'This promo code is already in your list.');
        }

        // 4. Link to user
        UserPromoCode::create([
            'user_id' => $user->id,
            'promo_code' => $code
        ]);

        if ($request->ajax()) return response()->json(['success' => true, 'message' => 'Promo code added to your exclusive offers!']);
        return back()->with('success', 'Promo code added to your exclusive offers!');
    }

}
