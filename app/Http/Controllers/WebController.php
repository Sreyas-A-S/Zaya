<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    //
    public function index() {
        return view('index');
    }

    public function comingSoon() {
        return view('coming-soon');
    }

    public function aboutUs() {
        return view('about');
    }
}
