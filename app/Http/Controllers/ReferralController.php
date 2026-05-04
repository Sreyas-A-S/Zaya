<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Referral;
use App\Models\User;
use App\Models\Service;
use App\Mail\ReferralInvitationMail;
use App\Mail\ReferralReceivedMail;
use App\Mail\BookingMail;
use App\Services\CurrencyConversionService;
use App\Traits\FinancialTrait;
use App\Services\PractitionerAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Controllers\DataAccessController;
use Carbon\Carbon;

class ReferralController extends Controller
{
    use FinancialTrait;

    /**
     * Practitioner refers a booking to another practitioner/doctor.
     */
    public function store(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist'], true)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $booking = Booking::with('user')->findOrFail($id);
        
        // Security check: Only the expert assigned to this session can refer it.
        // We compare profile_id to ensure it's the same professional entity.
        if ($booking->profile_id !== $user->profile_id) {
            return response()->json(['error' => 'You can only refer your own bookings.'], 403);
        }

        $request->validate([
            'referrals' => 'required|array|min:1',
            'referrals.*.id' => 'required|exists:users,id',
            'referrals.*.amount' => 'required|numeric|min:0',
            'referrals.*.booking_date' => 'required|date|after_or_equal:today',
            'referrals.*.booking_time' => 'required|string',
            'note' => 'nullable|string',
        ]);

        // Check if current practitioner already has approved access
        $hasExistingAccess = DataAccessController::hasAccess($user->id, $booking->user_id);

        $availabilityService = new PractitionerAvailabilityService();
        $clientCountryId = null;
        try {
            $clientUser = $booking->user;
            $raw = $clientUser ? ($clientUser->national_id ?? null) : null;
            if (is_array($raw)) {
                foreach ($raw as $v) { if (is_numeric($v)) { $clientCountryId = (int) $v; break; } }
            } elseif (is_numeric($raw)) {
                $clientCountryId = (int) $raw;
            }
        } catch (\Throwable $e) {}

        $batchNo = 'BATCH-' . strtoupper(Str::random(10));
        $referralResults = [];
        $proNames = [];
        $initialStatus = $hasExistingAccess ? 'pending' : 'awaiting_consent';

        foreach ($request->referrals as $refData) {
            $referredToUser = User::find($refData['id']);
            $profile = $referredToUser ? $referredToUser->profile : null;
            if (!$profile) {
                return response()->json(['error' => 'Selected professional does not have an active profile.'], 422);
            }

            $availableSlots = $availabilityService->getAvailableSlotsForProvider($profile, $refData['booking_date']);
            $availableTimes = collect($availableSlots)->pluck('time')->filter()->values()->all();
            if (!in_array($refData['booking_time'], $availableTimes, true)) {
                return response()->json([
                    'error' => "No slot available for {$referredToUser->name} on {$refData['booking_date']} at {$refData['booking_time']}. Please choose another time."
                ], 422);
            }

            $referralNo = 'ZAYA-REF-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            
            // If has access and amount is 0, we can skip pending and go straight to paid
            $status = ($hasExistingAccess && $refData['amount'] == 0) ? 'paid' : $initialStatus;

            // Only refer services that the professional actually handles
            $requiredServiceIds = (array) ($booking->service_ids ?? []);
            $handledServices = \App\Models\UserService::where('user_id', $refData['id'])
                ->whereIn('service_id', $requiredServiceIds)
                ->get();
            
            $handledServiceIds = $handledServices->pluck('service_id')->toArray();
            
            // Calculate correct amount based on handled services
            $calculatedAmount = (float) $handledServices->sum('rate');
            
            // If they handle some services, only show those. If none, fall back to consultation services but keep original amount if provided.
            $finalServiceIds = !empty($handledServiceIds) ? $handledServiceIds : $requiredServiceIds;
            $finalAmount = $calculatedAmount > 0 ? $calculatedAmount : (float) ($refData['amount'] ?? 0);

            $referral = Referral::create([
                'referral_no' => $referralNo,
                'batch_no' => $batchNo,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'referred_by_id' => $user->id,
                'referred_to_id' => $refData['id'],
                'service_ids' => $finalServiceIds,
                'amount' => $finalAmount,
                'currency' => $this->resolveProfessionalCurrency($referredToUser),
                'booking_date' => $refData['booking_date'],
                'booking_time' => $refData['booking_time'],
                'note' => $request->note,
                'status' => $status,
            ]);

            $proNames[] = $referral->referredTo->name;

            // Notify Referred Professional (Informational)
            try {
                Mail::to($referral->referredTo->email)->send(new ReferralReceivedMail($referral));
            } catch (\Throwable $e) {
                Log::error('Referral Received Email Error: ' . $e->getMessage());
            }

            if ($status === 'paid') {
                $this->createReferredBooking($referral, null);
            }

            $referralResults[] = $referralNo;
        }

        if (!$hasExistingAccess) {
            // Trigger OTP only if no existing access
            $this->triggerReferralOTP($user, $booking->user, $proNames);
            $msg = 'Referral request sent! The client has been notified to provide consent via OTP.';
        } else {
            $msg = 'Referral processed! The client has been notified for payment/confirmation.';
        }

        // Send ONE Referral Invitation Mail to Client
        try {
            $batchReferrals = Referral::where('batch_no', $batchNo)->get();
            $firstReferral = $batchReferrals->first();
            if ($booking->user && $firstReferral) {
                Mail::to($booking->user->email)->send(new ReferralInvitationMail($firstReferral)); 
            }
        } catch (\Throwable $e) {
            Log::error('Referral Invitation Email Error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => $msg,
            'batch_no' => $batchNo
        ]);
    }

