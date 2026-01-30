<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Storage;

class HomepageSettingController extends Controller
{
    public function index()
    {
        $settings = HomepageSetting::all()->groupBy('section');
        return view('admin.homepage-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = HomepageSetting::where('key', $key)->first();
            if ($setting) {
                if ($setting->type === 'image' && $request->hasFile($key)) {
                    // Delete old image if exists
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    $path = $request->file($key)->store('homepage', 'public');
                    $setting->update(['value' => $path]);
                } else {
                    $setting->update(['value' => $value]);
                }
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Homepage settings updated successfully.']);
        }

        return redirect()->back()->with('success', 'Homepage settings updated successfully.');
    }
}
