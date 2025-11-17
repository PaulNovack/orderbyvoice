<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemPrice>
 */
class ItemPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => \App\Models\MenuItem::factory(),
            'size_id' => fake()->optional()->randomElement(\App\Models\MenuSize::pluck('id')->toArray()),
            'base_price' => fake()->randomFloat(2, 5, 50),
        ];
    }
}
