<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;


class ContactusController extends Controller
{
      public function index()
    {
        $Contact = HomepageSetting::where('key', 'like', 'contact_%')->get();

        return view('admin.contact-us.index', compact('Contact'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {

            HomepageSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Contact settings updated successfully.'
        ]);
    }
}
