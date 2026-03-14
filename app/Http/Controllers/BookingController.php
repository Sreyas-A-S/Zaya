<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Practitioner;
use App\Models\Service;
use App\Models\Translator;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully!',
            'booking' => $booking
        ]);
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
}
