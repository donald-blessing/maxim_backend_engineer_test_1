<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UsersTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_users_index(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/users');

        $response->assertSuccessful();
    }

    /**
     * A basic feature test example.
     */
    public function test_get_users_show(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/users/' . $user->id);

        $response->assertSuccessful();
    }

    public function test_destroy_user(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete('/api/users/' . $user->id);

        $response->assertSuccessful();
    }
}
