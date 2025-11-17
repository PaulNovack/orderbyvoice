<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Addon>
 */
class AddonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'addon_group_id' => \App\Models\AddonGroup::factory(),
            'name' => fake()->word(),
            'type' => fake()->optional()->randomElement(['cheese', 'veg', 'meat', 'protein', 'sauce']),
            'is_active' => fake()->boolean(90),
        ];
    }
}
