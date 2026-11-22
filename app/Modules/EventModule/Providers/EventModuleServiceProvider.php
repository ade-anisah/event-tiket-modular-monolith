<?php

namespace App\Modules\EventModule\Providers;

use App\Modules\EventModule\Contracts\EventLookupContract;
use App\Modules\EventModule\Listeners\TambahPesertaTerdaftar;
use App\Modules\EventModule\Services\EventLookupService;
use App\Modules\RegistrationModule\Events\PendaftaranDikonfirmasi;
use Illuminate\Support\Facades\Event as EventFacade;
use Illuminate\Support\ServiceProvider;

class EventModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Contract diikat ke implementasinya di modul penyedia.
        $this->app->bind(EventLookupContract::class, EventLookupService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'event');

        // Domain Event dari RegistrationModule didengar di sini.
        EventFacade::listen(PendaftaranDikonfirmasi::class, TambahPesertaTerdaftar::class);
    }
}
