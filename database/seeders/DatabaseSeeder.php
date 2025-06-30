<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Database\Seeders\PostSeeder; // Assuming you have a PostSeeder to seed posts

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class); 
        $this->call(PostSeeder::class);
    }
}
