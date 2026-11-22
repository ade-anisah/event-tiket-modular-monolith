<?php

namespace App\Modules\TicketModule\Services;

use App\Modules\TicketModule\Models\Ticket;
use Illuminate\Support\Str;

class TicketCodeGenerator
{
    /**
     * Buat kode tiket acak yang belum dipakai. Indeks unik di kolom tickets.kode
     * menjadi pengaman terakhir bila dua kode kebetulan sama.
     */
    public function generate(): string
    {
        do {
            $kode = 'TKT-'.Str::upper(Str::random(10));
        } while (Ticket::where('kode', $kode)->exists());

        return $kode;
    }
}