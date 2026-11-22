<?php

namespace App\Modules\EventModule\Contracts;

interface EventLookupContract
{
    /**
     * Cari event berdasarkan id (semua status). Null bila tidak ada.
     */
    public function find(int $eventId): ?EventSummary;

    /**
     * Cari event yang berstatus published. Null bila tidak ada atau belum dibuka.
     */
    public function findPublished(int $eventId): ?EventSummary;

    /**
     * Pastikan event ada dan published.
     *
     * @throws \App\Modules\EventModule\Exceptions\EventTidakDibukaException
     */
    public function ensureOpen(int $eventId): void;
}