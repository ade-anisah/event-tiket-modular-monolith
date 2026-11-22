<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\RegistrationModule\Models\Participant;
use App\Modules\TicketModule\Models\TicketType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Angka diambil dari seeder deterministik (docs/perancangan/03-contract-event-seeder.md):
 * TT1 Regular 150000 kuota 5, TT2 VIP 300000 kuota 2, TT3 Umum 50000 kuota 10, TT4 Early Bird (event draft).
 */
class TicketPurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    private function peserta(int $nomor): User
    {
        return User::where('email', "peserta{$nomor}@uts.test")->firstOrFail();
    }

    private function beli(User $user, int $tipeId, int $jumlah): TestResponse
    {
        return $this->actingAs($user)
            ->from('/events/1/tiket')
            ->post(route('registration.purchase'), ['ticket_type_id' => $tipeId, 'jumlah' => $jumlah]);
    }

    public function test_skenario_kumulatif_t1_sampai_t5_dan_t10(): void
    {
        // T1: peserta1 beli 2 Regular, berhasil.
        $this->beli($this->peserta(1), 1, 2)->assertRedirect(route('registration.mine'));
        $this->assertDatabaseHas('ticket_types', ['id' => 1, 'terjual' => 2]);
        $this->assertDatabaseHas('registrations', ['jumlah' => 2, 'total' => 300000, 'status' => 'confirmed']);
        $this->assertDatabaseHas('events', ['id' => 1, 'peserta_terdaftar' => 2]);
        $this->assertDatabaseCount('registrations', 1);
        $this->assertDatabaseCount('tickets', 2);

        // T2: peserta2 minta 4 padahal sisa 3, ditolak dan di-rollback.
        $this->beli($this->peserta(2), 1, 4)
            ->assertRedirect('/events/1/tiket')
            ->assertSessionHas('error');
        $this->assertDatabaseHas('ticket_types', ['id' => 1, 'terjual' => 2]);
        $this->assertDatabaseHas('events', ['id' => 1, 'peserta_terdaftar' => 2]);
        $this->assertDatabaseCount('registrations', 1);
        $this->assertDatabaseCount('tickets', 2);

        // T3: peserta2 beli 3 (sisa 3), kuota habis.
        $this->beli($this->peserta(2), 1, 3)->assertRedirect(route('registration.mine'));
        $this->assertDatabaseHas('ticket_types', ['id' => 1, 'terjual' => 5]);
        $this->assertDatabaseHas('registrations', ['jumlah' => 3, 'total' => 450000]);
        $this->assertDatabaseHas('events', ['id' => 1, 'peserta_terdaftar' => 5]);
        $this->assertDatabaseCount('registrations', 2);
        $this->assertDatabaseCount('tickets', 5);

        // T4: peserta3 beli 1 saat kuota habis.
        $this->beli($this->peserta(3), 1, 1)->assertSessionHas('error');
        $this->assertDatabaseHas('ticket_types', ['id' => 1, 'terjual' => 5]);
        $this->assertDatabaseCount('registrations', 2);
        $this->assertDatabaseCount('tickets', 5);

        // T5: peserta3 beli 2 VIP.
        $this->beli($this->peserta(3), 2, 2)->assertRedirect(route('registration.mine'));
        $this->assertDatabaseHas('ticket_types', ['id' => 2, 'terjual' => 2]);
        $this->assertDatabaseHas('registrations', ['jumlah' => 2, 'total' => 600000]);
        $this->assertDatabaseHas('events', ['id' => 1, 'peserta_terdaftar' => 7]);
        $this->assertDatabaseCount('registrations', 3);
        $this->assertDatabaseCount('tickets', 7);

        // T10: 7 tiket, 7 kode berbeda.
        $this->assertSame(7, DB::table('tickets')->distinct()->count('kode'));

        // Relasi One-to-Many berfungsi: Regular menerbitkan 5 tiket, peserta2 punya 1 pendaftaran.
        $this->assertCount(5, TicketType::findOrFail(1)->tickets);
        $peserta2 = Participant::where('user_id', $this->peserta(2)->id)->firstOrFail();
        $this->assertCount(1, $peserta2->registrations);
    }

    public function test_t6_jumlah_melebihi_batas_per_pembelian_ditolak_validasi(): void
    {
        $this->beli($this->peserta(1), 3, 5)->assertSessionHasErrors('jumlah');

        $this->assertDatabaseCount('registrations', 0);
        $this->assertDatabaseHas('ticket_types', ['id' => 3, 'terjual' => 0]);
    }

    public function test_t8_event_draft_tidak_dapat_dibeli(): void
    {
        $this->beli($this->peserta(1), 4, 1)->assertSessionHas('error');

        $this->assertDatabaseHas('ticket_types', ['id' => 4, 'terjual' => 0]);
        $this->assertDatabaseCount('registrations', 0);
        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_rollback_membatalkan_pendaftaran_pending_saat_kuota_tidak_cukup(): void
    {
        // Sisa dibuat 2 (terjual 3 dari kuota 5), lalu diminta 3.
        TicketType::whereKey(1)->increment('terjual', 3);

        $this->beli($this->peserta(1), 1, 3)->assertSessionHas('error');

        // Baris registrations berstatus pending sempat dibuat di dalam transaksi, lalu dibatalkan.
        $this->assertDatabaseCount('registrations', 0);
        $this->assertDatabaseCount('tickets', 0);
        $this->assertDatabaseHas('ticket_types', ['id' => 1, 'terjual' => 3]);
    }

    public function test_tamu_tidak_dapat_membeli_tiket(): void
    {
        $this->post(route('registration.purchase'), ['ticket_type_id' => 1, 'jumlah' => 1])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('registrations', 0);
    }
}
