<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageUploadTrait;

class GallerySettingController extends Controller
{
    use ImageUploadTrait;

    public function __construct()
    {
        $this->middleware('permission:gallery-page-view')->only(['index']);
        $this->middleware('permission:gallery-page-edit')->only(['update']);
    }

    public function index()
    {
        $language = session('locale', 'en');
        
        // Get all settings for current language
        $currentSettings = HomepageSetting::where('section', 'gallery_page')
            ->where('language', $language)
            ->get()
            ->keyBy('key');

        // Get all setting structure from English (default)
        $defaultSettings = HomepageSetting::where('section', 'gallery_page')
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

        return view('admin.gallery-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $language = Session::get('locale', 'en');
        $data = $request->except(['_token', 'language']);

        foreach ($data as $key => $value) {
            // Skip cropped data keys as they are handled with their respective original keys
            if (str_ends_with($key, '_cropped')) continue;

            $setting = HomepageSetting::where('key', $key)
                ->where('language', $language)
                ->first();

            if ($setting) {
                if ($setting->type !== 'image' && $setting->max_length && strlen($value ?? '') > $setting->max_length) {
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'message' => "The " . str_replace('_', ' ', $key) . " field cannot be longer than {$setting->max_length} characters."], 422);
                    }
                    return redirect()->back()->withErrors([$key => "The " . str_replace('_', ' ', $key) . " field cannot be longer than {$setting->max_length} characters."]);
                }

                if ($setting->type === 'image') {
                    $croppedKey = $key . '_cropped';
                    if ($request->filled($croppedKey)) {
                        if ($setting->value && !str_contains($setting->value, 'frontend/assets') && Storage::disk('public')->exists($setting->value)) {
                            Storage::disk('public')->delete($setting->value);
                        }
                        $path = $this->uploadBase64($request->input($croppedKey), "gallery/{$language}");
                        $setting->update(['value' => $path]);
                    } elseif ($request->hasFile($key)) {
                        if ($setting->value && !str_contains($setting->value, 'frontend/assets') && Storage::disk('public')->exists($setting->value)) {
                            Storage::disk('public')->delete($setting->value);
                        }
                        $path = $request->file($key)->store("gallery/{$language}", 'public');
                        $setting->update(['value' => $path]);
                    }
                } else if ($setting->type !== 'image') {
                    $setting->update(['value' => $value]);
                }
            } else {
                $originalSetting = HomepageSetting::where('key', $key)->where('language', 'en')->first();
                if ($originalSetting) {
                    $val = $value;
                    if ($originalSetting->type === 'image') {
                        $croppedKey = $key . '_cropped';
                        if ($request->filled($croppedKey)) {
                            $val = $this->uploadBase64($request->input($croppedKey), "gallery/{$language}");
                        } elseif ($request->hasFile($key)) {
                            $val = $request->file($key)->store("gallery/{$language}", 'public');
                        } else {
                            $val = $originalSetting->value;
                        }
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
            return response()->json(['success' => true, 'message' => 'Gallery page settings updated successfully.']);
        }

        return redirect()->back()->with('success', 'Gallery page settings updated successfully.');
    }
}
