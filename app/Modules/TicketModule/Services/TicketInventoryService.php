<?php

namespace App\Modules\TicketModule\Services;

use App\Modules\TicketModule\Contracts\TicketInventoryContract;
use App\Modules\TicketModule\Contracts\TicketTypeSummary;
use App\Modules\TicketModule\Exceptions\KuotaTiketPenuhException;
use App\Modules\TicketModule\Models\Ticket;
use App\Modules\TicketModule\Models\TicketType;
use Illuminate\Support\Facades\DB;

class TicketInventoryService implements TicketInventoryContract
{
    public function __construct(private readonly TicketCodeGenerator $generator) {}

    public function summary(int $ticketTypeId): ?TicketTypeSummary
    {
        $type = TicketType::find($ticketTypeId);

        if (! $type) {
            return null;
        }

        return new TicketTypeSummary(
            id: $type->id,
            eventId: $type->event_id,
            nama: $type->nama,
            harga: $type->harga,
            kuota: $type->kuota,
            terjual: $type->terjual,
            maksPerPembelian: $type->maks_per_pembelian,
        );
    }

    public function reserveAndIssue(int $ticketTypeId, int $jumlah, int $registrationId, int $userId): array
    {
        return DB::transaction(function () use ($ticketTypeId, $jumlah, $registrationId, $userId): array {
            $type = TicketType::findOrFail($ticketTypeId);

            // Satu pernyataan UPDATE bersyarat: kolom terjual hanya bertambah bila kuota masih cukup.
            // Jumlah baris yang berubah 0 berarti kuota tidak mencukupi.
            $berubah = TicketType::query()
                ->whereKey($ticketTypeId)
                ->whereRaw('terjual + ? <= kuota', [$jumlah])
                ->increment('terjual', $jumlah);

            if ($berubah === 0) {
                throw KuotaTiketPenuhException::untuk($type->nama, $type->sisa(), $jumlah);
            }

            $daftarKode = [];

            for ($i = 0; $i < $jumlah; $i++) {
                $kode = $this->generator->generate();

                Ticket::create([
                    'ticket_type_id' => $type->id,
                    'registration_id' => $registrationId,
                    'user_id' => $userId,
                    'kode' => $kode,
                    'status' => 'aktif',
                    'harga_snapshot' => $type->harga,
                ]);

                $daftarKode[] = $kode;
            }

            return $daftarKode;
        });
    }
}