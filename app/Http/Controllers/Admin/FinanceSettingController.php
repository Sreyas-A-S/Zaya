<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;

class FinanceSettingController extends Controller
{
    public function index()
    {
        $settings = HomepageSetting::where('section', 'finance')->get();
        return view('admin.finance-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            $setting = HomepageSetting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Finance settings updated successfully.']);
        }

        return redirect()->back()->with('success', 'Finance settings updated successfully.');
    }
}
