<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ContactusController extends Controller
{
    public function index()
    {
        $language = $this->getCurrentLocale();
        $this->ensureContactDefaults();

        // 1. Fetch all your keys starting with 'contact_'
        $settings = HomepageSetting::where('key', 'like', 'contact_%')
            ->where('language', $language)
            ->get();

        $groupedSettings = [
            'hero_banner' => $settings->filter(fn($s) => Str::contains($s->key, 'banner')),
            'contact_information' => $settings->filter(fn($s) => Str::contains($s->key, 'info')),
            'message_form' => $settings->filter(fn($s) => Str::contains($s->key, 'form')),
            'support_section' => $settings->filter(fn($s) => Str::contains($s->key, 'support')),
            'faqs' => $settings->filter(fn($s) => Str::contains($s->key, 'faq')),
        ];
        // 3. Return the grouped settings!
        return view('admin.contact-us.index', ['settings' => $groupedSettings]);
    }

    public function update(Request $request)
    {
        $language = $this->getCurrentLocale();
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = HomepageSetting::where('key', $key)
                ->where('language', $language)
                ->first();
            if ($setting) {
                // Validate max length if applicable
                if ($setting->max_length && is_string($value) && strlen($value) > $setting->max_length) {
                    return response()->json(['success' => false, 'message' => "The {$key} field cannot be longer than {$setting->max_length} characters."], 422);
                }

                if ($setting->type === 'image' && $request->hasFile($key)) {
                    // Delete old image if exists
                    if ($setting->value && \Illuminate\Support\Facades\Storage::disk('public')->exists($setting->value)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($setting->value);
                    }
                    $path = $request->file($key)->store('contact', 'public');
                    $setting->update(['value' => $path]);
                } else if ($setting->type !== 'image') {
                    $setting->update(['value' => $value]);
                }
            } else {
                HomepageSetting::create([
                    'key' => $key,
                    'value' => is_string($value) ? $value : null,
                    'language' => $language,
                    'type' => 'text',
                    'section' => 'contact',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Contact settings updated successfully.'
        ]);
    }

    private function ensureContactDefaults(): void
    {
        $defaults = [
            // Hero Banner
            [
                'key' => 'contact_banner_title',
                'value' => 'Get in Touch',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
            ],
            [
                'key' => 'contact_banner_subtitle',
                'value' => 'We are here to help you on your wellness journey.',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 100,
            ],
            [
                'key' => 'contact_banner_image',
                'value' => 'frontend/assets/contact-banner.png',
                'type' => 'image',
                'section' => 'contact',
            ],

            // Contact Information
            [
                'key' => 'contact_info_address',
                'value' => '123 Wellness Street, Holistic City, IN 45678',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200,
            ],
            [
                'key' => 'contact_info_email',
                'value' => 'support@zaya.com',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
            ],
            [
                'key' => 'contact_info_phone',
                'value' => '+1 (234) 567-8900',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 20,
            ],
            [
                'key' => 'contact_info_working_hours',
                'value' => 'Mon - Fri: 9:00 AM - 6:00 PM',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
            ],

            // Message Form
            [
                'key' => 'contact_form_title',
                'value' => 'Send Us a Message',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
            ],
            [
                'key' => 'contact_form_subtitle',
                'value' => 'Fill out the form below and we will get back to you shortly.',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200,
            ],

            // Support Desk
            [
                'key' => 'contact_support_title',
                'value' => 'Professional Support',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
            ],
            [
                'key' => 'contact_support_description',
                'value' => 'Need technical assistance or have questions about our services?',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200,
            ],
            [
                'key' => 'contact_support_button_text',
                'value' => 'Visit Help Center',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 30,
            ],

            // FAQs Section
            [
                'key' => 'contact_faq_title',
                'value' => 'Frequently Asked Questions',
                'type' => 'text',
                'section' => 'contact',
                'max_length' => 50,
            ],
            [
                'key' => 'contact_faq_subtitle',
                'value' => 'Find quick answers to common questions about Zaya.',
                'type' => 'textarea',
                'section' => 'contact',
                'max_length' => 200,
            ],
        ];

        $languages = array_values(array_unique(['en', 'fr', $this->getCurrentLocale()]));

        foreach ($defaults as $default) {
            $baseValue = $this->getBaseContactValue($default['key'], $default['value']);

            foreach ($languages as $language) {
                $existing = HomepageSetting::where('key', $default['key'])
                    ->where('language', $language)
                    ->first();

                if (! $existing) {
                    $createData = $default;
                    $createData['value'] = $baseValue;
                    $createData['language'] = $language;
                    HomepageSetting::create($createData);
                    continue;
                }

                $updates = [];
                if (! $existing->type && isset($default['type'])) {
                    $updates['type'] = $default['type'];
                }
                if (! $existing->section && isset($default['section'])) {
                    $updates['section'] = $default['section'];
                }
                if ($existing->max_length === null && array_key_exists('max_length', $default)) {
                    $updates['max_length'] = $default['max_length'];
                }

                if (! empty($updates)) {
                    $existing->update($updates);
                }
            }
        }
    }

    private function getBaseContactValue(string $key, string $fallback): string
    {
        $enValue = HomepageSetting::where('key', $key)
            ->where('language', 'en')
            ->value('value');

        if (! empty($enValue)) {
            return $enValue;
        }

        $nullValue = HomepageSetting::where('key', $key)
            ->whereNull('language')
            ->value('value');

        return ! empty($nullValue) ? $nullValue : $fallback;
    }

    private function getCurrentLocale(): string
    {
        return Session::get('locale', 'en');
    }
}
