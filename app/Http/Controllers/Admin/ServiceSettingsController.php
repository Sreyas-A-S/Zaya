<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;

class ServiceSettingsController extends Controller
{
    /**
     * Display Service Settings Page
     */
    public function index()
    {
        $language = session('locale', 'en');
        // Fetch only INDIVIDUAL service detail page global settings
        $settings = HomepageSetting::where('section', 'service_detail_page')
            ->where('language', $language)
            ->get();

        return view('admin.service-settings.index', compact('settings'));
    }

    /**
     * Update Service Settings
     */
    public function update(Request $request)
    {
        try {

            $language = session('locale', 'en');
            foreach ($request->except('_token') as $key => $value) {

                $setting = HomepageSetting::where('key', $key)
                                  ->where('section', 'service_detail_page')
                                  ->where('language', $language)
                                  ->first();

                if ($setting) {

                    // Handle File Upload
                    if ($request->hasFile($key)) {

                        $file = $request->file($key);
                        $filename = time() . '_' . $file->getClientOriginalName();

                        // Move to public path and store relative path
                        $file->move(public_path('uploads/service-settings'), $filename);
                        $setting->value = 'uploads/service-settings/' . $filename;

                    } else {

                        $setting->value = $value;
                    }

                    $setting->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Service Settings updated successfully.'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}