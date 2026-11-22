<?php

namespace App\Modules\TicketModule\Models;

use App\Modules\TicketModule\Database\Factories\TicketTypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['event_id', 'nama', 'harga', 'kuota', 'maks_per_pembelian'])]
#[UseFactory(TicketTypeFactory::class)]
class TicketType extends Model
{
    use HasFactory;

    /**
     * Relasi One-to-Many: satu jenis tiket menerbitkan banyak tiket.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function sisa(): int
    {
        return max(0, $this->kuota - $this->terjual);
    }
}
