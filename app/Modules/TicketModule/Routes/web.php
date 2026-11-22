<?php

use App\Modules\TicketModule\Http\Controllers\MyTicketController;
use App\Modules\TicketModule\Http\Controllers\TicketCatalogController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/events/{eventId}/tiket', [TicketCatalogController::class, 'index'])
        ->whereNumber('eventId')->name('ticket.catalog');

    Route::get('/tiket-saya', [MyTicketController::class, 'index'])
        ->middleware('auth:sanctum')->name('ticket.mine');
});
