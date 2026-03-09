<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ServicesSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:services-page-view')->only(['index']);
        $this->middleware('permission:services-page-edit')->only(['update']);
    }

    public function index()
    {
        $language = session('locale', 'en');
        
        // Get all settings for current language
        $currentSettings = HomepageSetting::where('section', 'services_page')
            ->where('language', $language)
            ->get()
            ->keyBy('key');

        // Get all setting structure from English (default)
        $defaultSettings = HomepageSetting::where('section', 'services_page')
            ->where('language', 'en')
            ->get();

        $settings = $defaultSettings->map(function($setting) use ($currentSettings, $language) {
            if ($currentSettings->has($setting->key)) {
                return $currentSettings->get($setting->key);
            }
            
            $newSetting = $setting->replicate();
            $newSetting->language = $language;
            $newSetting->value = ($setting->type === 'image') ? $setting->value : ''; 
            return $newSetting;
        });

        return view('admin.services-settings.index', compact('settings'));
    }

    public function update(Request $request)
    { // Get current language from session (default en)


        $language = Session::get('locale', 'en');
        //   dd($language);
        $data = $request->except(['_token', 'language']);

        foreach ($data as $key => $value) {

            $setting = HomepageSetting::where('key', $key)
                ->where('language', $language)
                ->first();

            if ($setting) {

                // Validate only text fields
                if (
                    $setting->type !== 'image' &&
                    $setting->max_length &&
                    strlen($value) > $setting->max_length
                ) {

                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => "The {$key} field cannot be longer than {$setting->max_length} characters."
                        ], 422);
                    }

                    return redirect()->back()->withErrors([
                        $key => "The {$key} field cannot be longer than {$setting->max_length} characters."
                    ]);
                }

                // Handle image
                if ($setting->type === 'image' && $request->hasFile($key)) {

                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }

                    $path = $request->file($key)
                        ->store("services/{$language}", 'public');

                    $setting->update(['value' => $path]);
                } else {
                    $setting->update(['value' => $value]);
                }
            } else {
                // If setting doesn't exist for this language, create it based on English structure
                $originalSetting = HomepageSetting::where('key', $key)->where('language', 'en')->first();
                if ($originalSetting) {
                    // Handle image for new record
                    $val = $value;
                    if ($originalSetting->type === 'image' && $request->hasFile($key)) {
                        $val = $request->file($key)->store("services/{$language}", 'public');
                    }

                    HomepageSetting::create([
                        'key' => $key,
                        'value' => $val ?? '',
                        'language' => $language,
                        'type' => $originalSetting->type,
                        'section' => $originalSetting->section,
                        'max_length' => $originalSetting->max_length,
                    ]);
                }
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Services page settings updated successfully.',
                'language' => $language
            ]);
        }

        return redirect()->back()->with('success', 'Services page settings updated successfully.');
    }
}
