<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->words(rand(1, 2), true);
        $slug = Str::slug($title);
        return [
            'uuid' => Str::uuid(),
            'title' => $title,
            'slug' => $slug,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
