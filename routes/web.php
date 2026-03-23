<?php

use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PractitionerController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\MindfulnessPractitionerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TranslatorController;
use App\Http\Controllers\Admin\YogaTherapistController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\PincodeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CaptchaController;
use Illuminate\Support\Facades\Artisan;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// Social Authentication Routes
Route::get('auth/{provider}', [\App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');

Route::get('admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.submit');

Route::get('admin/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.forgot-password.show');
Route::post('admin/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetOtp'])->name('admin.forgot-password.send');
Route::get('admin/forgot-password/otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showOtpForm'])->name('admin.forgot-password.otp.show');
Route::post('admin/forgot-password/otp', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOtp'])->name('admin.forgot-password.otp.verify');
Route::get('admin/forgot-password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('admin.forgot-password.reset.show');
Route::post('admin/forgot-password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('admin.forgot-password.reset.update');
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

Route::post('/lang/{locale}', [LanguageController::class, 'change'])->name('lang.switch');

Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/index', [WebController::class, 'index'])->name('index');
Route::get('/coming-soon', [WebController::class, 'comingSoon'])->name('coming-soon');
Route::get('/about-us', [WebController::class, 'aboutUs'])->name('about-us');
Route::get('/services', [WebController::class, 'services'])->name('services');
Route::get('/gallery', [WebController::class, 'gallery'])->name('gallery');
Route::get('/find-practitioner', [WebController::class, 'findPractitioner'])->name('find-practitioner');
Route::post('/find-practitioner', [WebController::class, 'findPractitionerPost'])->name('find-practitioner.post');
Route::get('/search', [WebController::class, 'search'])->name('search');
Route::get('/filter-practitioners', [WebController::class, 'filterPractitioners'])->name('filter-practitioners');
Route::get('/search-locations', [WebController::class, 'searchLocations'])->name('search-locations');
Route::get('/practitioner/{slug}', [WebController::class, 'practitionerDetail'])->name('practitioner-detail');
Route::get('/zaya-login', [WebController::class, 'zayaLogin'])->name('zaya-login');
Route::get('/client-register', [WebController::class, 'clientRegister'])->name('client-register');
Route::get('/practitioner-register', [WebController::class, 'practitionerRegister'])->name('practitioner-register');
Route::get('/service/{slug}', [WebController::class, 'serviceDetail'])->name('service-detail');
Route::get('/blogs', [WebController::class, 'blogs'])->name('blogs');
Route::get('/announcements', [WebController::class, 'announcements'])->name('announcements');
Route::get('/announcement/{slug}', [WebController::class, 'announcementDetail'])->name('announcement-detail');
Route::get('/blog/{slug}', [WebController::class, 'blogDetail'])->name('blog-detail');
Route::get('/book-session/{practitioner?}', [WebController::class, 'bookSession'])->name('book-session');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/payment/callback', [BookingController::class, 'paymentCallback'])->name('bookings.payment.callback');
Route::get('/fetch-translators', [BookingController::class, 'fetchTranslators'])->name('fetch-translators');
Route::get('/api/available-slots/{practitioner}/{date}', [\App\Http\Controllers\AvailabilityController::class, 'getGeneratedSlots'])->name('api.available-slots');
Route::get('/contact-us', [WebController::class, 'contactUs'])->name('contact-us');
Route::post('/contact-us', [WebController::class, 'storeContact'])->name('contact-us.store');
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::post('/blog/like', [WebController::class, 'toggleLike'])->name('blog.like');
Route::post('/testimonial/{id}/like', [\App\Http\Controllers\Admin\TestimonialController::class, 'toggleLike'])->name('testimonial.like');
Route::post('/blog/comment', [WebController::class, 'postComment'])->name('blog.comment');
Route::get('/blog/comments/{postId}', [WebController::class, 'getComments'])->name('blog.comments');

Route::get('/captcha', [CaptchaController::class, 'generate'])->name('captcha');
Route::get('/magic-login', [\App\Http\Controllers\Auth\MagicLoginController::class, 'login'])->name('magic.login');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::resource('/admins', AdminsController::class);
    Route::post('/admins/{id}/status', [AdminsController::class, 'updateStatus'])->name('admins.status');
    Route::get('admin/admins/{id}/edit', [AdminController::class, 'edit']);
    Route::put('admin/admins/{id}', [AdminController::class, 'update']);
    Route::delete('/admin/admins/{id}', [AdminsController::class, 'destroy']);
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [AdminController::class, 'updatePassword'])->name('profile.password.update');
    Route::resource('countries', CountryController::class);
    Route::post('countries/{id}/status', [CountryController::class, 'updateStatus'])->name('countries.status');
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
    Route::get('testimonials/{id}/replies', [TestimonialController::class, 'replies'])->name('testimonials.replies');
    Route::get('testimonials/{id}/likes', [TestimonialController::class, 'likes'])->name('testimonials.likes');
    Route::delete('testimonials/like/{id}', [TestimonialController::class, 'destroyLike'])->name('testimonials.like.destroy');
    Route::post('testimonials/{id}/reply', [TestimonialController::class, 'storeReply'])->name('testimonials.reply.store');
    Route::delete('testimonials/reply/{id}', [TestimonialController::class, 'destroyReply'])->name('testimonials.reply.destroy');

    Route::resource('services', ServiceController::class);
    Route::post('services/{id}/status', [ServiceController::class, 'updateStatus'])->name('services.status');
    Route::delete('services/image/{id}', [ServiceController::class, 'deleteGalleryImage'])->name('services.delete-image');

    Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class);
    Route::post('packages/{id}/status', [\App\Http\Controllers\Admin\PackageController::class, 'updateStatus'])->name('packages.status');

    Route::get('other-fees', [\App\Http\Controllers\Admin\FinanceSettingController::class, 'index'])->name('other-fees.index');
    Route::post('other-fees', [\App\Http\Controllers\Admin\FinanceSettingController::class, 'update'])->name('other-fees.update');

    Route::get('credentials', [\App\Http\Controllers\Admin\CredentialController::class, 'index'])->name('credentials.index');
    Route::post('credentials/{id}/password', [\App\Http\Controllers\Admin\CredentialController::class, 'updatePassword'])->name('credentials.update-password');
    Route::post('credentials/{id}/generate-link', [\App\Http\Controllers\Admin\CredentialController::class, 'generateLoginLink'])->name('credentials.generate-link');
    
    Route::post('doctors/delete-certificate/{id}', [DoctorController::class, 'deleteCertificate'])->name('doctors.delete-certificate');

    // Reviews
    Route::get('reviews/practitioners', [\App\Http\Controllers\Admin\PractitionerReviewController::class, 'index'])->name('reviews.practitioners.index');
    Route::delete('reviews/practitioners/{id}', [\App\Http\Controllers\Admin\PractitionerReviewController::class, 'destroy'])->name('reviews.practitioners.destroy');
    Route::post('reviews/practitioners/{id}/status', [\App\Http\Controllers\Admin\PractitionerReviewController::class, 'updateStatus'])->name('reviews.practitioners.status');

    // Master Data
    Route::get('master-data/{type}', [MasterDataController::class, 'index'])->name('master-data.index');
    Route::post('master-data/{type}', [MasterDataController::class, 'store'])->name('master-data.store');
    Route::put('master-data/{type}/{id}', [MasterDataController::class, 'update'])->name('master-data.update');
    Route::post('master-data/{type}/{id}/status', [MasterDataController::class, 'updateStatus'])->name('master-data.status');
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

    // Find Practitioner Settings
    Route::get('find-practitioner-settings', [\App\Http\Controllers\Admin\FindPractitionerSettingController::class, 'index'])->name('find-practitioner-settings.index');
    Route::post('find-practitioner-settings', [\App\Http\Controllers\Admin\FindPractitionerSettingController::class, 'update'])->name('find-practitioner-settings.update');

    // Footer Page Settings
    Route::get('footer-settings', [\App\Http\Controllers\Admin\FooterPageController::class, 'index'])->name('footer-settings.index');
    Route::post('footer-settings', [\App\Http\Controllers\Admin\FooterPageController::class, 'update'])->name('footer-settings.update');

    // Contact Settings & FAQs
    Route::get('/contact-settings', [\App\Http\Controllers\Admin\ContactusController::class, 'index'])->name('contact-us.index');
    Route::post('/contact-settings/update', [\App\Http\Controllers\Admin\ContactusController::class, 'update'])->name('contact-settings.update');
    Route::post('/contact-settings/faqs', [\App\Http\Controllers\Admin\ContactusController::class, 'faqStore'])->name('contact-settings.faq-store');
    Route::post('/contact-settings/faqs/{id}', [\App\Http\Controllers\Admin\ContactusController::class, 'faqUpdate'])->name('contact-settings.faq-update');
    Route::delete('/contact-settings/faqs/{id}', [\App\Http\Controllers\Admin\ContactusController::class, 'faqDestroy'])->name('contact-settings.faq-destroy');
    Route::post('/contact-settings/faqs/{id}/status', [\App\Http\Controllers\Admin\ContactusController::class, 'faqStatus'])->name('contact-settings.faq-status');
    // Contact Messages
    Route::get('/contact-messages', [\App\Http\Controllers\Admin\ContactusController::class, 'messages'])->name('contact-us.messages');
    Route::delete('/contact-messages/{id}', [\App\Http\Controllers\Admin\ContactusController::class, 'destroyMessage'])->name('contact-us.destroy-message');

    // Email Logs
    Route::get('/email-logs', [\App\Http\Controllers\Admin\ContactusController::class, 'emailLogs'])->name('email-logs.index');
    Route::delete('/email-logs/{id}', [\App\Http\Controllers\Admin\ContactusController::class, 'destroyEmailLog'])->name('email-logs.destroy');

    // Newsletter Management
    Route::get('/newsletters', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletters.index');
    Route::post('/newsletters/{id}/status', [\App\Http\Controllers\Admin\NewsletterController::class, 'updateStatus'])->name('newsletters.status');
    Route::delete('/newsletters/{id}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletters.destroy');
    Route::get('/newsletters/export', [\App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('newsletters.export');

    // Notifications moved to routes/client.php

    // General Settings
    Route::get('general-settings', [\App\Http\Controllers\Admin\GeneralSettingController::class, 'index'])->name('general-settings.index');
    Route::post('general-settings', [\App\Http\Controllers\Admin\GeneralSettingController::class, 'update'])->name('general-settings.update');

     
    Route::resource('finance-managers', \App\Http\Controllers\Admin\FinanceManagerController::class);
    Route::get('finance-manager', function() { return redirect()->route('admin.finance-managers.index'); });
    Route::post('finance-managers/{id}/status', [\App\Http\Controllers\Admin\FinanceManagerController::class, 'updateStatus'])->name('finance-managers.status');

    Route::resource('content-managers', \App\Http\Controllers\Admin\ContentManagerController::class);
    Route::get('content-manager', function() { return redirect()->route('admin.content-managers.index'); });
    Route::post('content-managers/{id}/status', [\App\Http\Controllers\Admin\ContentManagerController::class, 'updateStatus'])->name('content-managers.status');

    Route::resource('user-managers', \App\Http\Controllers\Admin\UserManagerController::class);
    Route::get('user-manager', function() { return redirect()->route('admin.user-managers.index'); });
    Route::post('user-managers/{id}/status', [\App\Http\Controllers\Admin\UserManagerController::class, 'updateStatus'])->name('user-managers.status');

    Route::resource('languages', \App\Http\Controllers\Admin\LanguageController::class);
    Route::post('languages/{id}/status', [\App\Http\Controllers\Admin\LanguageController::class, 'updateStatus'])->name('languages.status');
    Route::post('/change-language/{id}', [LanguageController::class, 'change'])->name('change-language');
    Route::post('/change-country/{code}', function ($code) {
        session(['admin_country' => strtolower($code)]);
        return response()->json(['status' => true]);
    })->name('change-country');

    });

    // Pincode (Public)
    Route::post('/pincode/store', [\App\Http\Controllers\Admin\PincodeController::class, 'store'])->name('admin.pincode.store');
    Route::get('/pincode/get', [\App\Http\Controllers\Admin\PincodeController::class, 'getPincode'])->name('admin.pincode.get');
    Route::delete('/pincode/delete', [\App\Http\Controllers\Admin\PincodeController::class, 'destroy'])->name('admin.pincode.delete');



    // Profile page settings
    Route::get('/admin-panel-settings', [\App\Http\Controllers\Admin\AdminPanelSettingController::class, 'index'])->name('admin.admin-panel-settings.index');
    Route::get('/admin-panel-settings/edit', [\App\Http\Controllers\Admin\AdminPanelSettingController::class, 'edit'])->name('admin.admin-panel-settings.edit');
    Route::post('/admin-panel-settings/update', [\App\Http\Controllers\Admin\AdminPanelSettingController::class, 'update'])->name('admin.admin-panel-settings.update');
    Route::post('/admin-panel-settings/change-password', [\App\Http\Controllers\Admin\AdminPanelSettingController::class, 'changePassword'])->name('admin.admin-panel-settings.change-password');

    // client pannel settings
    Route::get('/client-pannel-settings', [\App\Http\Controllers\Admin\ClientPannelSettingController::class, 'index'])->name('admin.client-pannel-settings.index');
    Route::post('/client-pannel-settings/update', [\App\Http\Controllers\Admin\ClientPannelSettingController::class, 'update'])->name('admin.client-pannel-settings.update');

    // Invoice settings
    Route::get('/invoice-settings', [\App\Http\Controllers\Admin\InvoiceSettingController::class, 'index'])->name('admin.invoice-settings.index');
    Route::post('/invoice-settings/update', [\App\Http\Controllers\Admin\InvoiceSettingController::class, 'update'])->name('admin.invoice-settings.update');

    // Invoice Management
    Route::get('/invoices', [\App\Http\Controllers\InvoiceController::class, 'index'])->name('admin.invoices.index');
    Route::get('/invoice/preview', [\App\Http\Controllers\InvoiceController::class, 'preview'])->name('admin.invoice.preview');
    


// Route to run artisan optimize
Route::get('/optimize', function () {
    Artisan::call('optimize:clear');
    Artisan::call('optimize');
    return 'Application optimized successfully!';
});

Route::get('/migrate', function () {
    set_time_limit(300);
    Artisan::call('migrate', ['--force' => true]);
    return '<pre>' . Artisan::output() . '</pre>';
});

Route::get('/migrate-fresh', function () {
    set_time_limit(300);
    Artisan::call('migrate:fresh', ['--force' => true]);
    return '<pre>' . Artisan::output() . '</pre>';
});

Route::get('/migrate-fresh-seed', function () {
    set_time_limit(600);
    Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
    return '<pre>' . Artisan::output() . '</pre>';
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage linked successfully!';
});

Route::get('/seed', function () {
    set_time_limit(600);
    Artisan::call('db:seed', ['--force' => true]);
    return '<pre>' . Artisan::output() . '</pre>';
});


Route::get('/practitioner-profile', function () {
    return view('practitioner-profile');
})->name('practitioner-profile');

Route::get('/preview-otp-mail', function () {
    return new App\Mail\AdminOTPMail('123456');
});

Route::get('/preview-mail-template', function () {
    $messageData = (object) [
        'first_name' => 'Aarav',
        'last_name' => 'Sharma',
        'email' => 'aarav@example.com',
        'phone' => '+91 98765 43210',
        'user_type' => ['client'],
        'message' => "Hello,\nI would like to know more about your services."
    ];

    return view('emails.default', [
        'title' => 'Email Template Preview',
        'intro' => 'This is a preview of the unified email template.',
        'otp' => '123456',
        'expiration' => 'This OTP will expire in 5 minutes for your security.',
        'messageData' => $messageData,
        'outro' => 'If you did not request this email, please ignore it.',
    ]);
});
