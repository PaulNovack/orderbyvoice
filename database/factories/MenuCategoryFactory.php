<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuCategory>
 */
class MenuCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Burgers', 'Pizza', 'Sides', 'Entrées', 'Salads', 'Desserts', 'Beverages']);

        return [
            'company_id' => \App\Models\Company::factory(),
            'name' => $name,
            'slug' => str($name)->slug(),
        ];
    }
}
