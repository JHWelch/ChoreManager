<?php

namespace Database\Factories;

use App\Enums\Frequency;
use App\Models\Chore;
use App\Models\ChoreInstance;
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
            'title'        => implode(' ', $this->faker->words(3)),
            'description'  => $this->faker->sentence(),
            'frequency_id' => Arr::random(Frequency::FREQUENCIES),
            'user_id'      => User::factory()->create()->id,
        ];
    }

    /**
     * Indicate that the user should have a personal team.
     *
     * @return $this
     */
    public function withFirstInstance()
    {
        return $this->has(
            ChoreInstance::factory()
                ->state(function (array $attributes, Chore $chore) {
                    return ['user_id' => $chore->user->id];
                }));
    }
}
