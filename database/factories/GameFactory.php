<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'poster' => 'game' . fake()->word(1) . '.jpg',
            'backdrop' => 'game' . fake()->word(1) . '_backdrop.jpg',
            'released_date' => fake()->date(),
            'metacritic_url' => fake()->url(),
            'metacritic_score' => fake()->numberBetween(0, 100),
            'is_free' => fake()->boolean(),
            'is_published' => fake()->boolean(),
            'allow_update' => fake()->boolean(),
            'about_the_game' => fake()->text(),
            'requirements_min' => fake()->text(),
            'requirements_rec' => fake()->text(),
        ];
    }
}
