<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard', compact('user'));
    }

    public function bookings(Request $request)
    {
        $user = Auth::user();
        $bookings = \App\Models\Booking::with(['practitioner.user'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return view('partials.bookings-table', compact('user', 'bookings'))->render();
        }

        return view('bookings', compact('user', 'bookings'));
    }
}
