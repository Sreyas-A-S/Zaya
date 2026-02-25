<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;
use Illuminate\Support\Str;

class ContactusController extends Controller
{
      public function index()
    {
        // 1. Fetch all your keys starting with 'contact_'
        $settings = HomepageSetting::where('key', 'like', 'contact_%')->get();

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
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = HomepageSetting::where('key', $key)->first();
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
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Contact settings updated successfully.'
        ]);
    }
}
