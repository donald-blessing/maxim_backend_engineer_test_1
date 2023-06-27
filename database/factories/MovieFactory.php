<?php

namespace Database\Factories;

use App\Models\Movie;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use JsonException;

/**
 * @extends Factory<Movie>
 */
class MovieFactory extends Factory
{
    protected array $genre = [
        'action',
        'comedy',
        'drama',
        'horror',
        'romance',
        'sci-fi',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws JsonException
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'release_date' => $this->faker->date,
            'rating' => $this->faker->numberBetween(1, 10),
            'country' => $this->faker->country,
            'genre' => json_encode($this->getGenres($this->faker->numberBetween(1, count($this->genre))), JSON_THROW_ON_ERROR),
            'photo' => $this->faker->imageUrl(),
        ];
    }

    /**
     * @param int $count
     * @return array
     */
    protected function getGenres(int $count = 1): array
    {
        $genres = [];

        for ($i = 0; $i < $count; $i++) {
            $genres[] = $this->genre[$i];
        }

        return $genres;
    }

}
