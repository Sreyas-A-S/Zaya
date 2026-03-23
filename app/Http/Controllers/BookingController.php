<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Practitioner;
use App\Models\Service;
use App\Models\Translator;
use App\Models\Language;
use App\Mail\BookingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['fetchTranslators']);
    }

    public function store(Request $request)
    {
        // Enforce Client/Patient only
        if (!in_array(auth()->user()->role, ['client', 'patient'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only clients can book sessions. Please login with a client account.'
            ], 403);
        }

        $request->validate([
            'practitioner_id' => 'required|exists:practitioners,id',
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
            'mode' => 'required|in:online,in-person',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|string',
            'total_price' => 'required|numeric',
            'from_language' => 'nullable|string',
            'to_language' => 'nullable|string',
            'situation' => 'nullable|string',
            'conditions' => 'nullable|string',
        ]);

        $booking = new Booking();
        $booking->user_id = Auth::id();
        $booking->practitioner_id = $request->practitioner_id;
        $booking->service_ids = $request->service_ids;
        $booking->mode = $request->mode;
        $booking->conditions = $request->conditions;
        $booking->situation = $request->situation;
        $booking->need_translator = $request->boolean('need_translator');
        $booking->from_language = $request->from_language;
        $booking->to_language = $request->to_language;
        $booking->language_id = $request->language_id;
        $booking->translator_id = $request->translator_id;
        $booking->booking_date = $request->booking_date;
        $booking->booking_time = $request->booking_time;
        $booking->total_price = $request->total_price;
        $booking->status = 'pending';
        
        // Generate unique invoice number: ZAYA-YYYYMMDD-XXXXX
        $today = date('Ymd');
        $lastBooking = Booking::where('invoice_no', 'like', "ZAYA-$today-%")->orderBy('id', 'desc')->first();
        $sequence = 1;
        if ($lastBooking && preg_match('/-(\d+)$/', $lastBooking->invoice_no, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }
        $booking->invoice_no = "ZAYA-$today-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        $booking->save(); // Save first to get the ID
        
        // --- Razorpay Payment Link Creation ---
        $razorpayKey = config('services.razorpay.key');
        $razorpaySecret = config('services.razorpay.secret');
        
        $paymentUrl = null;
        $orderId = 'mock_plink_' . uniqid();

        if ($razorpayKey && $razorpaySecret && !str_contains($razorpayKey, 'dummy')) {
            $verifySsl = config('services.razorpay.verify_ssl');
            if ($verifySsl === null) {
                $verifySsl = !app()->environment('local');
            }
            try {
                $response = Http::withOptions(['verify' => (bool) $verifySsl])
                    ->withBasicAuth($razorpayKey, $razorpaySecret)
                    ->post('https://api.razorpay.com/v1/payment_links', [
                        'amount' => (int)round($request->total_price * 100),
                        'currency' => 'INR',
                        'description' => 'Session Booking with ' . ($booking->practitioner->user->name ?? 'Practitioner'),
                        'customer' => [
                            'name' => Auth::user()->name,
                            'email' => Auth::user()->email,
                            'contact' => Auth::user()->phone ?? '',
                        ],
                        'callback_url' => route('bookings.payment.callback'),
                        'callback_method' => 'get',
                        'notes' => [
                            'booking_id' => $booking->id
                        ]
                    ]);

                if ($response->successful()) {
                    $paymentUrl = $response->json('short_url');
                    $orderId = $response->json('id');
                } else {
                    \Log::error('Razorpay Payment Link Error: ' . $response->body());
                }
            } catch (\Exception $e) {
                \Log::error('Razorpay Connection Error: ' . $e->getMessage());
            }
        }

        $booking->razorpay_order_id = $orderId;
        $booking->razorpay_payment_url = $paymentUrl;
        $booking->save();

        if (!$paymentUrl) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway is unavailable. Please try again later.',
                'booking' => $booking,
                'redirect_url' => null
            ], 503);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking created! Opening payment gateway...',
            'booking' => $booking,
            'redirect_url' => $paymentUrl
        ]);
    }

    public function paymentCallback(Request $request)
    {
        $paymentLinkId = $request->razorpay_payment_link_id;
        $status = $request->razorpay_payment_link_status;
        $paymentId = $request->razorpay_payment_id;

        $booking = Booking::where('razorpay_order_id', $paymentLinkId)->firstOrFail();

        if ($status === 'paid') {
            $booking->status = 'confirmed';
            $booking->razorpay_payment_id = $paymentId;
            $booking->payment_details = $request->all();
            $booking->save();

            // Send Emails
            try {
                // To Client
                Mail::to($booking->user->email)->send(new BookingMail($booking, 'client'));
                
                // To Practitioner
                if ($booking->practitioner && $booking->practitioner->user) {
                    Mail::to($booking->practitioner->user->email)->send(new BookingMail($booking, 'practitioner'));
                }
                
                // To Translator
                if ($booking->need_translator && $booking->translator && $booking->translator->user) {
                    Mail::to($booking->translator->user->email)->send(new BookingMail($booking, 'translator'));
                }
            } catch (\Exception $e) {
                \Log::error('Booking Email Error: ' . $e->getMessage());
            }

            return redirect()->route('invoice.show', $booking->invoice_no)->with('success', 'Payment successful! Your booking is confirmed.');
        }

        $booking->payment_details = $request->all();
        $booking->save();

        return redirect()->route('home')->with('error', 'Payment was not completed. Please try again or contact support.');
    }


    public function fetchTranslators(Request $request)
    {
        $fromId = $request->from_language_id;
        $toId = $request->to_language_id;
        
        $fromLang = Language::find($fromId);
        $toLang = Language::find($toId);
        
        if (!$fromLang || !$toLang) {
            return response()->json([]);
        }

        // Fetch translators who can speak both languages
        $translators = Translator::where('status', 'active')
            ->where(function($query) use ($fromLang) {
                $query->whereJsonContains('target_languages', $fromLang->name)
                      ->orWhereJsonContains('source_languages', $fromLang->name)
                      ->orWhereJsonContains('additional_languages', $fromLang->name);
            })
            ->where(function($query) use ($toLang) {
                $query->whereJsonContains('target_languages', $toLang->name)
                      ->orWhereJsonContains('source_languages', $toLang->name)
                      ->orWhereJsonContains('additional_languages', $toLang->name);
            })->get();
        
        return response()->json($translators);
    }

    public function fetchReferrablePractitioners()
    {
        $users = User::whereIn('role', ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist'])
            ->where('status', 'active')
            ->get(['id', 'name', 'role']);
            
        return response()->json($users);
    }
}
