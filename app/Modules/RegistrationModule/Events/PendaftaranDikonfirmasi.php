<?php

namespace App\Modules\RegistrationModule\Events;

/**
 * Domain Event: dikirim setelah pendaftaran berhasil dan transaksi database commit.
 */
final class PendaftaranDikonfirmasi
{
    public function __construct(
        public readonly int $registrationId,
        public readonly int $eventId,
        public readonly int $jumlah,
    ) {}
}
