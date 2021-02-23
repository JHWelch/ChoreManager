<?php

namespace Database\Factories;

use App\Models\Chore;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ChoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chore::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'       => implode(' ', $this->faker->words(3)),
            'description' => $this->faker->sentence(),
            'frequency'   => Arr::random(array_keys(Chore::FREQUENCIES)),
            'user_id'     => User::factory()->create()->id,
        ];
    }
}
