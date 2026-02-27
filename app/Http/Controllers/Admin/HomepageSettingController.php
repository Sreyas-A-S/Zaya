<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Storage;

class HomepageSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:home-page-view')->only(['index']);
        $this->middleware('permission:home-page-edit')->only(['update']);
    }

    public function index()
    {
        $language = session('locale', 'en');
        $settings = HomepageSetting::where('section', '!=', 'about_page')
            ->where('language', $language)
            ->get()
            ->groupBy('section');
        return view('admin.homepage-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $language = session('locale', 'en');
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = HomepageSetting::where('key', $key)
                ->where('language', $language)
                ->first();
            if ($setting) {
                // Validate max length if applicable
                if ($setting->max_length && is_string($value) && strlen($value) > $setting->max_length) {
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
                    $path = $request->file($key)->store('homepage', 'public');
                    $setting->update(['value' => $path]);
                } else if ($setting->type !== 'image') {
                    $setting->update(['value' => $value]);
                }
            } else {
                // If setting doesn't exist for this language, we might want to create it if it exists for 'en'
                $originalSetting = HomepageSetting::where('key', $key)->where('language', 'en')->first();
                if ($originalSetting) {
                    HomepageSetting::create([
                        'key' => $key,
                        'value' => is_string($value) ? $value : $originalSetting->value,
                        'type' => $originalSetting->type,
                        'section' => $originalSetting->section,
                        'max_length' => $originalSetting->max_length,
                        'language' => $language,
                    ]);
                }
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Homepage settings updated successfully.']);
        }

        return redirect()->back()->with('success', 'Homepage settings updated successfully.');
    }
}
