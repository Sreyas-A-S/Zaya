<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Language;
use Illuminate\Support\Facades\Auth;

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

        return view('admin.dashboard', compact('user', 'myLanguages'));
    }
}
