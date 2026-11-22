<?php

namespace App\Modules\EventModule\Listeners;

use App\Modules\EventModule\Models\Event;
use App\Modules\RegistrationModule\Events\PendaftaranDikonfirmasi;

class TambahPesertaTerdaftar
{
    /**
     * Tambah penghitung peserta pada event yang bersangkutan.
     */
    public function handle(PendaftaranDikonfirmasi $pesan): void
    {
        Event::query()
            ->whereKey($pesan->eventId)
            ->increment('peserta_terdaftar', $pesan->jumlah);
    }
}