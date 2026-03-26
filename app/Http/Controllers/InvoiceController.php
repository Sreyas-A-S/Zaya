<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HomepageSetting;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices for admin.
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'practitioner'])->latest()->paginate(10);
        return view('admin.invoices.index', compact('bookings'));
    }

    /**
     * Display the specified invoice.
     *
     * @param  string  $invoice_no
     * @return \Illuminate\View\View
     */
    public function show($invoice_no)
    {
        $booking = Booking::with(['user.patient', 'practitioner', 'translator'])
            ->where('invoice_no', $invoice_no)
            ->firstOrFail();
        
        // Ensure the current user can view this invoice if they are the client who booked it or an admin
        $user = auth()->user();
        $isAuthorized = $user->hasPermission('dashboard-view') || $booking->user_id === $user->id;

        if (!$isAuthorized) {
            abort(403);
        }

        return view('invoice.index', compact('booking'));
    }

    /**
     * Preview the invoice with dummy data or the latest booking.
     */
    public function preview()
    {
        $booking = Booking::with(['user.patient', 'practitioner', 'translator'])->latest()->first();
        
        if (!$booking) {
            // Create a dummy booking for preview if none exists
            $booking = (object)[
                'id' => 1,
                'invoice_no' => 'ZAYA-' . date('Ymd') . '-0001',
                'booking_date' => now(),
                'booking_time' => '10:00 AM',
                'total_price' => 100.00,
                'currency' => config('app.currency', 'INR'),
                'status' => 'Paid',
                'service_ids' => [1, 2],
                'user' => (object)[
                    'id' => 1,
                    'name' => 'Quinn Emerson',
                    'profile_photo_url' => 'https://i.pravatar.cc/150?img=47',
                    'patient' => (object)[
                        'dob' => now()->subYears(28),
                        'city_state' => 'London, UK'
                    ]
                ],
                'practitioner' => (object)[
                    'id' => 1,
                    'first_name' => 'Dr. Lily',
                    'last_name' => 'Marie',
                    'profile_photo_path' => null,
                    'other_modalities' => ['Art Therapist'],
                    'city_state' => 'The Heritage Grove PTP Nagar, Kowdiar Road, Trivandrum, Kerala.'
                ]
            ];
        }

        return view('invoice.index', compact('booking'));
    }
}
