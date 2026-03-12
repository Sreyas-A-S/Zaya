<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FooterPageSetting;
use Illuminate\Support\Facades\Storage;

class FooterPageController extends Controller
{

    public function index()
    {
        $language = session('locale', 'en');

        $sections = ['newsletter', 'general', 'headings', 'social_links', 'quick_links', 'legal'];

        // Get all settings for current language
        $currentSettings = FooterPageSetting::where('language', $language)
            ->whereIn('section', $sections)
            ->get()
            ->keyBy('key');

        // Get all setting structure from English (default)
        $defaultSettings = FooterPageSetting::where('language', 'en')
            ->whereIn('section', $sections)
            ->get();

        $settings = $defaultSettings->map(function ($setting) use ($currentSettings, $language) {

            if ($currentSettings->has($setting->key)) {
                return $currentSettings->get($setting->key);
            }

            // Dummy model instance for form
            $newSetting = $setting->replicate();
            $newSetting->language = $language;
            $newSetting->value = ''; // Always empty if not exists for this language

            return $newSetting;

        })->groupBy('section');

        return view('admin.footer-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $language = session('locale', 'en');
        $data = $request->except('_token');

        foreach ($data as $key => $value) {

            $setting = FooterPageSetting::where('key', $key)
                ->where('language', $language)
                ->first();

            if ($setting) {

                // Validate max length
                if ($setting->max_length && is_string($value) && strlen($value) > $setting->max_length) {

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

                // Image upload
                if ($setting->type === 'image' && $request->hasFile($key)) {

                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }

                    $path = $request->file($key)->store('homepage', 'public');

                    $setting->update([
                        'value' => $path
                    ]);
                }
                else if ($setting->type !== 'image') {

                    $setting->update([
                        'value' => $value
                    ]);
                }

            } else {

                // Create setting for other language
                $originalSetting = FooterPageSetting::where('key', $key)
                    ->where('language', 'en')
                    ->first();

                if ($originalSetting) {

                    $val = is_string($value) ? $value : $originalSetting->value;

                    if ($originalSetting->type === 'image' && $request->hasFile($key)) {
                        $val = $request->file($key)->store('homepage', 'public');
                    }

                    FooterPageSetting::create([
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

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Footer page settings updated successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Footer page settings updated successfully.');
    }
}