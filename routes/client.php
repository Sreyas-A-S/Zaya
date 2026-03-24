<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'isClient'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');
    Route::post('/update-consent', [ProfileController::class, 'updateConsent'])->name('profile.updateConsent');
    Route::get('/bookings', [ProfileController::class, 'bookings'])->name('bookings.index');
    Route::get('/transactions', [ProfileController::class, 'transactions'])->name('transactions.index');
    Route::get('/conference-history', [ProfileController::class, 'conferences'])->name('conferences.index');
    Route::get('/recordings/{id}', [ProfileController::class, 'showRecording'])->name('recordings.show');
    Route::get('/conference/session/{channel}', [ProfileController::class, 'joinSession'])->name('conference.join');
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
    Route::get('/practitioner-profile', [ProfileController::class, 'practitionerProfile'])->name('practitioner.profile');
    Route::post('/practitioner-profile/update-professional', [ProfileController::class, 'updateProfessionalDetails'])->name('practitioner.profile.updateProfessional');
    Route::post('/practitioner-profile/gallery', [ProfileController::class, 'uploadGalleryImage'])->name('practitioner.profile.gallery.upload');
    Route::delete('/practitioner-profile/gallery/{id}', [ProfileController::class, 'deleteGalleryImage'])->name('practitioner.profile.gallery.delete');
    Route::get('/api/referrable-practitioners', [\App\Http\Controllers\BookingController::class, 'fetchReferrablePractitioners'])->name('api.referrable-practitioners');

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
