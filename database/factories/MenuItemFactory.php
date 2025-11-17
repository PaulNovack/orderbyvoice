<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(),
            'category_id' => \App\Models\MenuCategory::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->optional()->sentence(),
            'is_active' => fake()->boolean(90),
        ];
    }
}
