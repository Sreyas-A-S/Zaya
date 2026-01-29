<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    //
    public function index()
    {
        $practitioners = \App\Models\Practitioner::with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('index', compact('practitioners'));
    }

    public function comingSoon()
    {
        return view('coming-soon');
    }

    public function aboutUs()
    {
        return view('about');
    }

    public function services()
    {
        return view('services');
    }

    public function practitionerDetail()
    {
        return view('practitioner-detail');
    }

    public function zayaLogin()
    {
        return view('zaya-login');
    }
}
