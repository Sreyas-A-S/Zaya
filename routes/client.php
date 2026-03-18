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
});
