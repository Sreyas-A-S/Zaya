<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Prescription;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'client' || $user->role === 'patient') {
            $prescriptions = Prescription::where('user_id', $user->id)
                ->with('practitioner', 'booking')
                ->latest()
                ->get();
        } else {
            // Get expert's profile ID
            $profile = $user->profile;
            if (!$profile) {
                return view('prescriptions.index', ['prescriptions' => collect()]);
            }

            $prescriptions = Prescription::where('profile_id', $profile->id)
                ->where('practitioner_type', get_class($profile))
                ->with('patient', 'booking')
                ->latest()
                ->get();
        }

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create($bookingId)
    {
        $booking = Booking::with('practitioner')->findOrFail($bookingId);
        $user = Auth::user();

        // Check if user is the practitioner for this booking
        $practitionerUserId = $booking->practitioner->user_id ?? null;
        
        if ($practitionerUserId !== $user->id) {
            // Check if this booking was referred to THIS user
            $wasReferredToMe = Referral::where('referral_no', $booking->invoice_no)
                ->where('referred_to_id', $user->id)
                ->exists();
            
            if (!$wasReferredToMe) {
                // Check if user is a referred practitioner FROM this session
                $isReferredByMe = $booking->referralsFromThisSession()->where('referred_to_id', $user->id)->exists();
                if (!$isReferredByMe) {
                    abort(403, 'Unauthorized action.');
                }
            }
        }

        return view('prescriptions.create', compact('booking'));
    }

    public function store(Request $request, $bookingId)
    {
        $booking = Booking::with('practitioner')->findOrFail($bookingId);
        $user = Auth::user();

        // Security check
        $practitionerUserId = $booking->practitioner->user_id ?? null;
        if ($practitionerUserId !== $user->id) {
            $wasReferredToMe = Referral::where('referral_no', $booking->invoice_no)
                ->where('referred_to_id', $user->id)
                ->exists();
            
            if (!$wasReferredToMe) {
                $isReferredByMe = $booking->referralsFromThisSession()->where('referred_to_id', $user->id)->exists();
                if (!$isReferredByMe) {
                    abort(403, 'Unauthorized action.');
                }
            }
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'prescription_date' => 'required|date',
            'medications' => 'nullable|array',
            'medications.*.name' => 'required|string',
            'medications.*.dosage' => 'nullable|string',
            'medications.*.frequency' => 'nullable|string',
            'medications.*.timing' => 'nullable|string',
            'medications.*.duration' => 'nullable|string',
            'lifestyle_advice' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $profile = $user->profile;

        $prescription = Prescription::create([
            'booking_id' => $booking->id,
            'profile_id' => $profile->id,
            'practitioner_type' => get_class($profile),
            'user_id' => $booking->user_id,
            'title' => $validated['title'] ?: 'Prescription',
            'prescription_date' => $validated['prescription_date'],
            'medications' => $validated['medications'] ?? [],
            'lifestyle_advice' => $validated['lifestyle_advice'],
            'notes' => $validated['notes'],
            'status' => 'issued',
        ]);

        return redirect()->route('prescriptions.show', $prescription->id)
            ->with('success', 'Prescription issued successfully.');
    }

    public function show($id)
    {
        $prescription = Prescription::with('practitioner', 'patient', 'booking')->findOrFail($id);
        $user = Auth::user();

        // Get viewer's profile if they are an expert
        $profile = $user->profile;

        // Security check
        $isPatient = $prescription->user_id === $user->id;
        $isIssuer = $profile && $prescription->profile_id === $profile->id && $prescription->practitioner_type === get_class($profile);

        if (!$isPatient && !$isIssuer) {
            abort(403, 'Unauthorized access.');
        }

        return view('prescriptions.show', compact('prescription'));
    }
}
