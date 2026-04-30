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
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to view your invoice.');
        }

        $isRegistration = str_starts_with($invoice_no, 'ZAYA-REG');
        $relations = ['user.patient'];
        if (!$isRegistration) {
            $relations = array_merge($relations, ['practitioner.user', 'translator.user']);
        }

        $booking = Booking::with($relations)
            ->where('invoice_no', $invoice_no)
            ->firstOrFail();
        
        // Ensure user can only see their own invoice unless admin
        if (auth()->user()->role !== 'admin' && $booking->user_id !== auth()->id()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'For security purposes, please log in with the account associated with this invoice.');
        }
        
        return view('invoice.index', compact('booking'));
    }

    /**
     * Download the invoice without auth using a token.
     */
    public function download(Request $request, $invoice_no)
    {
        $token = $request->query('token');
        
        $isRegistration = str_starts_with($invoice_no, 'ZAYA-REG');
        $relations = ['user.patient'];
        if (!$isRegistration) {
            $relations = array_merge($relations, ['practitioner.user', 'translator.user']);
        }

        $booking = Booking::with($relations)
            ->where('invoice_no', $invoice_no)
            ->where('download_token', $token)
            ->firstOrFail();

        return view('invoice.download', compact('booking'));
    }

    /**
     * Preview the invoice with dummy data or the latest booking.
     */
    public function preview()
    {
        // Get the latest booking and check if it's registration or consultation
        $tempBooking = Booking::latest()->first();
        $isRegistration = $tempBooking && str_starts_with($tempBooking->invoice_no, 'ZAYA-REG');
        
        $relations = ['user.patient'];
        if (!$isRegistration) {
            $relations = array_merge($relations, ['practitioner.user', 'translator.user']);
        }

        $booking = Booking::with($relations)->latest()->first();
        
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
