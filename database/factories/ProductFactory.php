<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'price' => fake()->numerify('#####'),
            'currency' => Product::DEFAULT_CURRENCY,
            'display_image_url' => Product::DEFAULT_IMAGE,
            'category_id' => Category::factory(),
        ];
    }
}
