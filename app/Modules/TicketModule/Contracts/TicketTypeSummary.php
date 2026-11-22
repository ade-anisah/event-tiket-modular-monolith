<?php

namespace App\Modules\TicketModule\Contracts;

/**
 * Objek data ringkas yang diberikan TicketModule ke modul lain.
 */
final readonly class TicketTypeSummary
{
    public function __construct(
        public int $id,
        public int $eventId,
        public string $nama,
        public int $harga,
        public int $kuota,
        public int $terjual,
        public int $maksPerPembelian,
    ) {}

    public function sisa(): int
    {
        return max(0, $this->kuota - $this->terjual);
    }
}