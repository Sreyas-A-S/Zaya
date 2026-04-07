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
            'discount_amount' => 'nullable|numeric|min:0'
        ]);

        $practitioner = Practitioner::with('user')->findOrFail($request->practitioner_id);
        $currency = $request->currency ?? 'INR';

        // ... (check availability)

        // --- Razorpay Payment Link Creation ---
        $paymentUrl = null;
        $razorpayKey = config('services.razorpay.key');
        $razorpaySecret = config('services.razorpay.secret');

        if ($razorpayKey && $razorpaySecret) {
            try {
                $response = Http::withBasicAuth($razorpayKey, $razorpaySecret)
                    ->post('https://api.razorpay.com/v1/payment_links', [
                        'amount' => (int)($request->total_price * 100),
                        'currency' => $currency,
                        'accept_partial' => false,
                        'description' => 'Booking Session - Zaya Wellness',
                        'customer' => [
                            'name' => Auth::user()->name,
                            'email' => Auth::user()->email,
                            'contact' => Auth::user()->phone ?? '',
                        ],
                        'notify' => [
                            'sms' => true,
                            'email' => true,
                        ],
                        'callback_url' => route('booking.callback'),
                        'callback_method' => 'get',
                        'notes' => [
                            'practitioner_id' => $request->practitioner_id,
                            'booking_date' => $request->booking_date,
                            'booking_time' => $request->booking_time,
                            'service_ids' => implode(',', $request->service_ids),
                            'mode' => $request->mode,
                            'conditions' => $request->conditions,
                            'total_price' => $request->total_price,
                            'promo_code' => $request->promo_code,
                            'discount_amount' => $request->discount_amount,
                            'currency' => $currency
                        ]
                    ]);

                if ($response->successful()) {
                    $paymentUrl = $response->json('short_url');
                }
            } catch (\Exception $e) {
                \Log::error('Razorpay Connection Error: ' . $e->getMessage());
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
            $notes['booking_date'], 
            $notes['booking_time']
        );

        if ($isAvailable) {
            // SUCCESS PATH
            $booking = Booking::create([
                'invoice_no' => 'ZAYA-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => Auth::id(),
                'practitioner_id' => $notes['practitioner_id'],
                'service_ids' => explode(',', $notes['service_ids']),
                'mode' => $notes['mode'],
                'conditions' => $notes['conditions'] ?? null,
                'booking_date' => $notes['booking_date'],
                'booking_time' => $notes['booking_time'],
                'total_price' => $notes['total_price'],
                'promo_code' => $notes['promo_code'] ?? null,
                'discount_amount' => $notes['discount_amount'] ?? 0.00,
                'currency' => $notes['currency'],
                'status' => 'confirmed',
                'razorpay_order_id' => $paymentLinkId,
                'razorpay_payment_id' => $paymentId,
            ]);

            if (!empty($notes['promo_code'])) {
                $promo = \App\Models\PromoCode::where('code', $notes['promo_code'])->first();
                if ($promo) {
                    $promo->increment('used_count');
                }
            }

            // Record Financial Transaction
            $practitioner = $booking->practitioner;
            $countryId = null;
            try {
                $client = $booking->user;
                $raw = $client ? ($client->national_id ?? null) : null;
                if (is_array($raw)) {
                    foreach ($raw as $v) { if (is_numeric($v)) { $countryId = (int) $v; break; } }
                } elseif (is_numeric($raw)) {
                    $countryId = (int) $raw;
                } elseif (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    if (is_array($decoded)) {
                        foreach ($decoded as $v) { if (is_numeric($v)) { $countryId = (int) $v; break; } }
                    }
                }
            } catch (\Throwable $e) {}

            $this->recordTransaction([
                'type' => 'booking',
                'amount' => $booking->total_price,
                'user_id' => $booking->user_id,
                'practitioner_id' => $practitioner->user_id ?? null,
                'booking_id' => $booking->id,
                'payment_id' => $paymentId,
                'currency' => $booking->currency ?? 'INR',
                'country_id' => $countryId,
            ]);

            return redirect()->route('invoice.show', $booking->invoice_no)->with('success', 'Booking confirmed!');
        } else {
            // OVERBOOKED PATH (Someone paid faster for the same slot)
            // We still create the booking but mark it as 'pending_reschedule' 
            // and notify admin to handle refund or move.
            $booking = Booking::create([
                'invoice_no' => 'ZAYA-OVB-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => Auth::id(),
                'practitioner_id' => $notes['practitioner_id'],
                'service_ids' => explode(',', $notes['service_ids']),
                'mode' => $notes['mode'],
                'conditions' => $notes['conditions'] ?? null,
                'booking_date' => $notes['booking_date'],
                'booking_time' => $notes['booking_time'],
                'total_price' => $notes['total_price'],
                'promo_code' => $notes['promo_code'] ?? null,
                'discount_amount' => $notes['discount_amount'] ?? 0.00,
                'currency' => $notes['currency'],
                'status' => 'pending_reschedule', // Special status
                'razorpay_order_id' => $paymentLinkId,
                'razorpay_payment_id' => $paymentId,
            ]);

            \Log::warning("OVERBOOKING DETECTED for Booking ID #{$booking->id}. Payment received but slot was taken.");

            return redirect()->route('dashboard')->with('warning', 'Payment received, but this slot was just booked by someone else. Our team will contact you to reschedule or provide a full refund.');
        }
    }

    private function checkSlotAvailability($practitionerId, $date, $time)
    {
        return !Booking::where('practitioner_id', $practitionerId)
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

        $users = $usersQuery->limit(5)->get();

        $results = $users->map(function ($u) use ($booking) {
            $handlesService = false; $serviceFee = 0;
            if ($booking && in_array($u->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist'])) {
                $userServices = \App\Models\UserService::where('user_id', $u->id)->whereIn('service_id', $booking->service_ids ?? [])->get();
                if ($userServices->isNotEmpty()) {
                    $handlesService = true;
                    $serviceFee = $userServices->sum('rate');
                }
            }
            return [
                'id' => $u->id, 'name' => $u->name, 'role' => $u->role,
                'role_label' => str_replace('_', ' ', ucfirst($u->role)),
                'handles_service' => $handlesService, 'service_fee' => $serviceFee,
                'profile_pic' => $u->profile_pic ? (str_starts_with($u->profile_pic, 'http') ? $u->profile_pic : asset('storage/' . $u->profile_pic)) : asset('frontend/assets/profile-dummy-img.png'),
                'profile_url' => $u->profile_url,
            ];
        });

        return response()->json($results);
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
