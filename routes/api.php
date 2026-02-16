<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogCacheController;

Route::post('/clear-blog-cache', [BlogCacheController::class, 'clear'])->name('api.clear-blog-cache');
