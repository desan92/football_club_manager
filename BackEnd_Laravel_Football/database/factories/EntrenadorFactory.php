<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entrenador>
 */
class EntrenadorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'nom' => fake()->firstName(),
            'cognom' => fake()->lastName(),
            'edat' => fake()->numberBetween(40, 60),
            'pais' => fake()->country(),
        ];
    }
}
