<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddonPrice>
 */
class AddonPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'addon_id' => \App\Models\Addon::factory(),
            'size_id' => fake()->optional()->randomElement(\App\Models\MenuSize::pluck('id')->toArray()),
            'price' => fake()->randomFloat(2, 0.5, 10),
        ];
    }
}
