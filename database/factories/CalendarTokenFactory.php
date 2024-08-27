<?php

namespace Database\Factories;

use App\Models\CalendarToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CalendarToken>
 */
class CalendarTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'token' => Str::uuid(),
        ];
    }
}
