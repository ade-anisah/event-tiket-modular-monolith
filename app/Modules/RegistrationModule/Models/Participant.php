<?php

namespace App\Modules\RegistrationModule\Models;

use App\Modules\RegistrationModule\Database\Factories\ParticipantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'nama_lengkap', 'no_hp'])]
#[UseFactory(ParticipantFactory::class)]
class Participant extends Model
{
    use HasFactory;

    /**
     * Relasi One-to-Many: satu peserta punya banyak pendaftaran.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
