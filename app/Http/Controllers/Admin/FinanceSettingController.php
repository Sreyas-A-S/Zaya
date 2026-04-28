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
        $countryCode = session('admin_country', 'all');
        if (!$countryCode) $countryCode = 'all';
        
        if ($countryCode !== 'all') {
            $countryCode = strtoupper($countryCode);
        }

        $defaults = [
            ['key' => 'client_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'client_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'practitioner_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'practitioner_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'doctor_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'doctor_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'client_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'practitioner_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'doctor_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'mindfulness_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'mindfulness_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'mindfulness_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'yoga_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'yoga_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'yoga_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'translator_registration_fee', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'translator_registration_fee_currency', 'value' => 'EUR', 'type' => 'text', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'translator_registration_fee_enabled', 'value' => '1', 'type' => 'boolean', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'company_booking_commission', 'value' => '10', 'type' => 'number', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'company_referral_commission', 'value' => '0', 'type' => 'number', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
            ['key' => 'practitioner_referral_commission', 'value' => '5', 'type' => 'number', 'section' => 'finance', 'language' => 'en', 'country_code' => 'all'],
        ];

        foreach ($defaults as $def) {
            $exists = HomepageSetting::where('key', $def['key'])
                ->where('language', 'en')
                ->where('country_code', 'all')
                ->exists();
            if (!$exists) {
                HomepageSetting::create($def);
            }
        }

        // 1. Fetch settings for current country and language
        $currentSettings = HomepageSetting::where('section', 'finance')
            ->where('language', $language)
            ->where('country_code', $countryCode)
            ->get()
            ->keyBy('key');

        // 2. Fetch "all" country settings for this language as fallback structure
        $fallbackLanguageSettings = collect();
        if ($countryCode !== 'all') {
            $fallbackLanguageSettings = HomepageSetting::where('section', 'finance')
                ->where('language', $language)
                ->where('country_code', 'all')
                ->get()
                ->keyBy('key');
        }

        // 3. Fetch English + "all" settings as base structure
        $defaultSettings = HomepageSetting::where('section', 'finance')
            ->where('language', 'en')
            ->where('country_code', 'all')
            ->get();

        $settings = $defaultSettings->map(function($setting) use ($currentSettings, $fallbackLanguageSettings, $language, $countryCode) {
            if ($currentSettings->has($setting->key)) {
                return $currentSettings->get($setting->key);
            }
            
            // Replicate from fallback language if exists, else from English
            $source = $fallbackLanguageSettings->get($setting->key) ?? $setting;
            
            $newSetting = $source->replicate();
            $newSetting->language = $language;
            $newSetting->country_code = $countryCode;
            return $newSetting;
        });

        $mappedCurrency = null;
        if ($countryCode !== 'all') {
            $mappedCurrency = config('currencies.country_to_currency.' . $countryCode);
        }

        return view('admin.finance-settings.index', compact('settings', 'countryCode', 'mappedCurrency'));
    }

    public function update(Request $request)
    {
        $language = session('locale', 'en');
        $countryCode = session('admin_country', 'all');
        if ($countryCode !== 'all') {
            $countryCode = strtoupper($countryCode);
        }
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = HomepageSetting::where('key', $key)
                ->where('language', $language)
                ->where('country_code', $countryCode)
                ->first();

            if ($setting) {
                $setting->update(['value' => $value]);
            } else {
                // Find source setting to replicate structure (try current language/all first, then en/all)
                $originalSetting = HomepageSetting::where('key', $key)
                    ->where('language', $language)
                    ->where('country_code', 'all')
                    ->first();
                
                if (!$originalSetting) {
                    $originalSetting = HomepageSetting::where('key', $key)
                        ->where('language', 'en')
                        ->where('country_code', 'all')
                        ->first();
                }
                
                if ($originalSetting) {
                    HomepageSetting::create([
                        'key' => $key,
                        'value' => $value,
                        'language' => $language,
                        'country_code' => $countryCode,
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
