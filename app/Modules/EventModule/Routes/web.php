<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/_modul/event', fn () => 'EventModule aktif')->name('event.ping');
});