<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Referral;
use App\Models\User;
use App\Models\Service;
use App\Mail\ReferralInvitationMail;
use App\Mail\ReferralReceivedMail;
use App\Mail\BookingMail;
use App\Traits\FinancialTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
        
        if ($booking->practitioner->user_id !== $user->id) {
            return response()->json(['error' => 'You can only refer your own bookings.'], 403);
        }

        $request->validate([
            'referrals' => 'required|array|min:1',
            'referrals.*.id' => 'required|exists:users,id',
            'referrals.*.amount' => 'required|numeric|min:0',
        ]);

        // Check if current practitioner already has approved access
        $hasExistingAccess = DataAccessController::hasAccess($user->id, $booking->user_id);

        $batchNo = 'BATCH-' . strtoupper(Str::random(10));
        $referralResults = [];
        $proNames = [];
        $initialStatus = $hasExistingAccess ? 'pending' : 'awaiting_consent';

        foreach ($request->referrals as $refData) {
            $referralNo = 'ZAYA-REF-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            
            // If has access and amount is 0, we can skip pending and go straight to paid
            $status = ($hasExistingAccess && $refData['amount'] == 0) ? 'paid' : $initialStatus;

            $referral = Referral::create([
                'referral_no' => $referralNo,
                'batch_no' => $batchNo,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'referred_by_id' => $user->id,
                'referred_to_id' => $refData['id'],
                'service_ids' => $booking->service_ids,
                'amount' => $refData['amount'],
                'status' => $status,
            ]);

            $proNames[] = $referral->referredTo->name;

            // Notify Referred Professional (Informational)
            try {
                Mail::to($referral->referredTo->email)->send(new ReferralReceivedMail($referral));
            } catch (\Exception $e) {
                Log::error('Referral Received Email Error: ' . $e->getMessage());
            }

            if ($status === 'paid') {
                $this->createReferredBooking($referral);
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
            Mail::to($booking->user->email)->send(new ReferralInvitationMail($batchReferrals->first())); 
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
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
        
        $newBooking = Booking::create([
            'invoice_no' => $referral->referral_no,
            'user_id' => $referral->user_id,
            'practitioner_id' => $referredToUser->profile_id ?? $referredToUser->practitioner->id ?? $referredToUser->doctor->id ?? null,
            'service_ids' => $referral->service_ids,
            'mode' => $oldBooking->mode,
            'conditions' => $oldBooking->conditions,
            'situation' => $oldBooking->situation,
            'need_translator' => $oldBooking->need_translator,
            'from_language' => $oldBooking->from_language,
            'to_language' => $oldBooking->to_language,
            'language_id' => $oldBooking->language_id,
            'translator_id' => $oldBooking->translator_id,
            'booking_date' => now()->addDays(1),
            'booking_time' => $oldBooking->booking_time,
            'total_price' => $referral->amount,
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
            'currency' => $newBooking->currency ?? 'INR',
        ]);

        $newBooking->load('referral.referredBy');

        try {
            Mail::to($newBooking->user->email)->send(new BookingMail($newBooking, 'client'));
            if ($newBooking->practitioner && $newBooking->practitioner->user) {
                Mail::to($newBooking->practitioner->user->email)->send(new BookingMail($newBooking, 'practitioner'));
            }
        } catch (\Exception $e) {
            Log::error('Referral Booking Confirmation Email Error: ' . $e->getMessage());
        }
        
        return $newBooking;
    }

    public function pay($referral_no)
    {
        $referral = Referral::with(['user', 'referredBy', 'referredTo', 'booking'])->where('referral_no', $referral_no)->firstOrFail();
        $batch = Referral::with(['referredTo'])->where('batch_no', $referral->batch_no)->get();

        if ($referral->status === 'awaiting_consent') {
            return view('referrals.consent', compact('referral', 'batch'));
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
            // Also grant access to all professionals in the batch
            DataAccessController::grantAccess($ref->referred_to_id, $ref->user_id);

            if ($ref->amount > 0) {
                $ref->update(['status' => 'pending']);
            } else {
                $ref->update(['status' => 'paid']);
                $this->createReferredBooking($ref);
            }
        }

        if ($referral->amount > 0) {
            return $this->initiateRazorpay($referral);
        }

        return redirect()->route('dashboard')->with('success', 'Consent granted and referral confirmed!');
    }

    private function initiateRazorpay($referral)
    {
        $razorpayKey = config('services.razorpay.key');
        $razorpaySecret = config('services.razorpay.secret');

        $response = Http::withBasicAuth($razorpayKey, $razorpaySecret)
            ->post('https://api.razorpay.com/v1/payment_links', [
                'amount' => $referral->amount * 100,
                'currency' => 'INR',
                'accept_partial' => false,
                'description' => "Referral Session: " . $referral->referredTo->name,
                'customer' => [
                    'name' => $referral->user->name,
                    'email' => $referral->user->email,
                    'contact' => $referral->user->phone ?? '9999999999',
                ],
                'notify' => ['sms' => true, 'email' => true],
                'callback_url' => route('referrals.payment.callback'),
                'callback_method' => 'get',
                'notes' => ['referral_no' => $referral->referral_no]
            ]);

        if ($response->successful()) {
            $paymentData = $response->json();
            $referral->razorpay_order_id = $paymentData['id'];
            $referral->save();
            return redirect($paymentData['short_url']);
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
}
