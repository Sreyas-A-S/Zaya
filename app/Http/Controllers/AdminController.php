<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Practitioner;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Package;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-view')->only(['index']);
    }

    public function index()
    {
        $user = Auth::user();
        $user->load('nationality');

        $myLanguages = [];
        if ($user->languages) {
            $langIds = is_array($user->languages) ? $user->languages : json_decode($user->languages, true);
            if ($langIds) {
                $myLanguages = Language::whereIn('id', $langIds)->get();
            }
        }

        $stats = [
            'total_users' => User::count(),
            'total_practitioners' => Practitioner::count(),
            'total_patients' => Patient::count(),
            'total_services' => Service::count(),
            'total_packages' => Package::count(),
        ];

        return view('admin.dashboard', compact('user', 'myLanguages', 'stats'));
    }

    public function profile()
    {
        $user = Auth::user();
        $user->load('nationality');

        $myLanguages = [];
        if ($user->languages) {
            $langIds = is_array($user->languages) ? $user->languages : json_decode($user->languages, true);
            if ($langIds) {
                $myLanguages = Language::whereIn('id', $langIds)->get();
            }
        }

        return view('admin.profile', compact('user', 'myLanguages'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'Password updated successfully!');
    }
}
