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
        $finalPayable = $request->test_mode ? 1.00 : max(0, $subtotal - $promoDiscount - $coinDiscount);
        
        \Log::info('Final Totals:', [
            'subtotal' => $subtotal,
            'promo_discount' => $promoDiscount,
            'coin_discount' => $coinDiscount,
            'final_payable' => $finalPayable
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
                'total_price' => $subtotal,
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
                'user_id' => $booking->user_id,
                'practitioner_id' => $practitioner->user_id,
                'booking_id' => $booking->id,
                'payment_id' => 'ZERO_PAYMENT',
                'currency' => $currency,
                'coins_used' => $coinsUsed,
                'coin_discount' => $coinDiscount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking confirmed! (Discount applied)',
                'redirect_url' => route('invoice.show', $booking->invoice_no),
            ]);
        }

        // ... (check availability)

        // --- Razorpay Payment Link Creation ---
        $paymentUrl = null;
        $razorpayKey = config('services.razorpay.key');
        $razorpaySecret = config('services.razorpay.secret');

        if ($razorpayKey && $razorpaySecret) {
            $verifySsl = config('services.razorpay.verify_ssl');
            
            // If test mode is active, we force currency to INR and amount to 1.00
            $gatewayAmount = $request->test_mode ? 1.00 : $finalPayable;
            $gatewayCurrency = $request->test_mode ? 'INR' : $currency;

            try {
                $response = Http::withBasicAuth($razorpayKey, $razorpaySecret)
                    ->withOptions(['verify' => $verifySsl])
                    ->post('https://api.razorpay.com/v1/payment_links', [
                        'amount' => (int)(round($gatewayAmount, 2) * 100),
                        'currency' => $gatewayCurrency,
                        'accept_partial' => false,
                        'description' => 'Booking Session - Zaya Wellness',
                        'customer' => [
                            'name' => $user->name,
                            'email' => $user->email,
                            'contact' => $user->phone ?? '',
                        ],
                        'notify' => [
                            'sms' => true,
                            'email' => true,
                        ],
                        'callback_url' => route('bookings.payment.callback'),
                        'callback_method' => 'get',
                        'notes' => [
                            'practitioner_id' => $practitioner->id,
                            'practitioner_type' => $practitioner->getMorphClass(),
                            'booking_date' => $request->booking_date,
                            'booking_time' => $request->booking_time,
                            'service_ids' => implode(',', $request->service_ids),
                            'mode' => $request->mode,
                            'conditions' => json_encode($request->conditions),
                            'situation' => $request->situation,
                            'total_price' => $request->total_price,
                            'promo_code' => $request->promo_code,
                            'discount_amount' => $promoDiscount,
                            'coins_used' => $coinsUsed,
                            'coin_discount' => $coinDiscount,
                            'currency' => $currency,
                            'additional_info' => json_encode($request->additional_info)
                            ]
                            ]);

                if ($response->successful()) {
                    $paymentUrl = $response->json('short_url');
                } else {
                    \Log::error('Razorpay API Error:', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'payload' => [
                            'amount' => (int)(round($finalPayable, 2) * 100),
                            'currency' => $currency,
                        ]
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Razorpay Connection Exception: ' . $e->getMessage());
            }
        }

        if (!$paymentUrl) {
            // If payment fails or is not configured, we might allow manual confirmation if admin allows
            // or just return error.
            return response()->json(['success' => false, 'message' => 'Payment gateway unavailable.'], 503);
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
        $notes = $pData['notes'];

        // CRITICAL: Final availability check now that money is received
        $isAvailable = $this->checkSlotAvailability(
            $notes['practitioner_id'], 
            $notes['practitioner_type'] ?? 'practitioner',
            $notes['booking_date'], 
            $notes['booking_time']
        );

        if ($isAvailable) {
            // SUCCESS PATH
            $booking = Booking::create([
                'invoice_no' => 'ZAYA-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => Auth::id(),
                'profile_id' => $notes['practitioner_id'],
                'practitioner_type' => $notes['practitioner_type'] ?? 'practitioner',
                'service_ids' => explode(',', $notes['service_ids']),
                'mode' => $notes['mode'],
                'conditions' => isset($notes['conditions']) ? json_decode($notes['conditions'], true) : null,
                'situation' => $notes['situation'] ?? null,
                'booking_date' => $notes['booking_date'],
                'booking_time' => $notes['booking_time'],
                'total_price' => $notes['total_price'],
                'promo_code' => $notes['promo_code'] ?? null,
                'discount_amount' => $notes['discount_amount'] ?? 0.00,
                'coins_used' => $notes['coins_used'] ?? 0,
                'coin_discount' => $notes['coin_discount'] ?? 0.00,
                'currency' => $notes['currency'],
                'status' => 'confirmed',
                'razorpay_order_id' => $paymentLinkId,
                'razorpay_payment_id' => $paymentId,
                'additional_info' => isset($notes['additional_info']) ? json_decode($notes['additional_info'], true) : null,
            ]);

            // Deduct Coins from user balance if used
            if ($booking->coins_used > 0) {
                $user = Auth::user();
                $user->coins = max(0, $user->coins - $booking->coins_used);
                $user->save();
            }

            if (!empty($notes['promo_code'])) {
                $promo = \App\Models\PromoCode::where('code', $notes['promo_code'])->first();
                if ($promo) {
                    $promo->incrementUsageIfAvailable();
                }
            }

            // Record Financial Transaction
            $practitioner = $booking->practitioner;

            $this->recordTransaction([
                'type' => 'booking',
                'amount' => $booking->total_price,
                'user_id' => $booking->user_id,
                'practitioner_id' => $practitioner->user_id ?? null,
                'booking_id' => $booking->id,
                'payment_id' => $paymentId,
                'currency' => $booking->currency ?? 'INR',
                'coins_used' => $booking->coins_used,
                'coin_discount' => $booking->coin_discount,
            ]);

            return redirect()->route('invoice.show', $booking->invoice_no)->with('success', 'Booking confirmed!');
        } else {
            // OVERBOOKED PATH (Someone paid faster for the same slot)
            // We still create the booking but mark it as 'pending_reschedule' 
            // and notify admin to handle refund or move.
            $booking = Booking::create([
                'invoice_no' => 'ZAYA-OVB-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => Auth::id(),
                'profile_id' => $notes['practitioner_id'],
                'practitioner_type' => $notes['practitioner_type'] ?? 'practitioner',
                'service_ids' => explode(',', $notes['service_ids']),
                'mode' => $notes['mode'],
                'conditions' => isset($notes['conditions']) ? json_decode($notes['conditions'], true) : null,
                'situation' => $notes['situation'] ?? null,
                'booking_date' => $notes['booking_date'],
                'booking_time' => $notes['booking_time'],
                'total_price' => $notes['total_price'],
                'promo_code' => $notes['promo_code'] ?? null,
                'discount_amount' => $notes['discount_amount'] ?? 0.00,
                'currency' => $notes['currency'],
                'status' => 'pending_reschedule', // Special status
                'razorpay_order_id' => $paymentLinkId,
                'razorpay_payment_id' => $paymentId,
                'additional_info' => isset($notes['additional_info']) ? json_decode($notes['additional_info'], true) : null,
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
            $handlesService = false;
            $serviceFee = 0;
            $isRecommended = false;
            $matchedExpertises = [];

            if ($booking && in_array($u->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist'])) {
                // 1. Check if they handle the EXACT services requested
                $userServices = \App\Models\UserService::where('user_id', $u->id)->whereIn('service_id', $booking->service_ids ?? [])->get();
                if ($userServices->isNotEmpty()) {
                    $handlesService = true;
                    $serviceFee = $userServices->sum('rate');
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
                'handles_service' => $handlesService,
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
