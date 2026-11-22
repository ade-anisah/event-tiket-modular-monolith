<?php

namespace App\Modules\TicketModule\Providers;

use App\Modules\TicketModule\Contracts\TicketInventoryContract;
use App\Modules\TicketModule\Services\TicketInventoryService;
use Illuminate\Support\ServiceProvider;

class TicketModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Contract diikat ke implementasinya di modul penyedia.
        $this->app->bind(TicketInventoryContract::class, TicketInventoryService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'ticket');
    }
}
