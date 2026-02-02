<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Storage;

class ServicesSettingController extends Controller
{
    public function index()
    {
        $settings = HomepageSetting::where('section', 'services_page')->get();
        return view('admin.services-settings.index', compact('settings'));
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
                    $path = $request->file($key)->store('services', 'public');
                    $setting->update(['value' => $path]);
                } else {
                    $setting->update(['value' => $value]);
                }
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Services page settings updated successfully.',
                'path' => $path ?? null
            ]);
        }

        return redirect()->back()->with('success', 'Services page settings updated successfully.');
    }
}
