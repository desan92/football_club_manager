<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Club>
 */
class ClubFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nom_club' => fake()->name(),
            'escut' => fake()->image(),
            'email' => fake()->unique()->safeEmail(),
            'telefon' => fake()->phoneNumber(),
            'ciutat' => fake()->city(),
            'estadi' => fake()->name(),
            'founded' => fake()->year()
        ];
    }
}
