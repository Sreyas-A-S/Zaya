<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');
    Route::post('/update-consent', [ProfileController::class, 'updateConsent'])->name('profile.updateConsent');
    Route::get('/bookings', [ProfileController::class, 'bookings'])->name('bookings.index');
    Route::get('/transactions', [ProfileController::class, 'transactions'])->name('transactions.index');
});
