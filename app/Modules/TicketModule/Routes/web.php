<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/_modul/ticket', fn () => 'TicketModule aktif')->name('ticket.ping');
});