<?php

namespace App\Modules\EventModule\Database\Factories;

use App\Modules\EventModule\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $judul = fake()->unique()->sentence(3);
        $mulai = fake()->dateTimeBetween('+1 week', '+2 months');

        return [
            'judul' => $judul,
            'slug' => Str::slug($judul).'-'.fake()->unique()->numberBetween(1, 99999),
            'deskripsi' => fake()->paragraph(),
            'lokasi' => fake()->city(),
            'mulai_at' => $mulai,
            'selesai_at' => (clone $mulai)->modify('+3 hours'),
            'status' => Event::STATUS_PUBLISHED,
            'peserta_terdaftar' => 0,
            'created_by' => 1,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => Event::STATUS_DRAFT]);
    }
}
