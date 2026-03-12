<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilePageSettingController extends Controller
{
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $user->load('nationality');

        $myLanguages = [];
        if ($user->languages) {
            $langIds = is_array($user->languages) ? $user->languages : json_decode($user->languages, true);
            if ($langIds) {
                $myLanguages = \App\Models\Language::whereIn('id', $langIds)->get();
            }
        }

        $countries = \App\Models\Country::all();

        return view('admin.profile', compact('user', 'myLanguages', 'countries'));
    }

    public function edit()
    {
        // Not used, modal handles edit in profile view
    }

    public function update(\Illuminate\Http\Request $request)
    {
        // Not used, handled via AdminController@updateProfile
    }

    public function changePassword(\Illuminate\Http\Request $request)
    {
        // Not used, handled via AdminController@updatePassword
    }
}
