<?php

namespace App\Modules\RegistrationModule\Database\Seeders;

use App\Models\User;
use App\Modules\RegistrationModule\Models\Participant;
use Illuminate\Database\Seeder;

class RegistrationModuleSeeder extends Seeder
{
    /**
     * Buat profil peserta untuk setiap akun berperan peserta (3 akun dari DatabaseSeeder).
     */
    public function run(): void
    {
        $akun = User::where('role', User::ROLE_PESERTA)->orderBy('email')->get();

        foreach ($akun as $urutan => $user) {
            Participant::factory()->create([
                'user_id' => $user->id,
                'nama_lengkap' => $user->name,
                'no_hp' => '0812000000'.($urutan + 1),
            ]);
        }
    }
}
