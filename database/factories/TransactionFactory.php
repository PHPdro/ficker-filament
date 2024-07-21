<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'description' => fake()->name(),
            'type' => 'income',
            'category_id' => random_int(1, 5),
            'value' => random_int(1, 1000), 
            'date' => random_int(2023, 2024) . '-' . random_int(01, 12) . '-' . random_int(1, 30),
        ];
    }
}
