<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\MovieCharacter;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MovieCharacter>
 */
class MovieCharacterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'movie_id' => Movie::query()->inRandomOrder()->first()->id,
            'name' => $this->faker->name,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'height' => random_int(100, 200),
        ];
    }
}
