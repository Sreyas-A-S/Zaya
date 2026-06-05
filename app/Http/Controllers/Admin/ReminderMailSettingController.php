<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageSetting;

class ReminderMailSettingController extends Controller
{
    public function index()
    {
        $setting = HomepageSetting::where('key', 'global_reminder_lead_times')->first();
        if ($setting) {
            $leadTimes = json_decode($setting->value, true) ?: [1440, 60, 10];
        } else {
            // Fallback: check if old settings exist
            $adv = HomepageSetting::where('key', 'reminder_mail_advance')->first();
            $mid = HomepageSetting::where('key', 'reminder_mail_one_hour')->first();
            $fin = HomepageSetting::where('key', 'reminder_mail_final')->first();
            if ($adv || $mid || $fin) {
                $leadTimes = [];
                if ($adv) $leadTimes[] = (int) $adv->value;
                if ($mid) $leadTimes[] = (int) $mid->value;
                if ($fin) $leadTimes[] = (int) $fin->value;
                $leadTimes = array_values(array_unique($leadTimes));
                rsort($leadTimes);
            } else {
                $leadTimes = [1440, 60, 10];
            }
        }

        return view('admin.reminder-mail-settings.index', compact('leadTimes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'reminder_lead_times'   => 'required|array|min:1|max:3',
            'reminder_lead_times.*' => 'required|integer|min:5|max:10080',
        ]);

        $leadTimes = array_map('intval', $request->input('reminder_lead_times'));
        $leadTimes = array_values(array_unique($leadTimes));
        rsort($leadTimes);

        HomepageSetting::updateOrCreate(
            ['key' => 'global_reminder_lead_times'],
            ['value' => json_encode($leadTimes), 'type' => 'array', 'section' => 'general']
        );

        // Also sync legacy settings for safety/fallback compatibility
        if (isset($leadTimes[0])) {
            HomepageSetting::updateOrCreate(
                ['key' => 'reminder_mail_advance'],
                ['value' => (string) $leadTimes[0], 'type' => 'number', 'section' => 'general']
            );
        }
        if (isset($leadTimes[1])) {
            HomepageSetting::updateOrCreate(
                ['key' => 'reminder_mail_one_hour'],
                ['value' => (string) $leadTimes[1], 'type' => 'number', 'section' => 'general']
            );
        }
        if (isset($leadTimes[2])) {
            HomepageSetting::updateOrCreate(
                ['key' => 'reminder_mail_final'],
                ['value' => (string) $leadTimes[2], 'type' => 'number', 'section' => 'general']
            );
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Reminder mail settings updated successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Reminder mail settings updated successfully.');
    }
}
