<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jugador>
 */
class JugadorFactory extends Factory
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
            'edat' => fake()->numberBetween(16, 35),
            'pais' => fake()->country(),
            'posicio' => fake()->randomElement([
                'POR', 'DEF', 'MC', 'DEL'
            ]),
            'força' => fake()->numberBetween(50, 100),
            'valor_mercat' => fake()->numberBetween(1, 20),
            'gols' => 0,
            'targetes_grogues' => 0,
            'targetes_vermelles' => 0,
            'nombre_partits' => 0
        ];
    }
}
