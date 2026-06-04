<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $language = session('locale', 'en');

        $defaults = [
            // EN Settings
            ['key' => 'invoice_main_title', 'value' => 'Your Session has been Booked!', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'en'],
            ['key' => 'invoice_subtitle', 'value' => 'Please check your email for confirmation and further instruction.', 'type' => 'textarea', 'section' => 'invoice_settings', 'max_length' => 255, 'language' => 'en'],
            ['key' => 'invoice_client_name', 'value' => 'Client Name', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_client_id', 'value' => 'Client ID', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_client_dob', 'value' => 'DOB', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_client_location', 'value' => 'Location', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_sessions_title', 'value' => 'Sessions', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_service_col', 'value' => 'Service', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_date_col', 'value' => 'Date', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_time_col', 'value' => 'Time', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_no_services', 'value' => 'No services listed.', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'en'],
            ['key' => 'invoice_total_amount', 'value' => 'Total Amount', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_practitioner_title', 'value' => 'Sessions with Practitioner', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'en'],
            ['key' => 'invoice_footer_thanks', 'value' => 'Thanks for Booking!', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'en'],
            ['key' => 'invoice_footer_queries', 'value' => 'For more queries', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'en'],
            ['key' => 'invoice_btn_share', 'value' => 'Share', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],
            ['key' => 'invoice_btn_download', 'value' => 'Download', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'en'],

            // FR Settings
            ['key' => 'invoice_main_title', 'value' => 'Votre session a été réservée !', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'fr'],
            ['key' => 'invoice_subtitle', 'value' => 'Veuillez vérifier votre e-mail pour obtenir une confirmation et des instructions supplémentaires.', 'type' => 'textarea', 'section' => 'invoice_settings', 'max_length' => 255, 'language' => 'fr'],
            ['key' => 'invoice_client_name', 'value' => 'Nom du client', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_client_id', 'value' => 'ID client', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_client_dob', 'value' => 'Date de naissance', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_client_location', 'value' => 'Lieu', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_sessions_title', 'value' => 'Sessions', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_service_col', 'value' => 'Service', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_date_col', 'value' => 'Date', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_time_col', 'value' => 'Heure', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_no_services', 'value' => 'Aucun service répertorié.', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'fr'],
            ['key' => 'invoice_total_amount', 'value' => 'Montant total', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_practitioner_title', 'value' => 'Sessions avec le praticien', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'fr'],
            ['key' => 'invoice_footer_thanks', 'value' => 'Merci pour votre réservation !', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'fr'],
            ['key' => 'invoice_footer_queries', 'value' => 'Pour plus de questions', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 100, 'language' => 'fr'],
            ['key' => 'invoice_btn_share', 'value' => 'Partager', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
            ['key' => 'invoice_btn_download', 'value' => 'Télécharger', 'type' => 'text', 'section' => 'invoice_settings', 'max_length' => 50, 'language' => 'fr'],
        ];

        // Seed defaults if they don't exist
        foreach ($defaults as $def) {
            $exists = HomepageSetting::where('key', $def['key'])
                ->where('language', $def['language'])
                ->exists();
            if (!$exists) {
                HomepageSetting::create($def);
            }
        }

        $sections = [
            'invoice_settings'
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

        return view('admin.invoice-settings.index', compact('settings'));
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

                if ($setting->type !== 'image') {
                    $setting->update(['value' => $value]);
                }
            } else {
                // Create setting for other language
                $originalSetting = HomepageSetting::where('key', $key)
                    ->where('language', 'en')
                    ->first();

                if ($originalSetting) {
                    $val = is_string($value) ? $value : $originalSetting->value;

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
                'message' => 'Invoice settings updated successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Invoice settings updated successfully.');
    }
}