    private function triggerReferralOTP($practitioner, $client, $proNames)
    {
        $otp = rand(100000, 999999);
        
        \App\Models\DataAccessRequest::updateOrCreate(
            [
                'requester_id' => $practitioner->id,
                'client_id' => $client->id,
                'type' => 'referral',
            ],
            [
                'otp' => $otp,
                'meta' => $proNames,
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(15),
                'approved_at' => null,
            ]
        );

        try {
            Mail::to($client->email)->send(new \App\Mail\ReferralOTPMail($otp, $practitioner->name, implode(', ', $proNames)));
        } catch (\Throwable $e) {
            Log::error('Referral OTP Email Error: ' . $e->getMessage());
        }
    }

    public function resendOTP($referral_no)
    {
        $referral = Referral::with(['user', 'referredBy'])->where('referral_no', $referral_no)->firstOrFail();
        
        $accessRequest = \App\Models\DataAccessRequest::where('requester_id', $referral->referred_by_id)
            ->where('client_id', $referral->user_id)
            ->where('type', 'referral')
            ->first();

        if ($accessRequest && $accessRequest->updated_at->addMinute()->isFuture()) {
            $seconds = $accessRequest->updated_at->addMinute()->diffInSeconds(now());
            return response()->json(['error' => "Please wait {$seconds} seconds before requesting a new OTP."], 422);
        }

        $proNames = Referral::where('batch_no', $referral->batch_no)
            ->with('referredTo')
            ->get()
            ->pluck('referredTo.name')
            ->toArray();

        $this->triggerReferralOTP($referral->referredBy, $referral->user, $proNames);

        return response()->json(['success' => 'A new OTP has been sent to your email.']);
    }

    private function createReferredBooking($referral, $paymentId = null)
    {
        $oldBooking = $referral->booking;
        $referredToUser = User::with(['practitioner', 'doctor'])->find($referral->referred_to_id);
        $currency = strtoupper((string) ($referral->currency ?? $this->resolveProfessionalCurrency($referredToUser) ?? config('currencies.default', 'INR')));

        $newBooking = Booking::create([
            'invoice_no' => $referral->referral_no,
            'user_id' => $referral->user_id,
            'profile_id' => $referredToUser->profile_id,
            'practitioner_type' => $referredToUser->profile->getMorphClass(),
            'service_ids' => $referral->service_ids,
            'mode' => $oldBooking->mode,
            'conditions' => $oldBooking->conditions,
            'situation' => $oldBooking->situation,
            'need_translator' => $oldBooking->need_translator,
            'from_language' => $oldBooking->from_language,
            'to_language' => $oldBooking->to_language,
            'language_id' => $oldBooking->language_id,
            'translator_id' => $oldBooking->translator_id,
            'booking_date' => $referral->booking_date,
            'booking_time' => $referral->booking_time,
            'total_price' => $referral->amount,
            'currency' => $currency,
            'status' => 'confirmed',
            'razorpay_payment_id' => $paymentId,
        ]);

        // Auto-grant data access to the new professional
        DataAccessController::grantAccess($referral->referred_to_id, $referral->user_id);

        // Record Financial Transaction
        $this->recordTransaction([
            'type' => 'referral',
            'amount' => $referral->amount,
            'user_id' => $referral->user_id,
            'practitioner_id' => $referredToUser->id, // Receiver
            'referrer_id' => $referral->referred_by_id, // Referrer
            'booking_id' => $newBooking->id,
            'referral_id' => $referral->id,
            'payment_id' => $paymentId,
            'currency' => $currency,
            'referrer_role' => $referral->referredBy->role ?? null,
            'referred_role' => $referredToUser->role ?? null,
        ]);

        $newBooking->load('referral.referredBy');

        try {
            Mail::to($newBooking->user->email)->send(new BookingMail($newBooking, 'client'));
            if ($newBooking->practitioner && $newBooking->practitioner->user) {
                Mail::to($newBooking->practitioner->user->email)->send(new BookingMail($newBooking, 'practitioner'));
            }
        } catch (\Throwable $e) {
            Log::error('Referral Booking Confirmation Email Error: ' . $e->getMessage());
        }
        
        return $newBooking;
    }

