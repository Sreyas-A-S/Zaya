<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FinancialTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class RegistrationFeeController extends Controller
{
    use FinancialTrait;

    public function callback(Request $request)
    {
        $payload = $request->all();
        Log::info('Registration fee callback received', $payload);

        $paymentLinkId = $request->razorpay_payment_link_id;
        $status = $request->razorpay_payment_link_status;
        $paymentId = $request->razorpay_payment_id;
        
        if ($status === 'paid') {
            // Get user from payment link notes via Razorpay API or local lookup
            // For security and accuracy, we fetch the latest status from Razorpay
            $razorpayKey = config('services.razorpay.key');
            $razorpaySecret = config('services.razorpay.secret');
            
            try {
                $response = Http::withBasicAuth($razorpayKey, $razorpaySecret)
                    ->get("https://api.razorpay.com/v1/payment_links/$paymentLinkId");
                
                if ($response->successful()) {
                    $linkData = $response->json();
                    $notes = $linkData['notes'] ?? [];
                    $userId = $notes['user_id'] ?? null;
                    
                    if ($userId) {
                        $user = User::find($userId);
                        if ($user) {
                            // Generate Invoice No for Registration
                            $invoiceNo = 'ZAYA-REG-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4));

                            // Record the transaction
                            $this->recordTransaction([
                                'type' => 'registration',
                                'user_id' => $user->id,
                                'practitioner_id' => null, // No expert share for registration
                                'amount' => $linkData['amount'] / 100, // standard to subunits conversion
                                'currency' => $linkData['currency'],
                                'payment_id' => $paymentId,
                                'status' => 'completed'
                            ]);

                            // Create a dummy booking record to serve as an invoice
                            $booking = \App\Models\Booking::create([
                                'invoice_no' => $invoiceNo,
                                'user_id' => $user->id,
                                'profile_id' => 0,
                                'practitioner_type' => 'registration_fee',
                                'service_ids' => [],
                                'mode' => 'online',
                                'booking_date' => now(),
                                'booking_time' => now()->format('H:i'),
                                'total_price' => $linkData['amount'] / 100,
                                'currency' => $linkData['currency'],
                                'status' => 'paid',
                                'razorpay_payment_id' => $paymentId
                            ]);
                            
                            Log::info("Transaction and Registration Invoice recorded for user $userId.");

                            // Auto-login the user so they can view the invoice (protected by auth middleware)
                            \Illuminate\Support\Facades\Auth::login($user);

                            return redirect()->route('invoice.show', $invoiceNo)->with('success', 'Payment successful! Welcome to Zaya Wellness.');
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error processing registration fee transaction: " . $e->getMessage());
            }
             
             return redirect()->route('zaya-login')->with('success', 'Payment successful! You can now login to your account.');
        }

        return redirect()->route('zaya-login')->with('error', 'Payment was not completed. Please contact support.');
    }
}
