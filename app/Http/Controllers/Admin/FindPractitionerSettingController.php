<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Storage;

class FindPractitionerSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:home-page-view')->only(['index']);
        $this->middleware('permission:home-page-edit')->only(['update']);
    }

    public function index()
    {
        $language = session('locale', 'en');
        $section = 'find_practitioner_page';
        
        // Get all settings for current language in this section
        $currentSettings = HomepageSetting::where('section', $section)
            ->where('language', $language)
            ->get()
            ->keyBy('key');

        // Get all setting structure from English (default)
        $defaultSettings = HomepageSetting::where('section', $section)
            ->where('language', 'en')
            ->get();

        $settings = $defaultSettings->map(function($setting) use ($currentSettings, $language) {
            if ($currentSettings->has($setting->key)) {
                return $currentSettings->get($setting->key);
            }
            
            // Return a "dummy" model instance for the form
            $newSetting = $setting->replicate();
            $newSetting->language = $language;
            $newSetting->value = ''; 
            return $newSetting;
        });

        return view('admin.find-practitioner-settings.index', compact('settings'));
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
                    return response()->json(['success' => false, 'message' => "The field cannot be longer than {$setting->max_length} characters."], 422);
                }

                if ($setting->type === 'image' && $request->hasFile($key)) {
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    $path = $request->file($key)->store('homepage', 'public');
                    $setting->update(['value' => $path]);
                } else if ($setting->type !== 'image') {
                    $setting->update(['value' => $value]);
                }
            } else {
                $originalSetting = HomepageSetting::where('key', $key)->where('language', 'en')->first();
                if ($originalSetting) {
                    $val = is_string($value) ? $value : $originalSetting->value;
                    if ($originalSetting->type === 'image' && $request->hasFile($key)) {
                        $val = $request->file($key)->store('homepage', 'public');
                    }

                    HomepageSetting::create([
                        'key' => $key,
                        'value' => $val,
                        'type' => $originalSetting->type,
                        'section' => $originalSetting->section,
                        'max_length' => $originalSetting->max_length,
                        'language' => $language,
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Find Practitioner page settings updated successfully.']);
    }
}
