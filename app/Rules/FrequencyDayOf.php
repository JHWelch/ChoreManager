<?php

namespace App\Rules;

use App\Enums\FrequencyType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FrequencyDayOf implements ValidationRule
{
    public function __construct(
        protected FrequencyType $frequencyType
    ) {
    }

    /**
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $_, mixed $value, Closure $fail): void
    {
        match ($this->frequencyType) {
            FrequencyType::doesNotRepeat => $this->nullCheck($value, $fail),
            FrequencyType::daily => $this->nullCheck($value, $fail),
            FrequencyType::weekly => $this->rangeCheck($value, 1, 7, $fail),
            FrequencyType::monthly => $this->rangeCheck($value, 1, 31, $fail),
            FrequencyType::quarterly => $this->rangeCheck($value, 1, 92, $fail),
            FrequencyType::yearly => $this->rangeCheck($value, 1, 365, $fail),
        };
    }

    protected function rangeCheck(
        ?int $value,
        int $min,
        int $max,
        Closure $fail
    ): void {
        if (is_null($value) || $value < $min || $value > $max) {
            $fail("Day of the {$this->frequencyType->noun()} must be between $min and $max.");
        }
    }

    protected function nullCheck(?int $value, Closure $fail): void
    {
        if (! is_null($value)) {
            $fail("{$this->frequencyType->noun()} frequency cannot have specific day.");
        }
    }
}
