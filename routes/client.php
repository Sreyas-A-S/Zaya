<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ZegoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'isClient'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');
    Route::get('/health-journey', [ProfileController::class, 'healthJourney'])->name('health-journey.index');
    Route::post('/update-consent', [ProfileController::class, 'updateConsent'])->name('profile.updateConsent');
    Route::get('/consultations', [ProfileController::class, 'bookings'])->name('consultations.index');
    Route::get('/bookings', [ProfileController::class, 'bookings'])->name('bookings.index');
    Route::get('/transactions', [ProfileController::class, 'transactions'])->name('transactions.index');
    Route::get('/rewards', [ProfileController::class, 'promoCodes'])->name('rewards.index');
    Route::post('/rewards', [ProfileController::class, 'storePromoCode'])->name('rewards.store');
    Route::post('/rewards/regenerate-link', [ProfileController::class, 'regenerateReferralToken'])->name('rewards.regenerate');
    Route::get('/conference-history', [ProfileController::class, 'conferences'])->name('conferences.index');
    Route::post('/conference/instant-init', [\App\Http\Controllers\ConferenceController::class, 'initInstantMeeting'])->name('zego.instant.init');
    Route::get('/recordings/{id}', [ProfileController::class, 'showRecording'])->name('recordings.show');
    Route::get('/conference/session/{channel}', [ProfileController::class, 'joinSession'])->name('conference.join');
    Route::get('/conference/zego/{channel}', [ZegoController::class, 'join'])->name('zego.join');
    Route::get('/bookings/{id}/consultation-form', [ProfileController::class, 'showConsultationForm'])->name('bookings.consultation-form.show');
    Route::post('/bookings/{id}/consultation-form', [ProfileController::class, 'storeConsultationForm'])->name('bookings.consultation-form.store');
    Route::get('/agora/token', [ProfileController::class, 'generateToken'])->name('agora.token');
    Route::get('/invoice/{invoice_no}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/bookings/{id}/details', [ProfileController::class, 'showDetails'])->name('bookings.details');
    Route::get('/bookings/{id}/details-view', [ProfileController::class, 'showDetailsView'])->name('bookings.details-view');

    // Clinical Document Routes
    Route::post('/clinical-documents', [ProfileController::class, 'uploadDocument'])->name('clinical-documents.upload');
    Route::delete('/clinical-documents/{id}', [ProfileController::class, 'deleteDocument'])->name('clinical-documents.delete');

    // Referral Routes
    Route::post('/bookings/{id}/refer', [\App\Http\Controllers\ReferralController::class, 'store'])->name('bookings.refer');
    Route::get('/referrals/{referral_no}/pay', [\App\Http\Controllers\ReferralController::class, 'pay'])->name('referrals.pay');
    Route::post('/referrals/{referral_no}/pay', [\App\Http\Controllers\ReferralController::class, 'initiatePayment'])->name('referrals.pay.initiate');
    Route::post('/referrals/{referral_no}/resend-otp', [\App\Http\Controllers\ReferralController::class, 'resendOTP'])->name('referrals.resend-otp');
    Route::post('/referrals/{referral_no}/verify-consent', [\App\Http\Controllers\ReferralController::class, 'verifyConsent'])->name('referrals.verify-consent');
    Route::get('/referrals/payment/callback', [\App\Http\Controllers\ReferralController::class, 'paymentCallback'])->name('referrals.payment.callback');

    // Data Access Routes (OTP)
    Route::post('/data-access/request', [\App\Http\Controllers\DataAccessController::class, 'requestAccess'])->name('data-access.request');
    Route::post('/data-access/verify', [\App\Http\Controllers\DataAccessController::class, 'verifyOTP'])->name('data-access.verify');
    Route::post('/data-access/toggle', [\App\Http\Controllers\DataAccessController::class, 'toggleAccess'])->name('data-access.toggle');
    Route::get('/client-profile/{id}', [ProfileController::class, 'viewClientProfile'])->name('client.profile.view');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/complete-profile', [ProfileController::class, 'completeProfile'])->name('profile.complete');
    Route::post('/complete-profile', [ProfileController::class, 'storeCompleteProfile'])->name('profile.complete.store');
    Route::post('/profile/send-otp', [ProfileController::class, 'sendEmailOTP'])->name('profile.sendOtp');
    Route::post('/profile/verify-otp', [ProfileController::class, 'verifyEmailOTP'])->name('profile.verifyOtp');
    Route::post('/profile/check-email', [ProfileController::class, 'checkEmail'])->name('profile.checkEmail');
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

    Route::get('/api/referrable-practitioners', [BookingController::class, 'fetchReferrablePractitioners'])->name('referrable-practitioners-api');
    Route::get('/api/professional-profile/{user}', [BookingController::class, 'getProfessionalProfile'])->name('professional-profile-api');
    Route::get('/api/available-translators', [ProfileController::class, 'fetchAvailableTranslators'])->name('available-translators-api');
    Route::post('/bookings/{id}/assign-translator', [ProfileController::class, 'assignTranslator'])->name('bookings.assign-translator');

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

    // Conference Session Storage
    Route::post('/conference/store', [ProfileController::class, 'storeConference'])->name('conference.store');

    // Reviews
    Route::get('/reviews', [ProfileController::class, 'reviews'])->name('reviews.index');
    Route::post('/reviews', [ProfileController::class, 'storeReview'])->name('reviews.store');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

Route::get('/conference/share/{channel}', [ProfileController::class, 'publicJoinSession'])->name('conference.share');
Route::get('/conference/zego/share/{channel}', [ZegoController::class, 'publicJoin'])->name('zego.share');
Route::post('/conference/upload-recording', [ProfileController::class, 'uploadConferenceRecording'])->name('conference.upload-recording');
Route::post('/conference/zego/{channel}/recording/start', [ZegoController::class, 'startRecording'])->name('zego.recording.start');
Route::post('/conference/zego/{channel}/recording/stop', [ZegoController::class, 'stopRecording'])->name('zego.recording.stop');
Route::post('/conference/zego/{channel}/recording/status', [ZegoController::class, 'syncRecordingStatus'])->name('zego.recording.status');
Route::post('/conference/zego/{channel}/token', [ZegoController::class, 'generateToken'])->name('zego.token');
