<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientPannelSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $language = session('locale', 'en');

        $sections = [
            'client_panel_general',
            'client_panel_identity',
            'client_panel_consultations',
            'client_panel_documents',
            'client_panel_transactions',
            'client_panel_reviews',
            'client_panel_gdpr',
            'client_panel_sidebar'
        ];

        // Get all settings for current language
        $currentSettings = HomepageSetting::where('language', $language)
            ->whereIn('section', $sections)
            ->get()
            ->keyBy('key');

        // Get all setting structure from English (default)
        $defaultSettings = HomepageSetting::where('language', 'en')
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

        return view('admin.client-pannel-settings.index', compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $language = session('locale', 'en');
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = HomepageSetting::where('key', $key)
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
                    $path = $request->file($key)->store('client_panel', 'public');
                    $setting->update(['value' => $path]);
                } else if ($setting->type !== 'image') {
                    $setting->update(['value' => $value]);
                }
            } else {
                // Create setting for other language
                $originalSetting = HomepageSetting::where('key', $key)
                    ->where('language', 'en')
                    ->first();

                if ($originalSetting) {
                    $val = is_string($value) ? $value : $originalSetting->value;
                    if ($originalSetting->type === 'image' && $request->hasFile($key)) {
                        $val = $request->file($key)->store('client_panel', 'public');
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

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Client panel settings updated successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Client panel settings updated successfully.');
    }
}
