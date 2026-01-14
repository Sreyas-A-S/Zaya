<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PractitionerController;
use App\Http\Controllers\Admin\MasterDataController;
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

Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');

    Route::resource('doctors', App\Http\Controllers\Admin\DoctorController::class);
    Route::post('doctors/{id}/status', [App\Http\Controllers\Admin\DoctorController::class, 'updateStatus'])->name('doctors.status');
    Route::resource('practitioners', App\Http\Controllers\Admin\PractitionerController::class);
    Route::resource('clients', App\Http\Controllers\Admin\ClientController::class);
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
    Route::get('roles/{role}/permissions', [App\Http\Controllers\Admin\RoleController::class, 'showPermissions'])->name('roles.permissions');
    Route::post('roles/{role}/permissions', [App\Http\Controllers\Admin\RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

    // Master Data
    Route::get('master-data/{type}', [MasterDataController::class, 'index'])->name('master-data.index');
    Route::post('master-data/{type}', [MasterDataController::class, 'store'])->name('master-data.store');
    Route::put('master-data/{type}/{id}', [MasterDataController::class, 'update'])->name('master-data.update');
    Route::delete('master-data/{type}/{id}', [MasterDataController::class, 'destroy'])->name('master-data.destroy');
});
