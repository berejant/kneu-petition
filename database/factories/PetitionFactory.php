<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kneu\Petition\Petition;
use Kneu\Petition\User;

/**
 * @extends Factory<Petition>
 */
class PetitionFactory extends Factory
{
    protected $model = Petition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(8),
            'content' => fake()->paragraphs(3, true),
            'user_id' => User::factory(),
        ];
    }

    public function closed(): static
    {
        return $this->state(fn () => ['is_closed' => true]);
    }
}
