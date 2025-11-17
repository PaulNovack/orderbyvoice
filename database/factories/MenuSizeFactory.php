<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuSize>
 */
class MenuSizeFactory extends Factory
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
            'name' => fake()->randomElement(['Small', 'Medium', 'Large', '10"', '12"', '16"']),
            'size_note' => fake()->optional()->randomElement(['1/3 lb', '1/2 lb', '1 lb']),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
