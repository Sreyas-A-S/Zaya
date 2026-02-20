<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Practitioner;
use Illuminate\Http\Request;


class PractitionerController extends Controller
{
    public function index(){
        $practitioners = Practitioner::all();

        return response()->json([
            'status' => true,
            'data' => $practitioners
        ]);

    }
    
}