<?php

namespace App\Modules\RegistrationModule\Database\Factories;

use App\Modules\RegistrationModule\Models\Participant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Participant>
 */
class ParticipantFactory extends Factory
{
    protected $model = Participant::class;

    public function definition(): array
    {
        return [
            'user_id' => fake()->unique()->numberBetween(100, 99999),
            'nama_lengkap' => fake()->name(),
            'no_hp' => fake()->numerify('08##########'),
        ];
    }
}