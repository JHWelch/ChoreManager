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
}
