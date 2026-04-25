<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegistrationFeeController extends Controller
{
    public function callback(Request $request)
    {
        $payload = $request->all();
        Log::info('Registration fee callback received', $payload);

        $paymentLinkId = $request->razorpay_payment_link_id;
        $status = $request->razorpay_payment_link_status;
        
        if ($status === 'paid') {
             // In a production environment, you should verify the signature here
             // using Razorpay's SDK or a manual HMAC check if the signature was provided.
             // Payment Links callbacks usually include signature params in the query string 
             // when redirected back to the callback_url.
             
             Log::info("Payment link $paymentLinkId marked as PAID.");
             
             return redirect()->route('zaya-login')->with('success', 'Payment successful! You can now login to your account.');
        }

        return redirect()->route('zaya-login')->with('error', 'Payment was not completed. Please contact support.');
    }
}
