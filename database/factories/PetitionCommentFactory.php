<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kneu\Petition\Petition;
use Kneu\Petition\PetitionComment;
use Kneu\Petition\User;

/**
 * @extends Factory<PetitionComment>
 */
class PetitionCommentFactory extends Factory
{
    protected $model = PetitionComment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'petition_id' => Petition::factory(),
            'content' => fake()->paragraph(),
            'user_id' => User::factory(),
        ];
    }
}
