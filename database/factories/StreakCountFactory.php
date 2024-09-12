<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StreakCount>
 */
class StreakCountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'count' => $this->faker->randomNumber(),
        ];
    }
}
