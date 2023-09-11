<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' =>  'sotiris.zois',
            'password' => Hash::make('test1'),
            'role' => $this->faker->randomElement(['doctor','patient'])
        ];
    }

    /**
     * Indicate whether the model belongs to a Doctor.
     */
    public function isDoctor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' =>  'doctor',
        ]);
    }

    /**
     * Indicate whether the model belongs to a Patient.
     */
    public function isPatient(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' =>  'patient',
        ]);
    }
}
