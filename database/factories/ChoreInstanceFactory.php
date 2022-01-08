<?php

namespace Database\Factories;

use App\Models\ChoreInstance;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChoreInstanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChoreInstance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'due_date' => $this->faker->dateTimeBetween('+0 days', '+1 year'),
        ];
    }

    /**
     * Creates a chore instance due today.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function dueToday()
    {
        return $this->state(function (array $attributes) {
            return [
                'due_date' => today(),
            ];
        });
    }

    /**
     * Creates a chore instance already completed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'completed_date' => $this->faker->dateTimeBetween('-1 year', '+0 days'),
            ];
        });
    }
}
