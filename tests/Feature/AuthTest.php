<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function dataDaftar(array $ubah = []): array
    {
        return array_merge([
            'name' => 'Peserta Uji',
            'email' => 'uji@uts.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $ubah);
    }

    public function test_registrasi_membuat_akun_peserta_dan_langsung_masuk(): void
    {
        $this->post('/register', $this->dataDaftar())->assertRedirect('/events');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'uji@uts.test', 'role' => 'peserta']);
    }

    public function test_pengguna_tidak_bisa_memilih_role_sendiri_saat_registrasi(): void
    {
        $this->post('/register', $this->dataDaftar(['role' => 'admin']));

        $this->assertDatabaseHas('users', ['email' => 'uji@uts.test', 'role' => 'peserta']);
    }

    public function test_login_gagal_lalu_berhasil(): void
    {
        User::factory()->create(['email' => 'a@uts.test']);

        $this->post('/login', ['email' => 'a@uts.test', 'password' => 'salah'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/login', ['email' => 'a@uts.test', 'password' => 'password'])
            ->assertRedirect('/events');
        $this->assertAuthenticated();
    }

    public function test_tamu_dialihkan_ke_halaman_login(): void
    {
        $this->get('/tiket-saya')->assertRedirect(route('login'));
    }

    public function test_peserta_ditolak_dan_admin_diizinkan_di_halaman_admin(): void
    {
        $peserta = User::factory()->create(['role' => User::ROLE_PESERTA]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($peserta)->get('/admin/events/create')->assertForbidden();
        $this->actingAs($admin)->get('/admin/events/create')->assertOk();
    }
}
