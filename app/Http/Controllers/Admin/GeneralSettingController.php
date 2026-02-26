<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;

class GeneralSettingController extends Controller
{


    public function index()
    {
        $settings = HomepageSetting::where('section', 'general')->get();
        return view('admin.general-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = HomepageSetting::where('key', $key)->first();
            if ($setting) {
                // Validate max length if applicable
                if ($setting->max_length && strlen($value) > $setting->max_length) {
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => "The " . str_replace('_', ' ', $key) . " field cannot be longer than {$setting->max_length} characters."], 422);
                    }
                    return redirect()->back()->withErrors(["{$key}" => "The " . str_replace('_', ' ', $key) . " field cannot be longer than {$setting->max_length} characters."]);
                }

                $setting->update(['value' => $value]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Site settings updated successfully.']);
        }

        return redirect()->back()->with('success', 'Site settings updated successfully.');
    }
}
