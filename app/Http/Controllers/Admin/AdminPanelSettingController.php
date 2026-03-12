<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AdminPanelSettingController extends Controller
{
    public function index()
    {
        $language = Session::get('locale', 'en');

        $currentSettings = HomepageSetting::where('section', 'admin_panel')
            ->where('language', $language)
            ->get()
            ->keyBy('key');

        $defaultSettings = HomepageSetting::where('section', 'admin_panel')
            ->where('language', 'en')
            ->get();

        $settings = $defaultSettings->map(function ($setting) use ($currentSettings, $language) {
            if ($currentSettings->has($setting->key)) {
                return $currentSettings->get($setting->key);
            }

            $newSetting = $setting->replicate();
            $newSetting->language = $language;
            $newSetting->value = ($setting->type === 'image') ? $setting->value : '';
            return $newSetting;
        });

        return view('admin.admin-panel-settings.index', compact('settings'));
    }

    public function edit()
    {
        // Not used
    }

    public function update(\Illuminate\Http\Request $request)
    {
        $language = Session::get('locale', 'en');
        $data = $request->except(['_token', 'language']);

        foreach ($data as $key => $value) {
            $setting = HomepageSetting::where('key', $key)
                ->where('language', $language)
                ->first();

            if ($setting) {
                if (
                    $setting->type !== 'image' &&
                    $setting->max_length &&
                    is_string($value) &&
                    strlen($value) > $setting->max_length
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => "The {$key} field cannot be longer than {$setting->max_length} characters."
                    ], 422);
                }

                if ($setting->type === 'image' && $request->hasFile($key)) {
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    $path = $request->file($key)->store("admin-panel/{$language}", 'public');
                    $setting->update(['value' => $path]);
                } else if ($setting->type !== 'image') {
                    $setting->update(['value' => $value]);
                }
            } else {
                $originalSetting = HomepageSetting::where('key', $key)->where('language', 'en')->first();
                if ($originalSetting) {
                    $val = $value;
                    if ($originalSetting->type === 'image' && $request->hasFile($key)) {
                        $val = $request->file($key)->store("admin-panel/{$language}", 'public');
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

        return response()->json([
            'success' => true,
            'message' => 'Admin panel settings updated successfully.',
            'language' => $language,
        ]);
    }

    public function changePassword(\Illuminate\Http\Request $request)
    {
        // Not used
    }
}
