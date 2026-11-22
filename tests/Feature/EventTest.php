<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\EventModule\Models\Category;
use App\Modules\EventModule\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    public function test_admin_membuat_event_dengan_dua_kategori(): void
    {
        $kategori = Category::factory()->count(2)->create();

        $respons = $this->actingAs($this->admin())->post('/admin/events', [
            'judul' => 'Uji Event',
            'lokasi' => 'Yogyakarta',
            'mulai_at' => '2026-12-01 10:00',
            'selesai_at' => '2026-12-01 12:00',
            'status' => 'published',
            'deskripsi' => 'Deskripsi uji.',
            'category_ids' => $kategori->pluck('id')->all(),
        ]);

        $event = Event::where('judul', 'Uji Event')->firstOrFail();

        $respons->assertRedirect(route('event.show', $event));
        $this->assertSame(2, $event->categories()->count());
        $this->assertDatabaseCount('category_event', 2);
    }

    public function test_validasi_event_gagal_bila_input_kosong(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/events', [])
            ->assertSessionHasErrors(['judul', 'lokasi', 'mulai_at', 'selesai_at', 'status', 'category_ids']);

        $this->assertDatabaseCount('events', 0);
    }

    public function test_alamat_admin_events_menolak_peserta_dan_mengarahkan_admin(): void
    {
        $peserta = User::factory()->create(['role' => User::ROLE_PESERTA]);

        $this->actingAs($peserta)->get('/admin/events')->assertForbidden();
        $this->actingAs($this->admin())->get('/admin/events')->assertRedirect(route('event.index'));
    }
}
