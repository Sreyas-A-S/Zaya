<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PractitionerController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\MindfulnessPractitionerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TranslatorController;
use App\Http\Controllers\Admin\YogaTherapistController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ServiceController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Artisan;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.submit');
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
Route::get('/practitioner/{id}', [WebController::class, 'practitionerDetail'])->name('practitioner-detail');
Route::get('/zaya-login', [WebController::class, 'zayaLogin'])->name('zaya-login');
Route::get('/client-register', [WebController::class, 'clientRegister'])->name('client-register');
Route::get('/practitioner-register', [WebController::class, 'practitionerRegister'])->name('practitioner-register');
Route::get('/service/{slug}', [WebController::class, 'serviceDetail'])->name('service-detail');
Route::get('/blogs', [WebController::class, 'blogs'])->name('blogs');
Route::get('/blog/{slug}', [WebController::class, 'blogDetail'])->name('blog-detail');
Route::get('/book-session', [WebController::class, 'bookSession'])->name('book-session');
Route::get('/contact-us', [WebController::class, 'contactUs'])->name('contact-us');

Route::post('/blog/like', [WebController::class, 'toggleLike'])->name('blog.like');
Route::post('/blog/comment', [WebController::class, 'postComment'])->name('blog.comment');
Route::get('/blog/comments/{postId}', [WebController::class, 'getComments'])->name('blog.comments');

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
    Route::post('clients/{id}/status', [ClientController::class, 'updateStatus'])->name('clients.status');
    Route::resource('translators', TranslatorController::class);
    Route::post('translators/{id}/status', [TranslatorController::class, 'updateStatus'])->name('translators.status');
    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/permissions', [RoleController::class, 'showPermissions'])->name('roles.permissions');
    Route::post('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

    Route::resource('testimonials', TestimonialController::class);
    Route::post('testimonials/{id}/status', [TestimonialController::class, 'updateStatus'])->name('testimonials.status');

    Route::resource('services', ServiceController::class);
    Route::post('services/{id}/status', [ServiceController::class, 'updateStatus'])->name('services.status');
    Route::delete('services/image/{id}', [ServiceController::class, 'deleteGalleryImage'])->name('services.delete-image');
    Route::post('doctors/delete-certificate/{id}', [DoctorController::class, 'deleteCertificate'])->name('doctors.delete-certificate');

    // Reviews
    Route::get('reviews/practitioners', [\App\Http\Controllers\Admin\PractitionerReviewController::class, 'index'])->name('reviews.practitioners.index');
    Route::delete('reviews/practitioners/{id}', [\App\Http\Controllers\Admin\PractitionerReviewController::class, 'destroy'])->name('reviews.practitioners.destroy');
    Route::post('reviews/practitioners/{id}/status', [\App\Http\Controllers\Admin\PractitionerReviewController::class, 'updateStatus'])->name('reviews.practitioners.status');

    // Master Data
    Route::get('master-data/{type}', [MasterDataController::class, 'index'])->name('master-data.index');
    Route::post('master-data/{type}', [MasterDataController::class, 'store'])->name('master-data.store');
    Route::put('master-data/{type}/{id}', [MasterDataController::class, 'update'])->name('master-data.update');
    Route::delete('master-data/{type}/{id}', [MasterDataController::class, 'destroy'])->name('master-data.destroy');

    // Homepage Settings
    Route::get('homepage-settings', [\App\Http\Controllers\Admin\HomepageSettingController::class, 'index'])->name('homepage-settings.index');
    Route::post('homepage-settings', [\App\Http\Controllers\Admin\HomepageSettingController::class, 'update'])->name('homepage-settings.update');

    // About Us Settings
    Route::get('about-settings', [\App\Http\Controllers\Admin\AboutSettingController::class, 'index'])->name('about-settings.index');
    Route::post('about-settings', [\App\Http\Controllers\Admin\AboutSettingController::class, 'update'])->name('about-settings.update');

    // Services Page Settings
    Route::get('services-settings', [\App\Http\Controllers\Admin\ServicesSettingController::class, 'index'])->name('services-settings.index');
    Route::post('services-settings', [\App\Http\Controllers\Admin\ServicesSettingController::class, 'update'])->name('services-settings.update');
});

// Route to run artisan optimize
Route::get('/optimize', function () {
    Artisan::call('optimize:clear');
    Artisan::call('optimize');
    return 'Application optimized successfully!';
});

Route::get('/migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    return '<pre>' . Artisan::output() . '</pre>';
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage linked successfully!';
});

Route::get('/seed', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return '<pre>' . Artisan::output() . '</pre>';
});
