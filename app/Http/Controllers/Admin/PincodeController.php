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
            'pincode' => 'nullable|digits:6',
            'zipcode' => 'nullable|digits:6',
        ]);

        $zipcode = $request->input('zipcode', $request->input('pincode'));
        if (!$zipcode) {
            return response()->json([
                'status' => false,
                'message' => 'Zipcode is required',
            ], 422);
        }

        // Store new preferred session key, and keep legacy key for compatibility.
        session([
            'global_zipcode' => $zipcode,
            'global_pincode' => $zipcode,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Zipcode ' . $zipcode . ' saved successfully',
                'zipcode' => $zipcode,
            ]);
        }

        return redirect()->back()->with('success', 'Zipcode saved successfully');
    }

    // AJAX method
    public function getPincode()
    {
        $zipcode = session('global_zipcode', session('global_pincode'));

        return response()->json([
            'status' => true,
            'zipcode' => $zipcode,
            'pincode' => $zipcode, // legacy alias
        ]);
    }

    public function destroy()
    {
        session()->forget('global_zipcode');
        session()->forget('global_pincode');

        return response()->json([
            'status' => true,
            'message' => 'Zipcode cleared successfully'
        ]);
    }
}
