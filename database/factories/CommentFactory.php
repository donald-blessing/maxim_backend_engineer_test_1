<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment' => $this->faker->text(100),
            'comment_id' => Comment::query()->inRandomOrder()->first()?->id,
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'movie_id' => Movie::query()->inRandomOrder()->first()->id,
            'ip_address' => $this->faker->ipv4,
        ];
    }
}
