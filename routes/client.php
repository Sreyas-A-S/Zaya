<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');
    Route::post('/update-consent', [ProfileController::class, 'updateConsent'])->name('profile.updateConsent');
    Route::get('/bookings', [ProfileController::class, 'bookings'])->name('bookings.index');
    Route::get('/transactions', [ProfileController::class, 'transactions'])->name('transactions.index');
    Route::get('/conference-history', [ProfileController::class, 'conferences'])->name('conferences.index');
    Route::get('/recordings/{id}', [ProfileController::class, 'showRecording'])->name('recordings.show');
    Route::get('/conference/session/{channel}', [ProfileController::class, 'joinSession'])->name('conference.join');
    Route::get('/agora/token', [ProfileController::class, 'generateToken'])->name('agora.token');

    // Availability / Time Slots
    Route::get('/time-slots', [\App\Http\Controllers\AvailabilityController::class, 'index'])->name('time-slots.index');
    Route::post('/time-slots', [\App\Http\Controllers\AvailabilityController::class, 'store'])->name('time-slots.store');
    Route::get('/time-slots/date/{date}', [\App\Http\Controllers\AvailabilityController::class, 'getDateSlots'])->name('time-slots.get-date-slots');
    Route::post('/time-slots/reset', [\App\Http\Controllers\AvailabilityController::class, 'resetToWeekly'])->name('time-slots.reset-to-weekly');
    Route::post('/time-slots/settings', [\App\Http\Controllers\AvailabilityController::class, 'updateBookingSettings'])->name('time-slots.update-settings');
    Route::post('/time-slots/weekly-off', [\App\Http\Controllers\AvailabilityController::class, 'updateWeeklyOffDays'])->name('time-slots.update-weekly-off');
    Route::post('/time-slots/toggle-off', [\App\Http\Controllers\AvailabilityController::class, 'toggleOffDay'])->name('time-slots.toggle-off');
    Route::post('/time-slots/toggle-off-time', [\App\Http\Controllers\AvailabilityController::class, 'toggleOffTime'])->name('time-slots.toggle-off-time');
    Route::delete('/time-slots/{id}', [\App\Http\Controllers\AvailabilityController::class, 'destroy'])->name('time-slots.destroy');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
});
