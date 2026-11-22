<?php

namespace App\Modules\RegistrationModule\Services;

use App\Modules\EventModule\Contracts\EventLookupContract;
use App\Modules\RegistrationModule\Events\PendaftaranDikonfirmasi;
use App\Modules\RegistrationModule\Models\Participant;
use App\Modules\RegistrationModule\Models\Registration;
use App\Modules\TicketModule\Contracts\TicketInventoryContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrationService
{
    public function __construct(
        private readonly EventLookupContract $events,
        private readonly TicketInventoryContract $tickets,
    ) {}

    /**
     * Proses pembelian tiket dalam satu transaksi database.
     * Bila ada pengecualian (misalnya kuota penuh), seluruh perubahan dibatalkan (rollback).
     */
    public function purchase(int $userId, string $namaUser, int $ticketTypeId, int $jumlah): Registration
    {
        $registration = DB::transaction(function () use ($userId, $namaUser, $ticketTypeId, $jumlah): Registration {
            $tipe = $this->tickets->summary($ticketTypeId)
                ?? abort(404, 'Jenis tiket tidak ditemukan.');

            $this->events->ensureOpen($tipe->eventId);

            $peserta = Participant::firstOrCreate(
                ['user_id' => $userId],
                ['nama_lengkap' => $namaUser],
            );

            $registration = Registration::create([
                'participant_id' => $peserta->id,
                'event_id' => $tipe->eventId,
                'ticket_type_id' => $tipe->id,
                'kode_pendaftaran' => 'REG-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'jumlah' => $jumlah,
                'harga_satuan_snapshot' => $tipe->harga,
                'total' => $tipe->harga * $jumlah,
                'status' => Registration::STATUS_PENDING,
            ]);

            $this->tickets->reserveAndIssue($tipe->id, $jumlah, $registration->id, $userId);

            $registration->update(['status' => Registration::STATUS_CONFIRMED]);

            return $registration;
        });

        // Dikirim setelah transaksi commit, jadi listener tidak melihat data yang akhirnya dibatalkan.
        event(new PendaftaranDikonfirmasi($registration->id, $registration->event_id, $registration->jumlah));

        return $registration;
    }
}