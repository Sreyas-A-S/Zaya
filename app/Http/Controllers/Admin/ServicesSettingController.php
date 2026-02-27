<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ServicesSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:services-page-view')->only(['index']);
        $this->middleware('permission:services-page-edit')->only(['update']);
    }

    public function index()
    {
        $language = session('locale', 'en');
        $settings = HomepageSetting::where('section', 'services_page')
            ->where('language', $language)
            ->get();
        return view('admin.services-settings.index', compact('settings'));
    }

    public function update(Request $request)
    { // Get current language from session (default en)


        $language = Session::get('locale', 'en');
        //   dd($language);
        $data = $request->except(['_token', 'language']);

        foreach ($data as $key => $value) {

            $setting = HomepageSetting::where('key', $key)
                ->where('language', $language)
                ->first();

            if ($setting) {

                // Validate only text fields
                if (
                    $setting->type !== 'image' &&
                    $setting->max_length &&
                    strlen($value) > $setting->max_length
                ) {

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

                // Handle image
                if ($setting->type === 'image' && $request->hasFile($key)) {

                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }

                    $path = $request->file($key)
                        ->store("services/{$language}", 'public');

                    $setting->update(['value' => $path]);
                } else {
                    $setting->update(['value' => $value]);
                }
            } else {

                HomepageSetting::create([
                    'key' => $key,
                    'value' => $value,
                    'language' => $language,
                    'type' => 'text'
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Services page settings updated successfully.',
                'language' => $language
            ]);
        }

        return redirect()->back()->with('success', 'Services page settings updated successfully.');
    }
}
