<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kneu\Petition\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->numberBetween(1, 1000000),
            'email' => fake()->unique()->safeEmail(),
            'type' => fake()->randomElement(['student', 'teacher', 'simple']),
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'role' => null,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin']);
    }
}
