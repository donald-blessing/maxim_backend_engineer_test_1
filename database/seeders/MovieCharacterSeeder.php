<?php

namespace Database\Seeders;

use App\Models\MovieCharacter;
use Illuminate\Database\Seeder;

class MovieCharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MovieCharacter::factory(50)->create();
    }
}
