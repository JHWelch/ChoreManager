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
            'user_id'      => User::factory(),
        ];
    }

    /**
     * Indicate that the user should have a personal team.
     *
     * @return $this
     */
    public function withFirstInstance($due_date = null)
    {
        return $this->has(
            ChoreInstance::factory()
                ->state(function (array $attributes, Chore $chore) use ($due_date) {
                    return array_merge(
                        ['user_id' => $chore->user->id],
                        $due_date ? ['due_date' => $due_date] : []
                    );
                }));
    }
}
