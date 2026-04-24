<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingReservation; // Will eventually phase out
use App\Models\Practitioner;
use App\Models\Service;
use App\Models\Translator;
use App\Models\Language;
use App\Models\User;
use App\Models\UserService;
use App\Traits\FinancialTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    use FinancialTrait;

    /**
     * Step 1: Request Booking. 
     * Instead of locking the DB, we just return a payment link.
     */
    public function store(Request $request)
    {
        $request->validate([
            'practitioner_id' => 'required|exists:practitioners,id',
            'service_ids' => 'required|array',
            'mode' => 'required|string|in:online,in-person',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|string',
            'total_price' => 'required|numeric|min:0',
            'currency' => 'nullable|string',
            'promo_code' => 'nullable|string|exists:promo_codes,code',
            'discount_amount' => 'nullable|numeric|min:0',
            'coins_applied' => 'nullable|boolean',
            'test_mode' => 'nullable|boolean'
        ]);

        $practitioner = Practitioner::with('user')->findOrFail($request->practitioner_id);
        $user = Auth::user();
        $currency = $request->currency ?? 'INR';

        // 1. Calculate Subtotal on Server (Security: Don't trust request total_price)
        $subtotal = 0;
        $userServices = \App\Models\UserService::where('user_id', $practitioner->user_id)
            ->whereIn('service_id', $request->service_ids)
            ->where('status', 'active')
            ->get();
        
        \Log::info('Booking Calculation Debug:', [
            'practitioner_user_id' => $practitioner->user_id,
            'service_ids' => $request->service_ids,
            'services_found_count' => $userServices->count()
        ]);

        foreach ($userServices as $us) {
            $subtotal += (float) $us->rate;
            \Log::info("Adding service rate: {$us->rate} for service ID: {$us->service_id}");
        }
        
        // Fallback to request price if no specific rates found
        if ($subtotal <= 0) {
            $subtotal = (float) $request->total_price;
            \Log::info("Fallback subtotal from request: {$subtotal}");
        }

        // 2. Validate Promo Code
        $promoDiscount = 0;
        $promoCode = null;
        if ($request->promo_code) {
            $promo = \App\Models\PromoCode::where('code', $request->promo_code)
                ->where('status', true)
                ->where(function($q) {
                    $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()->toDateString());
                })->first();
            
            if ($promo && ($promo->usage_limit === null || $promo->used_count < $promo->usage_limit)) {
                $promoCode = $promo->code;
                if ($promo->type === 'percentage') {
                    $promoDiscount = ($subtotal * $promo->value) / 100;
                } else {
                    $promoDiscount = $promo->value;
                }
            }
        }

        // 3. Calculate Coin Discount
        $coinsUsed = 0;
        $coinDiscount = 0;
        if ($request->coins_applied && $user->coins > 0) {
            $coinSetting = \App\Models\CoinSetting::where('currency_code', $currency)->where('status', true)->first();
            if ($coinSetting && $coinSetting->coin_value > 0) {
                $afterPromo = max(0, $subtotal - $promoDiscount);
                
                $potentialDiscount = $user->coins * $coinSetting->coin_value;
                if ($potentialDiscount > $afterPromo) {
                    $coinDiscount = $afterPromo;
                    $coinsUsed = ceil($afterPromo / $coinSetting->coin_value);
                } else {
                    $coinDiscount = $potentialDiscount;
                    $coinsUsed = $user->coins;
                }
            }
        }

        // Final amount to be paid
        $isTestMode = $request->has('test_mode') && $request->test_mode;
        $realPayable = max(0, $subtotal - $promoDiscount - $coinDiscount);
        $finalPayable = $isTestMode ? 1.00 : $realPayable;
        
        \Log::info('Final Totals:', [
            'subtotal' => $subtotal,
            'promo_discount' => $promoDiscount,
            'coin_discount' => $coinDiscount,
            'real_payable' => $realPayable,
            'final_payable' => $finalPayable,
            'is_test' => $isTestMode
        ]);

        // 4. Availability Check
        if (!$this->checkSlotAvailability($practitioner->id, $practitioner->getMorphClass(), $request->booking_date, $request->booking_time)) {
            return response()->json(['success' => false, 'message' => 'This slot was just taken. Please choose another time.'], 422);
        }

        // 5. Handle Zero-Payable Bookings (Bypass Razorpay only if 0)
        if ($finalPayable <= 0) {
            $booking = Booking::create([
                'invoice_no' => 'ZAYA-FREE-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => $user->id,
                'profile_id' => $practitioner->id,
                'practitioner_type' => $practitioner->getMorphClass(),
                'service_ids' => $request->service_ids,
                'mode' => $request->mode,
                'conditions' => $request->conditions ?? null,
                'situation' => $request->situation ?? null,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'subtotal' => $subtotal,
                'total_price' => 0,
                'promo_code' => $promoCode,
                'discount_amount' => $promoDiscount,
                'coins_used' => $coinsUsed,
                'coin_discount' => $coinDiscount,
                'currency' => $currency,
                'status' => 'confirmed',
                'razorpay_payment_id' => 'ZERO_PAYMENT',
                'additional_info' => $request->additional_info,
            ]);

            // Deduct Coins
            if ($coinsUsed > 0) {
                $user->coins = max(0, $user->coins - $coinsUsed);
                $user->save();
            }

            // Increment Promo Usage
            if ($promoCode) {
                $promo = \App\Models\PromoCode::where('code', $promoCode)->first();
                if ($promo) $promo->incrementUsageIfAvailable();
            }

            // Record Financial Transaction
            $this->recordTransaction([
                'type' => 'booking',
                'amount' => $booking->total_price,
                'subtotal' => $booking->subtotal,
                'user_id' => $booking->user_id,
                'practitioner_id' => $practitioner->user_id,
                'booking_id' => $booking->id,
                'payment_id' => 'ZERO_PAYMENT',
                'currency' => $currency,
                'coins_used' => $coinsUsed,
                'coin_discount' => $coinDiscount,
            ]);

            try {
                \Illuminate\Support\Facades\Mail::to($booking->user->email)->send(new \App\Mail\BookingMail($booking, 'client'));
                if ($booking->practitioner && $booking->practitioner->user) {
                    \Illuminate\Support\Facades\Mail::to($booking->practitioner->user->email)->send(new \App\Mail\BookingMail($booking, 'practitioner'));
                }
                if ($booking->translator && $booking->translator->user) {
                    \Illuminate\Support\Facades\Mail::to($booking->translator->user->email)->send(new \App\Mail\BookingMail($booking, 'translator'));
                }
            } catch (\Exception $e) {
                \Log::error('Zero-Pay Booking Confirmation Email Error: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking confirmed! (Discount applied)',
                'redirect_url' => route('invoice.show', $booking->invoice_no),
            ]);
        }

        // ... (check availability)

        // 6. Create Temporary Reservation (to avoid Razorpay notes character limit)
        $reservationToken = 'RES-' . strtoupper(Str::random(12));
        $reservation = \App\Models\BookingReservation::create([
            'user_id' => $user->id,
            'profile_id' => $practitioner->id,
            'practitioner_type' => $practitioner->getMorphClass(),
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'reservation_token' => $reservationToken,
            'status' => 'reserved',
            'expires_at' => now()->addMinutes(30),
            'booking_data' => [
                'service_ids' => $request->service_ids,
                'mode' => $request->mode,
                'conditions' => $request->conditions,
                'situation' => $request->situation,
                'subtotal' => $subtotal,
                'total_price' => $realPayable,
                'promo_code' => $promoCode,
                'discount_amount' => $promoDiscount,
                'coins_used' => $coinsUsed,
                'coin_discount' => $coinDiscount,
                'currency' => $currency,
                'is_test' => $isTestMode,
                'additional_info' => $request->additional_info,
            ]
        ]);

        // --- Razorpay Payment Link Creation ---
        $paymentUrl = null;
        $razorpayKey = config('services.razorpay.key');
        $razorpaySecret = config('services.razorpay.secret');

        if ($razorpayKey && $razorpaySecret) {
            $verifySsl = config('services.razorpay.verify_ssl');
            
            $gatewayAmount = $finalPayable;
            $gatewayCurrency = $isTestMode ? 'INR' : $currency;

            try {
                $payload = [
                    'amount' => (int)(round($gatewayAmount, 2) * 100),
                    'currency' => $gatewayCurrency,
                    'accept_partial' => false,
                    'description' => 'Booking Session - Zaya Wellness',
                    'customer' => [
                        'name' => (string) ($user->name ?? 'Client'),
                        'email' => (string) $user->email,
                        'contact' => (string) ($user->phone ?? $user->mobile ?? ''),
                    ],
                    'notify' => ['sms' => false, 'email' => true],
                    'callback_url' => route('bookings.payment.callback'),
                    'callback_method' => 'get',
                    'notes' => [
                        'reservation_token' => (string) $reservationToken,
                        'user_id' => (string) $user->id,
                        'practitioner_id' => (string) $practitioner->id
                    ]
                ];

                \Log::info('Razorpay Payment Link Payload:', $payload);

                $response = Http::withBasicAuth($razorpayKey, $razorpaySecret)
                    ->withOptions(['verify' => (bool)$verifySsl])
                    ->post('https://api.razorpay.com/v1/payment_links', $payload);

                if ($response->successful()) {
                    $paymentUrl = $response->json('short_url');
                } else {
                    $errorBody = $response->json();
                    $errorMsg = $errorBody['error']['description'] ?? 'Razorpay API Error';
                    return response()->json(['success' => false, 'message' => 'Payment Error: ' . $errorMsg], 422);
                }
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Could not connect to payment gateway.'], 503);
            }
        }

        if (!$paymentUrl) {
            return response()->json(['success' => false, 'message' => 'Payment gateway is not configured correctly.'], 503);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => $paymentUrl,
        ]);
    }

    /**
     * Step 2: Callback. 
     * This is where the actual validation and booking happens.
     */
    public function paymentCallback(Request $request)
    {
        $paymentLinkId = $request->razorpay_payment_link_id;
        $status = $request->razorpay_payment_link_status;
        $paymentId = $request->razorpay_payment_id;
        
        // Fetch details from Razorpay to be safe
        $razorpayKey = config('services.razorpay.key');
        $razorpaySecret = config('services.razorpay.secret');
        $response = Http::withBasicAuth($razorpayKey, $razorpaySecret)
            ->get("https://api.razorpay.com/v1/payment_links/{$paymentLinkId}");

        if (!$response->successful() || $status !== 'paid') {
            return redirect()->route('home')->with('error', 'Payment not completed or verified.');
        }

        $pData = $response->json();
        $notes = $pData['notes'] ?? [];
        $reservationToken = $notes['reservation_token'] ?? null;

        if (!$reservationToken) {
            \Log::error('Razorpay Callback Error: No reservation token in notes.', ['link_id' => $paymentLinkId]);
            return redirect()->route('home')->with('error', 'Invalid payment details.');
        }

        $reservation = \App\Models\BookingReservation::where('reservation_token', $reservationToken)
            ->where('status', 'reserved')
            ->first();

        if (!$reservation) {
             \Log::error('Razorpay Callback Error: Reservation not found or already processed.', ['token' => $reservationToken]);
             return redirect()->route('home')->with('error', 'Booking session expired or already processed.');
        }

        $bookingData = $reservation->booking_data;

        // CRITICAL: Final availability check now that money is received
        $isAvailable = $this->checkSlotAvailability(
            $reservation->profile_id, 
            $reservation->practitioner_type,
            $reservation->booking_date, 
            $reservation->booking_time
        );

        if ($isAvailable) {
            // SUCCESS PATH
            $booking = Booking::create([
            'invoice_no' => 'ZAYA-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'user_id' => $reservation->user_id,
            'profile_id' => $reservation->profile_id,
            'practitioner_type' => $reservation->practitioner_type,
            'service_ids' => $bookingData['service_ids'],
            'mode' => $bookingData['mode'],
            'conditions' => $bookingData['conditions'] ?? null,
            'situation' => $bookingData['situation'] ?? null,
            'booking_date' => $reservation->booking_date,
            'booking_time' => $reservation->booking_time,
            'subtotal' => $bookingData['subtotal'] ?? $bookingData['total_price'],
            'total_price' => $bookingData['total_price'],
            'promo_code' => $bookingData['promo_code'] ?? null,
            'discount_amount' => $bookingData['discount_amount'] ?? 0.00,
            'coins_used' => $bookingData['coins_used'] ?? 0,
            'coin_discount' => $bookingData['coin_discount'] ?? 0.00,
            'currency' => $bookingData['currency'],
            'status' => 'confirmed',
            'is_test' => $bookingData['is_test'] ?? false,
            'razorpay_order_id' => $paymentLinkId,
            'razorpay_payment_id' => $paymentId,
            'additional_info' => $bookingData['additional_info'] ?? null,
        ]);

        // Mark reservation as confirmed
        $reservation->update(['status' => 'confirmed']);

        // Deduct Coins from user balance if used
        if ($booking->coins_used > 0) {
            $user = User::find($booking->user_id);
            if ($user) {
                $user->coins = max(0, $user->coins - $booking->coins_used);
                $user->save();
            }
        }

        if (!empty($booking->promo_code)) {
            $promo = \App\Models\PromoCode::where('code', $booking->promo_code)->first();
            if ($promo) {
                $promo->incrementUsageIfAvailable();
            }
        }

            // Record Financial Transaction
            $practitioner = $booking->practitioner;

            $this->recordTransaction([
                'type' => 'booking',
                'amount' => $booking->total_price,
                'subtotal' => $booking->subtotal,
                'user_id' => $booking->user_id,
                'practitioner_id' => $practitioner->user_id ?? null,
                'booking_id' => $booking->id,
                'payment_id' => $paymentId,
                'currency' => $booking->currency ?? 'INR',
                'coins_used' => $booking->coins_used,
                'coin_discount' => $booking->coin_discount,
            ]);

            try {
                \Illuminate\Support\Facades\Mail::to($booking->user->email)->send(new \App\Mail\BookingMail($booking, 'client'));
                if ($booking->practitioner && $booking->practitioner->user) {
                    \Illuminate\Support\Facades\Mail::to($booking->practitioner->user->email)->send(new \App\Mail\BookingMail($booking, 'practitioner'));
                }
                if ($booking->translator && $booking->translator->user) {
                    \Illuminate\Support\Facades\Mail::to($booking->translator->user->email)->send(new \App\Mail\BookingMail($booking, 'translator'));
                }
            } catch (\Exception $e) {
                \Log::error('Booking Confirmation Email Error: ' . $e->getMessage());
            }

            return redirect()->route('invoice.show', $booking->invoice_no)->with('success', 'Booking confirmed!');
        } else {
            // OVERBOOKED PATH (Someone paid faster for the same slot)
            // We still create the booking but mark it as 'pending_reschedule' 
            // and notify admin to handle refund or move.
            $booking = Booking::create([
                'invoice_no' => 'ZAYA-OVB-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => $reservation->user_id,
                'profile_id' => $reservation->profile_id,
                'practitioner_type' => $reservation->practitioner_type,
                'service_ids' => $bookingData['service_ids'],
                'mode' => $bookingData['mode'],
                'conditions' => $bookingData['conditions'] ?? null,
                'situation' => $bookingData['situation'] ?? null,
                'booking_date' => $reservation->booking_date,
                'booking_time' => $reservation->booking_time,
                'subtotal' => $bookingData['subtotal'] ?? $bookingData['total_price'],
                'total_price' => $bookingData['total_price'],
                'promo_code' => $bookingData['promo_code'] ?? null,
                'discount_amount' => $bookingData['discount_amount'] ?? 0.00,
                'currency' => $bookingData['currency'],
                'status' => 'pending_reschedule', // Special status
                'is_test' => $bookingData['is_test'] ?? false,
                'razorpay_order_id' => $paymentLinkId,
                'razorpay_payment_id' => $paymentId,
                'additional_info' => $bookingData['additional_info'] ?? null,
            ]);

            \Log::warning("OVERBOOKING DETECTED for Booking ID #{$booking->id}. Payment received but slot was taken.");

            return redirect()->route('dashboard')->with('warning', 'Payment received, but this slot was just booked by someone else. Our team will contact you to reschedule or provide a full refund.');
        }
    }

    private function checkSlotAvailability($profileId, $type, $date, $time)
    {
        return !Booking::where('profile_id', $profileId)
            ->where('practitioner_type', $type)
            ->where('booking_date', $date)
            ->where('booking_time', $time)
            ->whereIn('status', ['confirmed', 'completed', 'paid'])
            ->exists();
    }

    public function fetchReferrablePractitioners(Request $request)
    {
        $roles = $request->input('roles', []);
        $query = $request->input('query');
        $bookingId = $request->input('booking_id');
        $currentUser = Auth::user();

        if (empty($roles)) return response()->json([]);

        $booking = $bookingId ? Booking::find($bookingId) : null;
        $usersQuery = User::whereIn('role', $roles)->where('status', 'active')->where('id', '!=', $currentUser->id);

        // Ensure the professional profile is also active
        $usersQuery->where(function($q) {
            $q->whereHas('practitioner', fn($sub) => $sub->where('status', 'active'))
              ->orWhereHas('doctor', fn($sub) => $sub->where('status', 'active'))
              ->orWhereHas('mindfulnessPractitioner', fn($sub) => $sub->where('status', 'active'))
              ->orWhereHas('yogaTherapist', fn($sub) => $sub->where('status', 'active'));
        });

        if ($query) $usersQuery->where('name', 'LIKE', "%{$query}%");

        // Prepare matching criteria from current booking
        $matchCriteria = [];
        if ($booking) {
            if ($booking->service_ids) {
                $serviceTitles = Service::whereIn('id', (array) $booking->service_ids)->pluck('title')->toArray();
                $matchCriteria = array_merge($matchCriteria, $serviceTitles);
            }
            if ($booking->conditions) {
                // Assuming conditions might be a string or array, normalize it
                $bookingConditions = is_array($booking->conditions) ? $booking->conditions : explode(',', (string)$booking->conditions);
                $matchCriteria = array_merge($matchCriteria, array_map('trim', $bookingConditions));
            }
        }
        $matchCriteria = array_unique(array_filter($matchCriteria));

        $users = $usersQuery->get(); // Fetch all for sorting, or we can use a more complex query

        $results = $users->map(function ($u) use ($booking, $matchCriteria) {
            $handlesAllServices = false;
            $serviceFee = 0;
            $isRecommended = false;
            $matchedExpertises = [];
            $missingServices = [];

            if ($booking && in_array($u->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist'])) {
                // 1. Check if they handle the EXACT services requested
                $requiredServiceIds = $booking->service_ids ?? [];
                $userServices = \App\Models\UserService::where('user_id', $u->id)
                    ->whereIn('service_id', $requiredServiceIds)
                    ->get();
                
                $handledServiceIds = $userServices->pluck('service_id')->toArray();
                
                if (count($requiredServiceIds) > 0) {
                    $missingServiceIds = array_diff($requiredServiceIds, $handledServiceIds);
                    if (empty($missingServiceIds)) {
                        $handlesAllServices = true;
                        $serviceFee = $userServices->sum('rate');
                    } else {
                        $missingServices = \App\Models\Service::whereIn('id', $missingServiceIds)->pluck('title')->toArray();
                    }
                }

                // 2. Check for Recommendation (Service overlap or Condition match)
                $profile = $u->profile;
                if ($profile) {
                    $practitionerExpertises = (array) ($profile->expertises_list ?? []);
                    $practitionerConditions = (array) ($profile->conditions_list ?? []);
                    
                    // Check intersection
                    $matches = array_intersect(
                        array_map('strtolower', (array)$matchCriteria),
                        array_map('strtolower', array_merge($practitionerExpertises, $practitionerConditions))
                    );

                    if (!empty($matches)) {
                        $isRecommended = true;
                        $matchedExpertises = array_values($matches);
                    }
                }
            }

            return [
                'id' => $u->id,
                'name' => $u->name,
                'role' => $u->role,
                'role_label' => str_replace('_', ' ', ucfirst($u->role)),
                'handles_service' => $handlesAllServices,
                'missing_services' => $missingServices,
                'service_fee' => $serviceFee,
                'is_recommended' => $isRecommended,
                'matched_expertises' => $matchedExpertises,
                'profile_pic' => $u->profile_pic ? (str_starts_with($u->profile_pic, 'http') ? $u->profile_pic : asset('storage/' . $u->profile_pic)) : asset('frontend/assets/profile-dummy-img.png'),
                'profile_url' => $u->profile_url,
            ];
        });

        // Sort: Recommended first
        $results = $results->sortByDesc('is_recommended')->values();

        return response()->json($results->take(10));
    }

    public function getProfessionalProfile(Request $request, User $user)
    {
        if (strtolower((string) ($user->status ?? 'active')) !== 'active') {
            return response()->json(['error' => 'User not found'], 404);
        }

        $allowedRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist', 'translator'];
        if (!in_array($user->role, $allowedRoles, true)) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $profile = $user->profile;
        if (!$profile) {
            return response()->json(['error' => 'No professional profile found'], 404);
        }

        $specialities = $this->firstNonEmptyProfileArray($profile, [
            'consultations',
            'specialization',
            'practitioner_type',
            'yoga_therapist_type',
            'fields_of_specialization',
            'areas_of_expertise',
        ]);

        $conditions = $this->firstNonEmptyProfileArray($profile, [
            'body_therapies',
            'health_conditions_treated',
            'client_concerns',
            'services_offered',
            'areas_of_expertise',
        ]);

        $services = UserService::with('service')
            ->where('user_id', $user->id)
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', true)->orWhere('status', 'active');
            })
            ->get()
            ->map(function ($us) {
                return [
                    'service_id' => $us->service_id,
                    'title' => $us->service ? $us->service->title : null,
                    'rate' => $us->rate !== null ? (float) $us->rate : null,
                    'currency' => $us->currency,
                    'duration' => $us->duration,
                ];
            })
            ->filter(fn ($s) => !empty($s['title']))
            ->values();

        $roleLabel = str_replace('_', ' ', ucfirst($user->role));
        $profilePic = $user->profile_pic
            ? (str_starts_with($user->profile_pic, 'http') ? $user->profile_pic : asset('storage/' . $user->profile_pic))
            : asset('frontend/assets/profile-dummy-img.png');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'role_label' => $roleLabel,
            'profile_pic' => $profilePic,
            // Only practitioners have a dedicated public profile page in this app.
            'profile_url' => $user->role === 'practitioner' ? $user->profile_url : null,
            'specialities' => $specialities,
            'conditions' => $conditions,
            'services' => $services,
        ]);
    }

    private function firstNonEmptyProfileArray($profile, array $keys): array
    {
        foreach ($keys as $key) {
            if (!isset($profile->{$key})) continue;

            $value = $profile->{$key};
            if ($value === null) continue;

            if (is_string($value)) $value = [$value];
            if (is_object($value) && method_exists($value, 'toArray')) $value = $value->toArray();

            $arr = array_values(array_filter((array) $value, fn ($v) => trim((string) $v) !== ''));
            if (!empty($arr)) return $arr;
        }

        return [];
    }
}
