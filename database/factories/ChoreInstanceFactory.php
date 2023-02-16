<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChoreInstanceFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'  => User::factory(),
            'due_date' => $this->faker->dateTimeBetween('+0 days', '+1 year'),
        ];
    }

    /**
     * Creates a chore instance due today.
     */
    public function dueToday(): Factory
    {
        return $this->state(['due_date' => today()]);
    }

    /**
     * Creates a chore instance due today.
     */
    public function pastDue(): Factory
    {
        return $this->state([
            'due_date' => $this->faker->dateTimeBetween('-1 year', '+0 days'),
        ]);
    }

    /**
     * Creates a chore instance already completed.
     */
    public function completed(): Factory
    {
        return $this->state([
            'completed_date' => $this->faker->dateTimeBetween('-1 year', '+0 days'),
        ]);
    }
}
