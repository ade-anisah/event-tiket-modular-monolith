<?php

namespace App\Modules\EventModule\Database\Factories;

use App\Modules\EventModule\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $nama = fake()->unique()->words(2, true);

        return [
            'nama' => $nama,
            'slug' => Str::slug($nama),
        ];
    }
}
