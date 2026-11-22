<?php

namespace App\Modules\TicketModule\Database\Seeders;

use App\Modules\TicketModule\Models\TicketType;
use Illuminate\Database\Seeder;

class TicketModuleSeeder extends Seeder
{
    /**
     * Data contoh deterministik. event_id 1, 2, 3 mengacu ke event dari EventModuleSeeder
     * (hanya berupa ID, tanpa mengakses model EventModule).
     */
    public function run(): void
    {
        $data = [
            ['event_id' => 1, 'nama' => 'Regular', 'harga' => 150000, 'kuota' => 5],
            ['event_id' => 1, 'nama' => 'VIP', 'harga' => 300000, 'kuota' => 2],
            ['event_id' => 2, 'nama' => 'Umum', 'harga' => 50000, 'kuota' => 10],
            ['event_id' => 3, 'nama' => 'Early Bird', 'harga' => 75000, 'kuota' => 3],
        ];

        foreach ($data as $baris) {
            TicketType::factory()->create($baris + ['terjual' => 0, 'maks_per_pembelian' => 4]);
        }
    }
}