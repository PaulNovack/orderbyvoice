<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddonGroup>
 */
class AddonGroupFactory extends Factory
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
            'name' => fake()->words(2, true),
            'applies_to_category_id' => fake()->optional()->randomElement(\App\Models\MenuCategory::pluck('id')->toArray()),
            'applies_to_item_id' => fake()->optional()->randomElement(\App\Models\MenuItem::pluck('id')->toArray()),
            'min_select' => fake()->numberBetween(0, 2),
            'max_select' => fake()->optional()->numberBetween(1, 5),
            'required' => fake()->boolean(30),
        ];
    }
}
