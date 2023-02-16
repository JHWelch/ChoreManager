<?php

namespace App\Rules;

use App\Enums\Frequency;
use Illuminate\Contracts\Validation\Rule;

class FrequencyDayOf implements Rule
{
    protected int $frequency_id;

    /**
     * Create a new rule instance.
     *
     * @param int $frequency_id
     * @return void
     */
    public function __construct(int $frequency_id)
    {
        $this->frequency_id = $frequency_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes(string $attribute, $value): bool
    {
        return match (intval($this->frequency_id)) {
            Frequency::DOES_NOT_REPEAT => ($value === null),
            Frequency::DAILY           => ($value === null),
            Frequency::WEEKLY          => ($value >= 1 && $value <= 7),
            Frequency::MONTHLY         => ($value >= 1 && $value <= 31),
            Frequency::QUARTERLY       => ($value >= 1 && $value <= 92),
            Frequency::YEARLY          => ($value >= 1 && $value <= 365),
            default                    => false,
        };
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return match ($this->frequency_id) {
            Frequency::DOES_NOT_REPEAT => 'Does not repeat frequency cannot have specific day.',
            Frequency::DAILY           => 'Daily frequency cannot have specific day.',
            Frequency::WEEKLY          => 'Day of the week must be between 1 and 7.',
            Frequency::MONTHLY         => 'Day of the month must be between 1 and 31.',
            Frequency::QUARTERLY       => 'Day of the quarter must be between 1 and 92',
            Frequency::YEARLY          => 'Day of the year must be between 1 and 365',
            default                    => 'Invalid frequency ' . $this->frequency_id,
        };
    }
}
