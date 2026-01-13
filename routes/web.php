<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PractitionerController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register/selection', function () {
    return view('auth.register_selection');
})->name('register.selection');

Route::get('register/{type}', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('register', [RegisterController::class, 'register'])->name('register');

Route::get('register', function() {
    return redirect()->route('register.selection');
});

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');

    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::resource('doctors', App\Http\Controllers\Admin\DoctorController::class);
        Route::resource('practitioners', App\Http\Controllers\Admin\PractitionerController::class);
        Route::resource('clients', App\Http\Controllers\Admin\ClientController::class);
    });
});