    public function pay($referral_no)
    {
        $referral = Referral::with(['user', 'referredBy', 'referredTo', 'booking'])->where('referral_no', $referral_no)->firstOrFail();
        $batch = Referral::with(['referredTo'])->where('batch_no', $referral->batch_no)->get();

        $serviceTitles = [];
        try {
            $serviceIds = (array) ($referral->service_ids ?? []);
            $serviceIds = array_values(array_filter($serviceIds, fn ($v) => is_numeric($v)));
            if (!empty($serviceIds)) {
                $serviceTitles = Service::whereIn('id', $serviceIds)->pluck('title')->filter()->values()->all();
            }
        } catch (\Throwable $e) {
            $serviceTitles = [];
        }

        $expertCurrency = strtoupper((string) ($referral->currency ?? $this->resolveProfessionalCurrency($referral->referredTo) ?? config('currencies.default', 'INR')));
        $clientCurrency = derive_currency_from_user(Auth::user());

        $converted = null;
        if ($expertCurrency && $clientCurrency && $expertCurrency !== $clientCurrency) {
            $converted = app(CurrencyConversionService::class)->convert((float) $referral->amount, $expertCurrency, $clientCurrency);
        }

        if ($referral->status === 'awaiting_consent') {
            return view('referrals.consent', compact('referral', 'batch'));
        }

        if ($referral->status === 'paid') {
            return redirect()->route('dashboard')->with('info', 'This referral session is already confirmed.');
        }

        if ($referral->status === 'pending' && $referral->amount > 0) {
            return view('referrals.pay', compact('referral', 'serviceTitles', 'expertCurrency', 'clientCurrency', 'converted'));
        }

        return redirect()->route('dashboard');
    }

    public function initiatePayment($referral_no)
    {
        Log::info('initiatePayment hit', ['referral_no' => $referral_no]);
        $referral = Referral::with(['user', 'referredTo'])->where('referral_no', $referral_no)->firstOrFail();

        if ($referral->status === 'awaiting_consent') {
            return redirect()->route('referrals.pay', $referral_no);
        }

        if ($referral->status === 'paid') {
            return redirect()->route('dashboard')->with('info', 'This referral session is already confirmed.');
        }

        if ($referral->status === 'pending' && $referral->amount > 0) {
            return $this->initiateRazorpay($referral);
        }

        return redirect()->route('dashboard');
    }

    public function verifyConsent(Request $request, $referral_no)
    {
        $request->validate(['otp' => 'required|size:6']);
        
        $referral = Referral::where('referral_no', $referral_no)->firstOrFail();
        $batch = Referral::where('batch_no', $referral->batch_no)->get();

        $accessRequest = \App\Models\DataAccessRequest::where('requester_id', $referral->referred_by_id)
            ->where('client_id', $referral->user_id)
            ->where('type', 'referral')
            ->where('status', 'pending')
            ->where('otp', $request->otp)
            ->first();

        if (!$accessRequest || $accessRequest->expires_at->isPast()) {
            return back()->with('error', 'Invalid or expired OTP.');
        }

        $accessRequest->update([
            'status' => 'approved',
            'approved_at' => Carbon::now(),
            'otp' => null,
        ]);

        foreach ($batch as $ref) {
            if ($ref->amount > 0) {
                $ref->update(['status' => 'pending']);
            } else {
                $ref->update(['status' => 'paid']);
                $this->createReferredBooking($ref);
            }
        }

        if ($referral->amount > 0) {
            return redirect()
                ->route('referrals.pay', $referral->referral_no)
                ->with('success', 'Consent verified. Please proceed to payment to confirm the referral.');
        }

        return redirect()->route('dashboard')->with('success', 'Consent granted and referral confirmed!');
    }

