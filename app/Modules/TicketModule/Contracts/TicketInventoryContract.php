<?php

namespace App\Modules\TicketModule\Contracts;

use App\Modules\TicketModule\Exceptions\KuotaTiketPenuhException;

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
     * @throws KuotaTiketPenuhException
     */
    public function reserveAndIssue(int $ticketTypeId, int $jumlah, int $registrationId, int $userId): array;
}
