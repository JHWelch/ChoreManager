<?php

namespace Database\Factories;

use App\Enums\FrequencyType;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ChoreFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title'        => implode(' ', $this->faker->words(3)),
            'description'  => $this->faker->sentence(),
            'frequency_id' => Arr::random(FrequencyType::cases()),
            'user_id'      => User::factory(),
        ];
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withFirstInstance($due_date = null, $user_id = null): static
    {
        return $this->has(
            ChoreInstance::factory()
                ->state(function (array $_, Chore $chore) use ($due_date, $user_id) {
                    return array_merge(
                        ['user_id' => $user_id ?? $chore->user->id],
                        $due_date ? ['due_date' => $due_date] : []
                    );
                })
        );
    }

    /**
     * Creates a chore that is assigned to the team, not an individual user.
     */
    public function assignedToTeam(): Factory
    {
        return $this->state(['user_id' => null]);
    }

    public function daily()
    {
        return $this->state(['frequency_id' => FrequencyType::daily]);
    }
}
