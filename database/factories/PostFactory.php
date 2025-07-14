<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => fn(array $attributes) => Str::slug($attributes['title']),
            'description' => $this->faker->text(100),
            'content' => $this->faker->paragraph(),
            'publish_date' => now(),
            'status' => 0, // 0: mới, 1: cập nhật, 2: khác
            //'user_id' => User::inRandomOrder()->first()->id,
            'user_id' => '12', // Use factory to create a user
        ];
    }

}
