<?php

use App\Http\Controllers\Auth\WebAuthController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/events');

Route::middleware('guest')->group(function () {
    Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register'])->middleware('throttle:5,1')->name('register.store');
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
});

Route::post('/logout', [WebAuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');