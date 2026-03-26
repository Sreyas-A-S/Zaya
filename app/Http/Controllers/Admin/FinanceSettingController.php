<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;

class FinanceSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:other-fees-view')->only('index');
        $this->middleware('permission:other-fees-edit')->only('update');
    }

    public function index()
    {
        $language = session('locale', 'en');

        $defaults = [
            ['key' => 'client_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'client_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'practitioner_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'practitioner_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'doctor_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'doctor_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'client_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'practitioner_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'doctor_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'mindfulness_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'mindfulness_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'mindfulness_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'yoga_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'yoga_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'yoga_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'translator_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'translator_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en'],
            ['key' => 'translator_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en'],
        ];

        foreach ($defaults as $def) {
            $exists = HomepageSetting::where('key', $def['key'])
                ->where('language', 'en')
                ->exists();
            if (!$exists) {
                HomepageSetting::create($def);
            }
        }

        // 1. Fetch current language settings
        $currentSettings = HomepageSetting::where('section', 'finance')
            ->where('language', $language)
            ->get()
            ->keyBy('key');

        // 2. Fetch English settings as structure
        $defaultSettings = HomepageSetting::where('section', 'finance')
            ->where('language', 'en')
            ->get();

        $settings = $defaultSettings->map(function($setting) use ($currentSettings, $language) {
            if ($currentSettings->has($setting->key)) {
                return $currentSettings->get($setting->key);
            }
            
            $newSetting = $setting->replicate();
            $newSetting->language = $language;
            $newSetting->value = ''; 
            return $newSetting;
        });

        return view('admin.finance-settings.index', compact('settings'));
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
                $setting->update(['value' => $value]);
            } else {
                // Replicate from 'en' if it exists
                $originalSetting = HomepageSetting::where('key', $key)
                    ->where('language', 'en')
                    ->first();
                
                if ($originalSetting) {
                    HomepageSetting::create([
                        'key' => $key,
                        'value' => $value,
                        'language' => $language,
                        'type' => $originalSetting->type,
                        'section' => $originalSetting->section,
                        'max_length' => $originalSetting->max_length,
                    ]);
                }
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Finance settings updated successfully.']);
        }

        return redirect()->back()->with('success', 'Finance settings updated successfully.');
    }
}
