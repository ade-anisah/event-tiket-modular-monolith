<?php

namespace Database\Seeders;

use App\Modules\EventModule\Database\Seeders\EventModuleSeeder;
use App\Modules\RegistrationModule\Database\Seeders\RegistrationModuleSeeder;
use App\Modules\TicketModule\Database\Seeders\TicketModuleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Urutan penting: akun lebih dulu, karena seeder modul membutuhkan akun admin dan peserta.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EventModuleSeeder::class,
            TicketModuleSeeder::class,
            RegistrationModuleSeeder::class,
        ]);
    }
}
