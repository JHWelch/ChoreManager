<?php

namespace App\Rules;

use App\Enums\Frequency;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FrequencyDayOf implements ValidationRule
{
    protected int $frequency_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $frequency_id)
    {
        $this->frequency_id = $frequency_id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        switch ($this->frequency_id) {
            case Frequency::DOES_NOT_REPEAT:
                if ($value !== null) {
                    $fail('Does not repeat frequency cannot have specific day.');
                }
                break;
            case Frequency::DAILY:
                if ($value !== null) {
                    $fail('Daily frequency cannot have specific day.');
                }
                break;
            case Frequency::WEEKLY:
                if ($value < 1 || $value > 7) {
                    $fail('Day of the week must be between 1 and 7.');
                }
                break;
            case Frequency::MONTHLY:
                if ($value < 1 || $value > 31) {
                    $fail('Day of the month must be between 1 and 31.');
                }
                break;
            case Frequency::QUARTERLY:
                if ($value < 1 || $value > 92) {
                    $fail('Day of the quarter must be between 1 and 92');
                }
                break;
            case Frequency::YEARLY:
                if ($value < 1 || $value > 365) {
                    $fail('Day of the year must be between 1 and 365');
                }
                break;
            default:
                $fail('Invalid frequency ' . $this->frequency_id);
        }
    }
}
