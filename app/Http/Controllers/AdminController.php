<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-view')->only(['index']);
    }

    public function index()
    {
        return view('admin.dashboard');
    }
}
