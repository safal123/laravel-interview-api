<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'category' => $this->faker->randomElement(['Gold', 'Silver', 'Bronze']),
            'start_date' => $this->faker->date(),
            'reference' => strtoupper($this->faker->word),
        ];
    }
}
