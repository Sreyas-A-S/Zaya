<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function redirectToRazorpay(Request $request)
    {
        $booking = Booking::with('user')->findOrFail($request->booking_id);

        $key = env('RAZORPAY_KEY');
        $secret = env('RAZORPAY_SECRET');

        if (!$key || !$secret) {
            return back()->with('error', 'Razorpay API keys are not configured.');
        }

        try {
            // Create a Razorpay Payment Link
            $response = Http::withBasicAuth($key, $secret)
                ->post('https://api.razorpay.com/v1/payment_links', [
                    'amount' => $booking->total_price * 100, // Amount in paise
                    'currency' => 'INR', // Or EUR if supported by their account, but typically INR for Razorpay
                    'accept_partial' => false,
                    'description' => "Booking for " . $booking->practitioner->user->name,
                    'customer' => [
                        'name' => $booking->user->name,
                        'email' => $booking->user->email,
                        'contact' => $booking->user->phone ?? '',
                    ],
                    'notify' => [
                        'sms' => true,
                        'email' => true,
                    ],
                    'reminder_enable' => true,
                    'notes' => [
                        'booking_id' => $booking->id,
                    ],
                    'callback_url' => route('payment.callback'),
                    'callback_method' => 'get',
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $booking->update([
                    'razorpay_order_id' => $data['id'], // Storing payment link id as order id for reference
                ]);

                return redirect($data['short_url']);
            }

            Log::error('Razorpay Error: ' . $response->body());
            return back()->with('error', 'Could not initiate payment with Razorpay.');

        } catch (\Exception $e) {
            Log::error('Razorpay Exception: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while connecting to Razorpay.');
        }
    }

    public function handleCallback(Request $request)
    {
        // Handle payment success/failure from Razorpay callback
        $paymentId = $request->razorpay_payment_id;
        $status = $request->razorpay_payment_link_status;

        if ($status === 'paid') {
            // Update booking status
            // You might need to find the booking via the razorpay_order_id (payment link id)
            $booking = Booking::where('razorpay_order_id', $request->razorpay_payment_link_id)->first();
            if ($booking) {
                $booking->update([
                    'status' => 'paid',
                    'razorpay_payment_id' => $paymentId,
                ]);
                return redirect()->route('book-session')->with('success', 'Payment successful! Your booking is confirmed.');
            }
        }

        return redirect()->route('book-session')->with('error', 'Payment failed or was cancelled.');
    }
}
