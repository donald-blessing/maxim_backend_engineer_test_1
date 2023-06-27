<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class MoviesTest extends TestCase
{
    use WithFaker;

    protected array $genre = [
        'action',
        'comedy',
        'drama',
        'horror',
        'romance',
        'sci-fi',
    ];

    protected array $mime = ['jpeg', 'png', 'jpg', 'gif', 'svg'];

    public function test_get_movies_index(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/movies');

        $response->assertSuccessful();
    }

    /**
     * A basic feature test example.
     */
    public function test_get_movies_show(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/movies/' . $movie->id);

        $response->assertSuccessful();
    }

    public function test_store_movies(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'release_date' => $this->faker->date,
            'rating' => $this->faker->numberBetween(1, 10),
            'country' => $this->faker->country,
            'genre' => $this->getGenres($this->faker->numberBetween(1, count($this->genre))),
            'photo' => UploadedFile::fake()->create(Str::random(8) . '.' . $this->faker->randomElement($this->mime)),
        ];
        $response = $this->actingAs($user)->post('/api/movies', $payload);

        $response->assertSuccessful();
    }

    public function test_update_movies(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $payload = [
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'release_date' => $this->faker->date,
            'rating' => $this->faker->numberBetween(1, 10),
            'country' => $this->faker->country,
            'genre' => $this->getGenres($this->faker->numberBetween(1, count($this->genre))),
            'photo' => UploadedFile::fake()->create(Str::random(8) . '.' . $this->faker->randomElement($this->mime)),
        ];
        $response = $this->actingAs($user)->put('/api/movies/' . $movie->id, $payload);

        $response->assertSuccessful();
    }

    public function test_destroy_movie(): void
    {
        $movie = Movie::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete('/api/movies/' . $movie->id);

        $response->assertSuccessful();
    }

    protected function getGenres(int $count = 1): array
    {
        $genres = [];

        for ($i = 0; $i < $count; $i++) {
            $genres[] = $this->genre[$i];
        }

        return $genres;
    }
}
