<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private function getBookingQuery($user)
    {
        $role = $user->role;
        $profileId = $user->profile_id;
        $query = Booking::with(['practitioner.user', 'user']);

        if ($role === 'client' || $role === 'patient') {
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

    public function bookings(Request $request)
    {
        $user = Auth::user();
        $bookings = $this->getBookingQuery($user)
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return view('partials.bookings-table', compact('user', 'bookings'))->render();
        }

        return view('bookings', compact('user', 'bookings'));
    }

    public function transactions(Request $request)
    {
        $user = Auth::user();
        $invoices = \App\Models\Booking::where('user_id', $user->id)
            ->whereNotNull('razorpay_payment_id')
            ->latest()
            ->paginate(15);

        if ($request->ajax()) {
            return view('partials.transactions-table', compact('user', 'invoices'))->render();
        }

        return view('transactions', compact('user', 'invoices'));
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

        return view('practitioner.client-profile', compact('client', 'recordings', 'bookings'));
    }

    public function joinSession($channel)
    {
        $user = Auth::user();
        $appId = config('services.agora.app_id');
        
        \Log::info("User joining session:", [
            'user' => $user->id,
            'channel' => $channel,
            'has_app_id' => !empty($appId),
            'app_id_preview' => $appId ? substr($appId, 0, 5) . '...' : 'NONE'
        ]);

        if (!$appId) {
            return back()->with('error', 'Video consultation is not configured (Missing App ID).');
        }

        return view('conference.session', compact('user', 'channel', 'appId'));
    }

    public function generateToken(Request $request)
    {
        $appId = config('services.agora.app_id');
        $appCertificate = config('services.agora.app_certificate');
        $channelName = $request->channel;
        $uid = $request->uid ?? 0;
        
        \Log::info("Agora Token Request:", [
            'channel' => $channelName,
            'uid' => $uid,
            'has_app_id' => !empty($appId),
            'has_cert' => !empty($appCertificate)
        ]);

        if (!$appId || !$appCertificate) {
            return response()->json(['token' => null, 'error' => 'Agora credentials missing']);
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
            $privilegeExpiredTs
        );

        \Log::info("Generated Token: " . substr($token, 0, 10) . "...");

        return response()->json([
            'token' => $token, 
            'expire' => $privilegeExpiredTs
        ]);
    }
}
