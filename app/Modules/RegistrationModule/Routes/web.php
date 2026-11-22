<?php

use App\Modules\RegistrationModule\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:sanctum'])->group(function () {
    Route::post('/beli-tiket', [RegistrationController::class, 'store'])->name('registration.purchase');
    Route::get('/pendaftaran-saya', [RegistrationController::class, 'mine'])->name('registration.mine');
});