    private function initiateRazorpay($referral)
    {
        $razorpayKey = config('services.razorpay.key');
        $razorpaySecret = config('services.razorpay.secret');

        $verifySsl = config('services.razorpay.verify_ssl');
        if ($verifySsl === null) {
            $verifySsl = !app()->environment('local');
        }

        $currency = strtoupper((string) ($referral->currency ?? $this->resolveProfessionalCurrency($referral->referredTo) ?? config('currencies.default', 'INR')));

        $customerPhone = (string) ($referral->user->phone ?? $referral->user->mobile ?? '');

        $payload = [
            'amount' => (int) round(((float) $referral->amount) * 100),
            'currency' => $currency,
            'accept_partial' => false,
            'description' => "Referral Session: " . $referral->referredTo->name,
            'customer' => [
                'name' => $referral->user->name,
                'email' => $referral->user->email,
                'contact' => $customerPhone,
            ],
            'notify' => ['sms' => false, 'email' => true],
            'callback_url' => url('/referrals/payment/callback'),
            'callback_method' => 'get',
            'notes' => [
                'referral_no' => $referral->referral_no,
                'referral_currency' => $currency,
            ]
        ];

        Log::info('Initiating Razorpay Referral Payment Link:', [
            'referral_no' => $referral->referral_no,
            'payload' => $payload
        ]);

        try {
            $response = Http::withOptions(['verify' => (bool) $verifySsl])
                ->withBasicAuth($razorpayKey, $razorpaySecret)
                ->post('https://api.razorpay.com/v1/payment_links', $payload);

            if ($response->successful()) {
                $paymentData = $response->json();
                $referral->razorpay_order_id = $paymentData['id'];
                $referral->save();
                return redirect($paymentData['short_url']);
            }

            Log::error('Razorpay Referral Payment Link API Error:', [
                'referral_no' => $referral->referral_no,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Razorpay Referral Connection Error:', [
                'referral_no' => $referral->referral_no,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return redirect()->route('dashboard')->with('error', 'Unable to initiate payment.');
    }

    public function paymentCallback(Request $request)
    {
        $paymentLinkId = $request->razorpay_payment_link_id;
        $status = $request->razorpay_payment_link_status;
        $paymentId = $request->razorpay_payment_id;

        $referral = Referral::where('razorpay_order_id', $paymentLinkId)->firstOrFail();

        if ($status === 'paid') {
            $referral->status = 'paid';
            $referral->razorpay_payment_id = $paymentId;
            $referral->save();

            $newBooking = $this->createReferredBooking($referral, $paymentId);

            return redirect()->route('invoice.show', $newBooking->invoice_no)->with('success', 'Referral payment successful!');
        }

        return redirect()->route('dashboard')->with('error', 'Payment failed.');
    }

    public function requestReReferral(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::with('referral')->findOrFail($id);

        if (!$booking->referral || $booking->referral->referred_to_id !== $user->id) {
            return response()->json(['error' => 'Only the referred expert can request a re-referral.'], 403);
        }

        $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        \App\Models\ReferralRequest::create([
            'booking_id' => $booking->id,
            'requester_id' => $user->id,
            'recipient_id' => $booking->referral->referred_by_id,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return response()->json(['success' => 'Re-referral request sent to the original practitioner.']);
    }

    public function updateRequestStatus(Request $request, $id)
    {
        $user = Auth::user();
        $referralRequest = \App\Models\ReferralRequest::findOrFail($id);

        if ($referralRequest->recipient_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:processed,dismissed',
        ]);

        $referralRequest->update(['status' => $request->status]);

        return response()->json(['success' => 'Request status updated.']);
    }

    private function resolveProfessionalCurrency(?User $user): string
    {
        if (!$user) return strtoupper(config('currencies.default', 'INR'));

        try {
            $profile = $user->profile;
            if ($profile && isset($profile->payout_currency) && $profile->payout_currency) {
                return strtoupper(trim((string) $profile->payout_currency));
            }
        } catch (\Throwable $e) {}

        return derive_currency_from_user($user);
    }
}
