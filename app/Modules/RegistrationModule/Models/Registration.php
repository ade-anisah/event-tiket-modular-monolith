<?php

namespace App\Modules\RegistrationModule\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'participant_id', 'event_id', 'ticket_type_id', 'kode_pendaftaran',
    'jumlah', 'harga_satuan_snapshot', 'total', 'status',
])]
class Registration extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }
}