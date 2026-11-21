<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/_modul/registration', fn () => 'RegistrationModule aktif')->name('registration.ping');
});