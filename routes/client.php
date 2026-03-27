<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'isClient'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');
    Route::get('/health-journey', [ProfileController::class, 'healthJourney'])->name('health-journey.index');
    Route::post('/update-consent', [ProfileController::class, 'updateConsent'])->name('profile.updateConsent');
    Route::get('/consultations', [ProfileController::class, 'bookings'])->name('consultations.index');
    Route::get('/bookings', [ProfileController::class, 'bookings'])->name('bookings.index');
    Route::get('/transactions', [ProfileController::class, 'transactions'])->name('transactions.index');
    Route::get('/conference-history', [ProfileController::class, 'conferences'])->name('conferences.index');
    Route::get('/recordings/{id}', [ProfileController::class, 'showRecording'])->name('recordings.show');
    Route::get('/conference/session/{channel}', [ProfileController::class, 'joinSession'])->name('conference.join');
    Route::get('/bookings/{id}/consultation-form', [ProfileController::class, 'showConsultationForm'])->name('bookings.consultation-form.show');
    Route::post('/bookings/{id}/consultation-form', [ProfileController::class, 'storeConsultationForm'])->name('bookings.consultation-form.store');
    Route::get('/agora/token', [ProfileController::class, 'generateToken'])->name('agora.token');
    Route::get('/invoice/{invoice_no}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/bookings/{id}/details', [ProfileController::class, 'showDetails'])->name('bookings.details');

    // Clinical Document Routes
    Route::post('/clinical-documents', [ProfileController::class, 'uploadDocument'])->name('clinical-documents.upload');
    Route::delete('/clinical-documents/{id}', [ProfileController::class, 'deleteDocument'])->name('clinical-documents.delete');

    // Referral Routes
    Route::post('/bookings/{id}/refer', [\App\Http\Controllers\ReferralController::class, 'store'])->name('bookings.refer');
    Route::get('/referrals/{referral_no}/pay', [\App\Http\Controllers\ReferralController::class, 'pay'])->name('referrals.pay');
    Route::get('/referrals/payment/callback', [\App\Http\Controllers\ReferralController::class, 'paymentCallback'])->name('referrals.payment.callback');

    // Data Access Routes (OTP)
    Route::post('/data-access/request', [\App\Http\Controllers\DataAccessController::class, 'requestAccess'])->name('data-access.request');
    Route::post('/data-access/verify', [\App\Http\Controllers\DataAccessController::class, 'verifyOTP'])->name('data-access.verify');
    Route::get('/client-profile/{id}', [ProfileController::class, 'viewClientProfile'])->name('client.profile.view');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/update-personal', [ProfileController::class, 'updatePersonalDetails'])->name('profile.updatePersonal');
    Route::post('/profile/update-pic', [ProfileController::class, 'updateProfilePic'])->name('profile.updatePic');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::post('/profile/update-professional', [ProfileController::class, 'updateProfessionalDetails'])->name('profile.updateProfessional');
    Route::post('/profile/gallery', [ProfileController::class, 'uploadGalleryImage'])->name('profile.gallery.upload');
    Route::delete('/profile/gallery/{id}', [ProfileController::class, 'deleteGalleryImage'])->name('profile.gallery.delete');

    // My Services Routes
    Route::get('/my-services', [ProfileController::class, 'myServices'])->name('my-services.index');
    Route::get('/api/available-services', [ProfileController::class, 'getAvailableServices'])->name('api.available-services');
    Route::post('/my-services', [ProfileController::class, 'storeService'])->name('my-services.store');
    Route::post('/my-services/reminder-settings', [ProfileController::class, 'updateReminderSettings'])->name('my-services.reminder');
    Route::delete('/my-services/{id}', [ProfileController::class, 'deleteService'])->name('my-services.delete');
    Route::delete('/my-services/group/{service_id}', [ProfileController::class, 'deleteServiceGroup'])->name('my-services.delete-group');

    Route::get('/api/referrable-practitioners', [BookingController::class, 'fetchReferrablePractitioners'])->name('api.referrable-practitioners');

    // Availability / Time Slots
    Route::get('/time-slots', [\App\Http\Controllers\AvailabilityController::class, 'index'])->name('time-slots.index');
    Route::post('/time-slots', [\App\Http\Controllers\AvailabilityController::class, 'store'])->name('time-slots.store');
    Route::get('/time-slots/date/{date}', [\App\Http\Controllers\AvailabilityController::class, 'getDateSlots'])->name('time-slots.get-date-slots');
    Route::post('/time-slots/reset', [\App\Http\Controllers\AvailabilityController::class, 'resetToWeekly'])->name('time-slots.reset-to-weekly');
    Route::post('/time-slots/settings', [\App\Http\Controllers\AvailabilityController::class, 'updateBookingSettings'])->name('time-slots.update-settings');
    Route::post('/time-slots/weekly-off', [\App\Http\Controllers\AvailabilityController::class, 'updateWeeklyOffDays'])->name('time-slots.update-weekly-off');
    Route::post('/time-slots/weekly-slots', [\App\Http\Controllers\AvailabilityController::class, 'updateWeeklySlots'])->name('time-slots.update-weekly-slots');
    Route::post('/time-slots/toggle-off', [\App\Http\Controllers\AvailabilityController::class, 'toggleOffDay'])->name('time-slots.toggle-off');
    Route::post('/time-slots/toggle-off-time', [\App\Http\Controllers\AvailabilityController::class, 'toggleOffTime'])->name('time-slots.toggle-off-time');
    Route::delete('/time-slots/{id}', [\App\Http\Controllers\AvailabilityController::class, 'destroy'])->name('time-slots.destroy');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::get('/conference/share/{channel}', [ProfileController::class, 'publicJoinSession'])->name('conference.share');
