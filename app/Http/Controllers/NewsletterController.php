<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address.'
            ], 422);
        }

        $subscription = Newsletter::where('email', $request->email)->first();

        if ($subscription) {
            if ($subscription->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already subscribed!'
                ]);
            } else {
                $subscription->is_active = true;
                $subscription->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Welcome back! Your subscription has been reactivated.'
                ]);
            }
        }

        Newsletter::create([
            'email' => $request->email,
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!'
        ]);
    }
}
