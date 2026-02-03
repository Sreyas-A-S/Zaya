<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Storage;

class AboutSettingController extends Controller
{
    public function index()
    {
        $settings = HomepageSetting::where('section', 'about_page')->get();
        return view('admin.about-settings.index', compact('settings'));
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
                        return response()->json(['success' => false, 'message' => "The {$key} field cannot be longer than {$setting->max_length} characters."], 422);
                    }
                    return redirect()->back()->withErrors(["{$key}" => "The {$key} field cannot be longer than {$setting->max_length} characters."]);
                }

                if ($setting->type === 'image' && $request->hasFile($key)) {
                    // Delete old image if exists
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    $path = $request->file($key)->store('about', 'public');
                    $setting->update(['value' => $path]);
                } else {
                    $setting->update(['value' => $value]);
                }
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'About Us settings updated successfully.']);
        }

        return redirect()->back()->with('success', 'About Us settings updated successfully.');
    }
}
