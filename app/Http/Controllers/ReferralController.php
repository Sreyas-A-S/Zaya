<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Referral;
use App\Models\User;
use App\Models\Service;
use App\Mail\ReferralInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    /**
     * Practitioner refers a booking to another practitioner/doctor.
     */
    public function store(Request $request, $id)
    {
        $user = Auth::user();
        
        // Ensure only practitioners/doctors can refer
        if (!in_array($user->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $booking = Booking::findOrFail($id);
        
        // Check if current user is the practitioner of this booking
        // The project uses profile_id logic sometimes, but let's assume we can match via practitioner table
        // Or simplified: current user must be the practitioner linked to this booking
        if ($booking->practitioner->user_id !== $user->id) {
            return response()->json(['error' => 'You can only refer your own bookings.'], 403);
        }

        $request->validate([
            'referred_to_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $referralNo = 'REF-' . Str::upper(Str::random(10));

        $referral = Referral::create([
            'referral_no' => $referralNo,
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'referred_by_id' => $user->id,
            'referred_to_id' => $request->referred_to_id,
            'service_ids' => $booking->service_ids,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        // Send Email to Client
        try {
            Mail::to($booking->user->email)->send(new ReferralInvitationMail($referral));
        } catch (\Exception $e) {
            Log::error('Referral Email Error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => 'Referral request sent to the client successfully.',
            'referral_no' => $referralNo
        ]);
    }

    /**
     * Client clicks the link in email to pay for the referred session.
     */
    public function pay($referral_no)
    {
        $referral = Referral::with(['user', 'referredBy', 'referredTo', 'booking'])->where('referral_no', $referral_no)->firstOrFail();
        
        if ($referral->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'This referral has already been processed.');
        }

        // --- Razorpay Payment Link Creation ---
        $razorpayKey = config('services.razorpay.key');
        $razorpaySecret = config('services.razorpay.secret');

        $response = Http::withBasicAuth($razorpayKey, $razorpaySecret)
            ->post('https://api.razorpay.com/v1/payment_links', [
                'amount' => $referral->amount * 100, // in paise
                'currency' => 'INR', // Project seems to use INR for Razorpay
                'accept_partial' => false,
                'description' => "Referral Session: " . $referral->referredTo->name,
                'customer' => [
                    'name' => $referral->user->name,
                    'email' => $referral->user->email,
                    'contact' => $referral->user->phone ?? '9999999999',
                ],
                'notify' => [
                    'sms' => true,
                    'email' => true,
                ],
                'reminder_enable' => true,
                'callback_url' => route('referrals.payment.callback'),
                'callback_method' => 'get',
                'notes' => [
                    'referral_id' => $referral->id,
                    'referral_no' => $referral->referral_no
                ]
            ]);

        if ($response->successful()) {
            $paymentData = $response->json();
            $referral->razorpay_order_id = $paymentData['id'];
            $referral->save();

            return redirect($paymentData['short_url']);
        }

        return back()->with('error', 'Unable to initiate payment. Please contact support.');
    }

    /**
     * Callback after successful payment.
     */
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

            // Create a new Booking based on this referral
            $oldBooking = $referral->booking;
            
            $newBooking = Booking::create([
                'invoice_no' => 'ZAYA-REF-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => $referral->user_id,
                'practitioner_id' => $referral->referredTo->profile_id, // Assuming profile_id logic
                'service_ids' => $referral->service_ids,
                'mode' => $oldBooking->mode,
                'conditions' => $oldBooking->conditions,
                'situation' => $oldBooking->situation,
                'need_translator' => $oldBooking->need_translator,
                'from_language' => $oldBooking->from_language,
                'to_language' => $oldBooking->to_language,
                'language_id' => $oldBooking->language_id,
                'translator_id' => $oldBooking->translator_id,
                'booking_date' => now()->addDays(1), // Default to tomorrow or let them choose later
                'booking_time' => $oldBooking->booking_time,
                'total_price' => $referral->amount,
                'status' => 'confirmed',
                'razorpay_payment_id' => $paymentId,
            ]);

            return redirect()->route('invoice.show', $newBooking->invoice_no)->with('success', 'Referral payment successful! Your new session is confirmed.');
        }

        return redirect()->route('dashboard')->with('error', 'Payment was not completed.');
    }
}
