<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogCacheController;
use App\Http\Controllers\Api\PractitionerController;

Route::post('/clear-blog-cache', [BlogCacheController::class, 'clear'])->name('api.clear-blog-cache');

//Practitioners List 
Route::get('/practitioners', [PractitionerController::class, 'index']);
