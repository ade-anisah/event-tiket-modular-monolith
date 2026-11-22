<?php

namespace App\Modules\EventModule\Models;

use App\Modules\EventModule\Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['nama', 'slug'])]
#[UseFactory(CategoryFactory::class)]
class Category extends Model
{
    use HasFactory;

    /**
     * Relasi Many-to-Many ke Event lewat tabel pivot category_event.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'category_event')->withTimestamps();
    }
}
