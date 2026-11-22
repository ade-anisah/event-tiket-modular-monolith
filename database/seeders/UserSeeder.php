<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Akun contoh. Password semua akun adalah "password" (data dummy).
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin UTS',
            'email' => 'admin@uts.test',
            'role' => User::ROLE_ADMIN,
        ]);

        foreach ([1, 2, 3] as $nomor) {
            User::factory()->create([
                'name' => "Peserta {$nomor}",
                'email' => "peserta{$nomor}@uts.test",
                'role' => User::ROLE_PESERTA,
            ]);
        }
    }
}
