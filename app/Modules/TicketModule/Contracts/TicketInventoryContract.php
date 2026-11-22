<?php

namespace App\Modules\TicketModule\Contracts;

interface TicketInventoryContract
{
    /**
     * Ringkasan satu jenis tiket. Null bila tidak ditemukan.
     */
    public function summary(int $ticketTypeId): ?TicketTypeSummary;

    /**
     * Kurangi kuota dan terbitkan tiket berkode unik secara atomik.
     *
     * @return list<string> daftar kode tiket yang diterbitkan
     *
     * @throws \App\Modules\TicketModule\Exceptions\KuotaTiketPenuhException
     */
    public function reserveAndIssue(int $ticketTypeId, int $jumlah, int $registrationId, int $userId): array;
}