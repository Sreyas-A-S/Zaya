<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PractitionerController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\MindfulnessPractitionerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TranslatorController;
use App\Http\Controllers\Admin\YogaTherapistController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebController;
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

Route::get('register', function () {
    return redirect()->route('register.selection');
});

// Public Master Data Quick Add (for registration forms)
Route::post('master-data/quick-add/{type}', [MasterDataController::class, 'store'])->name('master-data.quick-add');

Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/index', [WebController::class, 'index'])->name('index');
Route::get('/coming-soon', [WebController::class, 'comingSoon'])->name('coming-soon');
Route::get('/about-us', [WebController::class, 'aboutUs'])->name('about-us');
Route::get('/services', [WebController::class, 'services'])->name('services');
Route::get('/practitioner-detail', [WebController::class, 'practitionerDetail'])->name('practitioner-detail');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::resource('doctors', DoctorController::class);
    Route::post('doctors/{id}/status', [DoctorController::class, 'updateStatus'])->name('doctors.status');
    Route::resource('practitioners', PractitionerController::class);
    Route::post('practitioners/{id}/status', [PractitionerController::class, 'updateStatus'])->name('practitioners.status');

    Route::resource('mindfulness-practitioners', MindfulnessPractitionerController::class);
    Route::post('mindfulness-practitioners/{id}/status', [MindfulnessPractitionerController::class, 'updateStatus'])->name('mindfulness-practitioners.status');

    Route::resource('yoga-therapists', YogaTherapistController::class);
    Route::post('yoga-therapists/{id}/status', [YogaTherapistController::class, 'updateStatus'])->name('yoga-therapists.status');

    Route::resource('clients', ClientController::class);
    Route::resource('translators', TranslatorController::class);
    Route::post('translators/{id}/status', [TranslatorController::class, 'updateStatus'])->name('translators.status');
    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/permissions', [RoleController::class, 'showPermissions'])->name('roles.permissions');
    Route::post('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

    // Master Data
    Route::get('master-data/{type}', [MasterDataController::class, 'index'])->name('master-data.index');
    Route::post('master-data/{type}', [MasterDataController::class, 'store'])->name('master-data.store');
    Route::put('master-data/{type}/{id}', [MasterDataController::class, 'update'])->name('master-data.update');
    Route::delete('master-data/{type}/{id}', [MasterDataController::class, 'destroy'])->name('master-data.destroy');
});
