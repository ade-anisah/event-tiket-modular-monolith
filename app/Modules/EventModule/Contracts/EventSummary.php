<?php

namespace App\Modules\EventModule\Contracts;

use DateTimeInterface;

/**
 * Objek data ringkas yang diberikan EventModule ke modul lain.
 * Modul lain tidak pernah menerima model Eloquent milik EventModule.
 */
final readonly class EventSummary
{
    public function __construct(
        public int $id,
        public string $judul,
        public string $lokasi,
        public string $status,
        public DateTimeInterface $mulaiAt,
    ) {}
}
