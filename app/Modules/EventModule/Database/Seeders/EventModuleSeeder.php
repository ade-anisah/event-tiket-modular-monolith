<?php

namespace App\Modules\EventModule\Database\Seeders;

use App\Models\User;
use App\Modules\EventModule\Models\Category;
use App\Modules\EventModule\Models\Event;
use Illuminate\Database\Seeder;

class EventModuleSeeder extends Seeder
{
    /**
     * Data contoh deterministik (id event 1, 2, 3 pada database baru).
     */
    public function run(): void
    {
        $adminId = User::where('email', 'admin@uts.test')->value('id') ?? 1;

        $musik = Category::factory()->create(['nama' => 'Musik', 'slug' => 'musik']);
        $seminar = Category::factory()->create(['nama' => 'Seminar', 'slug' => 'seminar']);
        $olahraga = Category::factory()->create(['nama' => 'Olahraga', 'slug' => 'olahraga']);

        $e1 = Event::factory()->create([
            'judul' => 'Festival Musik Jogja',
            'slug' => 'festival-musik-jogja',
            'lokasi' => 'Yogyakarta',
            'mulai_at' => '2026-12-20 19:00:00',
            'selesai_at' => '2026-12-20 22:00:00',
            'status' => Event::STATUS_PUBLISHED,
            'created_by' => $adminId,
        ]);
        $e1->categories()->sync([$musik->id, $seminar->id]);

        $e2 = Event::factory()->create([
            'judul' => 'Seminar Teknologi',
            'slug' => 'seminar-teknologi',
            'lokasi' => 'Sleman',
            'mulai_at' => '2027-01-10 09:00:00',
            'selesai_at' => '2027-01-10 12:00:00',
            'status' => Event::STATUS_PUBLISHED,
            'created_by' => $adminId,
        ]);
        $e2->categories()->sync([$seminar->id]);

        $e3 = Event::factory()->create([
            'judul' => 'Fun Run Kampus',
            'slug' => 'fun-run-kampus',
            'lokasi' => 'Bantul',
            'mulai_at' => '2027-02-14 06:00:00',
            'selesai_at' => '2027-02-14 09:00:00',
            'status' => Event::STATUS_DRAFT,
            'created_by' => $adminId,
        ]);
        $e3->categories()->sync([$olahraga->id]);
    }
}