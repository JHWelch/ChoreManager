<?php

namespace Database\Factories;

use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChoreInstance>
 */
class ChoreInstanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'due_date' => $this->faker->dateTimeBetween('+0 days', '+1 year'),
        ];
    }

    public function dueToday(): static
    {
        return $this->state(['due_date' => today()]);
    }

    public function pastDue(): static
    {
        return $this->state([
            'due_date' => $this->faker->dateTimeBetween('-1 year', '+0 days'),
        ]);
    }

    public function completed(): static
    {
        return $this->state([
            'completed_date' => $this->faker->dateTimeBetween('-1 year', '+0 days'),
        ]);
    }
}
