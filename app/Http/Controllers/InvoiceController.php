<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display the specified invoice.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $booking = Booking::with(['user.patient', 'practitioner', 'translator'])->findOrFail($id);
        
        // Ensure the current user can view this invoice
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        return view('invoice.view-invoice-index', compact('booking'));
    }
}
