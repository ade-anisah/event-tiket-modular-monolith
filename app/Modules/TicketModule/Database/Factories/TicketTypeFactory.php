<?php

namespace App\Modules\TicketModule\Database\Factories;

use App\Modules\TicketModule\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketType>
 */
class TicketTypeFactory extends Factory
{
    protected $model = TicketType::class;

    public function definition(): array
    {
        return [
            'event_id' => 1,
            'nama' => fake()->randomElement(['Regular', 'VIP', 'Early Bird']),
            'harga' => fake()->numberBetween(5, 50) * 10000,
            'kuota' => fake()->numberBetween(10, 100),
            'terjual' => 0,
            'maks_per_pembelian' => 4,
        ];
    }
}