<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageUploadTrait;

class HomepageSettingController extends Controller
{
    use ImageUploadTrait;

    public function __construct()
    {
        $this->middleware('permission:home-page-view')->only(['index']);
        $this->middleware('permission:home-page-edit')->only(['update']);
    }

    public function index()
    {
        $language = session('locale', 'en');
        
        // Get all settings for current language
        $currentSettings = HomepageSetting::whereNotIn('section', ['about_page', 'services_page', 'contact', 'general', 'gallery_page'])
            ->where('language', $language)
            ->get()
            ->keyBy('key');

        $defaultSettings = HomepageSetting::whereNotIn('section', ['about_page', 'services_page', 'contact', 'general', 'gallery_page'])
            ->where('language', 'en')
            ->get();

        $globalKeys = ['blog_post_1_link', 'blog_post_2_link', 'blog_post_3_link'];

        $settings = $defaultSettings->map(function($setting) use ($currentSettings, $language, $globalKeys) {
            $isGlobal = in_array($setting->key, $globalKeys);
            
            if ($isGlobal) {
                // For global settings, always use the 'en' record
                $globalSetting = $setting->replicate();
                $globalSetting->is_global = true;
                // If we are editing 'en', use current values, otherwise use en values
                if ($language === 'en' && $currentSettings->has($setting->key)) {
                    $globalSetting->value = $currentSettings->get($setting->key)->value;
                } else {
                    $globalSetting->value = $setting->value;
                }
                return $globalSetting;
            }

            if ($currentSettings->has($setting->key)) {
                return $currentSettings->get($setting->key);
            }
            
            // Return a "dummy" model instance for the form
            $newSetting = $setting->replicate();
            $newSetting->language = $language;
            $newSetting->value = ($setting->type === 'image') ? $setting->value : ''; 
            return $newSetting;
        })->groupBy('section');

        return view('admin.homepage-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $language = session('locale', 'en');
        $data = $request->except('_token');
        $globalKeys = ['blog_post_1_link', 'blog_post_2_link', 'blog_post_3_link'];

        foreach ($data as $key => $value) {
            // Skip cropped data keys
            if (str_ends_with($key, '_cropped')) continue;

            $isGlobal = in_array($key, $globalKeys);
            $targetLanguage = $isGlobal ? 'en' : $language;

            $setting = HomepageSetting::where('key', $key)
                ->where('language', $targetLanguage)
                ->first();
            if ($setting) {
                // Validate max length if applicable
                if ($setting->max_length && is_string($value) && strlen($value) > $setting->max_length) {
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => "The " . str_replace('_', ' ', $key) . " field cannot be longer than {$setting->max_length} characters."], 422);
                    }
                    return redirect()->back()->withErrors(["{$key}" => "The {$key} field cannot be longer than {$setting->max_length} characters."]);
                }

                if ($setting->type === 'image') {
                    $croppedKey = $key . '_cropped';
                    if ($request->filled($croppedKey)) {
                        if ($setting->value && !str_contains($setting->value, 'frontend/assets') && Storage::disk('public')->exists($setting->value)) {
                            Storage::disk('public')->delete($setting->value);
                        }
                        $path = $this->uploadBase64($request->input($croppedKey), 'homepage');
                        $setting->update(['value' => $path]);
                    } elseif ($request->hasFile($key)) {
                        if ($setting->value && !str_contains($setting->value, 'frontend/assets') && Storage::disk('public')->exists($setting->value)) {
                            Storage::disk('public')->delete($setting->value);
                        }
                        $path = $request->file($key)->store('homepage', 'public');
                        $setting->update(['value' => $path]);
                    }
                } else if ($setting->type !== 'image') {
                    $setting->update(['value' => $value]);
                }
            } else {
                // If setting doesn't exist for this language, we might want to create it if it exists for 'en'
                $originalSetting = HomepageSetting::where('key', $key)->where('language', 'en')->first();
                if ($originalSetting) {
                    $val = is_string($value) ? $value : $originalSetting->value;
                    
                    if ($originalSetting->type === 'image') {
                        $croppedKey = $key . '_cropped';
                        if ($request->filled($croppedKey)) {
                            $val = $this->uploadBase64($request->input($croppedKey), 'homepage');
                        } elseif ($request->hasFile($key)) {
                            $val = $request->file($key)->store('homepage', 'public');
                        } else {
                            $val = $originalSetting->value;
                        }
                    }

                    HomepageSetting::create([
                        'key' => $key,
                        'value' => $val ?? '',
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
