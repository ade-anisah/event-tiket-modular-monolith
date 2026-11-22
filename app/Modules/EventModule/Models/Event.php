<?php

namespace App\Modules\EventModule\Models;

use App\Modules\EventModule\Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable(['judul', 'slug', 'deskripsi', 'lokasi', 'mulai_at', 'selesai_at', 'status', 'created_by'])]
#[UseFactory(EventFactory::class)]
class Event extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_CLOSED = 'closed';

    public const STATUSES = [self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_CLOSED];

    protected function casts(): array
    {
        return [
            'mulai_at' => 'datetime',
            'selesai_at' => 'datetime',
        ];
    }

    /**
     * Relasi Many-to-Many ke Category lewat tabel pivot category_event.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_event')->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Buat slug dari judul dan pastikan unik (tambah angka bila sudah dipakai).
     */
    public static function uniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $dasar = Str::slug($judul) ?: 'event';
        $slug = $dasar;
        $urutan = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $dasar.'-'.$urutan++;
        }

        return $slug;
    }
}