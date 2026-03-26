<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegistrationFeeController extends Controller
{
    public function callback(Request $request)
    {
        // For now, just log the callback; reconciliation can be expanded later.
        Log::info('Registration fee callback', $request->all());

        return response()->json(['status' => 'ok']);
    }
}
