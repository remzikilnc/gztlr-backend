<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word,
            'slug' => fake()->unique()->slug,
            'description' => fake()->paragraph,
            'image' => fake()->imageUrl(),
            'status' => fake()->boolean(80)
        ];

    }
}