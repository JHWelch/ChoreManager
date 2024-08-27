<?php

namespace Database\Factories;

use App\Enums\FrequencyType;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<Chore>
 */
class ChoreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => implode(' ', $this->faker->words(3)),
            'description' => $this->faker->sentence(),
            'frequency_id' => Arr::random(FrequencyType::cases()),
            'user_id' => User::factory(),
        ];
    }

    public function repeatable(): static
    {
        return $this->state(['frequency_id' => Arr::random(array_filter(
            FrequencyType::cases(),
            fn ($id) => $id !== FrequencyType::doesNotRepeat
        ))]);
    }

    public function withFirstInstance(
        ?Carbon $due_date = null,
        int|User|null $user_id = null,
    ): static {
        return $this->has(
            ChoreInstance::factory()
                ->state(fn (array $_, Chore $chore) => array_filter([ // @phpstan-ignore-line
                    'user_id' => $user_id ?? $chore->user->id,
                    'due_date' => $due_date,
                ]))
        );
    }

    public function assignedToTeam(): static
    {
        return $this->state(['user_id' => null]);
    }

    public function daily(): static
    {
        return $this->state(['frequency_id' => FrequencyType::daily]);
    }
}
