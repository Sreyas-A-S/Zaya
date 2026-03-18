<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('patient');

        $upcomingBookings = \App\Models\Booking::with(['practitioner.user'])
            ->where('user_id', $user->id)
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

        $completedBookings = \App\Models\Booking::with(['practitioner.user'])
            ->where('user_id', $user->id)
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

        // Invoices can be derived from paid bookings for now
        $invoices = \App\Models\Booking::where('user_id', $user->id)
            ->whereNotNull('razorpay_payment_id')
            ->latest()
            ->take(5)
            ->get();

        $allServices = \App\Models\Service::whereIn('id', collect($upcomingBookings->pluck('service_ids'))->collapse()->unique())->get()->keyBy('id');
        $allServices = $allServices->merge(\App\Models\Service::whereIn('id', collect($completedBookings->pluck('service_ids'))->collapse()->unique())->get()->keyBy('id'));

        return view('dashboard', compact('user', 'upcomingBookings', 'completedBookings', 'reviews', 'invoices', 'allServices'));
    }

    public function updateConsent(Request $request)
    {
        $user = Auth::user();
        if (!$user->patient) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $user->patient->update([
            'data_sharing_consent' => $request->consent ? 1 : 0
        ]);

        return response()->json(['message' => 'Consent updated successfully', 'consent' => $user->patient->data_sharing_consent]);
    }

    public function bookings(Request $request)
    {
        $user = Auth::user();
        $bookings = \App\Models\Booking::with(['practitioner.user'])
            ->where('user_id', $user->id)
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
        $conferences = \App\Models\Booking::with(['practitioner.user'])
            ->where('user_id', $user->id)
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
        $booking = \App\Models\Booking::with(['practitioner.user'])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->whereNotNull('recording_url')
            ->firstOrFail();

        return view('recordings.show', compact('user', 'booking'));
    }

    public function joinSession($channel)
    {
        $user = Auth::user();
        $appId = config('services.agora.app_id');
        
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
