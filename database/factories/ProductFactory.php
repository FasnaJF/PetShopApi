<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category_uuids = Category::select('uuid')->get();
        $category_uuid = fake()->randomElement($category_uuids)->uuid;

        $brand_uuids = Brand::select('uuid')->get();
        $brand_uuid = fake()->randomElement($brand_uuids)->uuid;

        return [
            'category_uuid' => $category_uuid,
            'title' => fake()->words(rand(6,10),true),
            'uuid' => Str::uuid(),
            'price' => fake()->randomFloat(2,10,10000),
            'description' => fake()->sentence(),
            'metadata' => json_encode([
                'brand'=> $brand_uuid,
                'image' =>''
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
