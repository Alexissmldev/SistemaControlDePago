<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name'     => fake()->firstName(),
            'last_name'      => fake()->lastName(),
            'dni'            => 'V-' . fake()->unique()->numberBetween(10000000, 35000000),
            'birthdate'      => fake()->date(),
            'representative' => fake()->name(),
            'phone'          => fake()->numerify('###########'),
            'status'         => fake()->randomElement(['active', 'inactive']),
        ];
    }
}