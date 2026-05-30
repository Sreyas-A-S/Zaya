<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;

class ReminderMailSettingController extends Controller
{
    public function index()
    {
        // 1. Advance reminder stored in minutes (default 1440 = 24 hours)
        $advanceSetting = HomepageSetting::where('key', 'reminder_mail_advance')->first();
        $advanceMinutes = $advanceSetting ? (int) $advanceSetting->value : 1440;
        $advanceHours   = (int) round($advanceMinutes / 60);

        // 2. 1-Hour Reminder stored in minutes (default 60 = 1 hour)
        $oneHourSetting = HomepageSetting::where('key', 'reminder_mail_one_hour')->first();
        $oneHourMinutes = $oneHourSetting ? (int) $oneHourSetting->value : 60;
        $oneHourHours   = (int) round($oneHourMinutes / 60);

        // 3. Final reminder stored in minutes (default 10)
        $finalSetting   = HomepageSetting::where('key', 'reminder_mail_final')->first();
        $finalMinutes   = $finalSetting ? (int) $finalSetting->value : 10;

        return view('admin.reminder-mail-settings.index', compact('advanceHours', 'oneHourHours', 'finalMinutes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'reminder_mail_advance_hr'   => 'required|integer|min:1|max:168',
            'reminder_mail_one_hour_hr'  => 'required|integer|min:1|max:168',
            'reminder_mail_final_min'    => 'required|integer|min:1|max:1440',
        ]);

        $advanceMinutes = (int) $request->input('reminder_mail_advance_hr') * 60;
        $oneHourMinutes = (int) $request->input('reminder_mail_one_hour_hr') * 60;
        $finalMinutes   = (int) $request->input('reminder_mail_final_min');

        // Validation: Advance > 1-Hour
        if ($oneHourMinutes >= $advanceMinutes) {
            $msg = 'The 1-hour reminder timing must be shorter than the advance reminder timing.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->withErrors(['reminder_mail_one_hour_hr' => $msg]);
        }

        // Validation: 1-Hour > Final
        if ($finalMinutes >= $oneHourMinutes) {
            $msg = 'The final reminder timing must be shorter than the 1-hour reminder timing.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->withErrors(['reminder_mail_final_min' => $msg]);
        }

        HomepageSetting::updateOrCreate(
            ['key' => 'reminder_mail_advance'],
            ['value' => (string) $advanceMinutes, 'type' => 'number', 'section' => 'general']
        );

        HomepageSetting::updateOrCreate(
            ['key' => 'reminder_mail_one_hour'],
            ['value' => (string) $oneHourMinutes, 'type' => 'number', 'section' => 'general']
        );

        HomepageSetting::updateOrCreate(
            ['key' => 'reminder_mail_final'],
            ['value' => (string) $finalMinutes, 'type' => 'number', 'section' => 'general']
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Reminder mail settings updated successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Reminder mail settings updated successfully.');
    }
}
