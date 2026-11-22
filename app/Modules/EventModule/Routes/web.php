<?php

use App\Modules\EventModule\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// Route yang dimuat lewat loadRoutesFrom tidak otomatis memakai grup middleware "web",
// jadi grup ini wajib ditulis agar session, CSRF, dan route model binding berjalan.
Route::middleware('web')->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('event.index');

    Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/events', fn () => redirect()->route('event.index'))->name('event.admin');
        Route::get('/events/create', [EventController::class, 'create'])->name('event.create');
        Route::post('/events', [EventController::class, 'store'])->name('event.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->whereNumber('event')->name('event.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->whereNumber('event')->name('event.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->whereNumber('event')->name('event.destroy');
    });

    Route::get('/events/{event}', [EventController::class, 'show'])->whereNumber('event')->name('event.show');
});
