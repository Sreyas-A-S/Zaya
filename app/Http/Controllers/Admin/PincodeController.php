<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PincodeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pincode' => 'required|digits:6'
        ]);

        session(['global_pincode' => $request->pincode]);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Pincode ' . $request->pincode . ' saved successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Pincode saved successfully');
    }

    // AJAX method
    public function getPincode()
    {
        $pincode = session('global_pincode');

        return response()->json([
            'status' => true,
            'pincode' => $pincode
        ]);
    }

    public function destroy()
    {
        session()->forget('global_pincode');

        return response()->json([
            'status' => true,
            'message' => 'Pincode cleared successfully'
        ]);
    }
}