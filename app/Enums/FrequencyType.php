<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum FrequencyType : int
{
    case doesNotRepeat = 0;
    case daily         = 1;
    case weekly        = 2;
    case monthly       = 3;
    case quarterly     = 4;
    case yearly        = 5;

    public function adjective()
    {
        return match ($this) {
            self::doesNotRepeat => 'Does not repeat',
            self::daily         => 'Daily',
            self::weekly        => 'Weekly',
            self::monthly       => 'Monthly',
            self::quarterly     => 'Quarterly',
            self::yearly        => 'Yearly',
        };
    }

    public function noun()
    {
        return match ($this) {
            self::doesNotRepeat => 'Does not repeat',
            self::daily         => 'Days',
            self::weekly        => 'Weeks',
            self::monthly       => 'Months',
            self::quarterly     => 'Quarters',
            self::yearly        => 'Years',
        };
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function nounsAsSelectOptions(): array
    {
        return Arr::map(self::cases(), fn ($frequencyType) => [
            'value' => $frequencyType->value,
            'label' => $frequencyType->noun(),
        ]);
    }

    /**
     * @return array<int, array<string, FrequencyType|string>>
     */
    public static function adjectivesAsSelectOptions(): array
    {
        return Arr::map(self::cases(), fn ($frequencyType) => [
            'value' => $frequencyType->value,
            'label' => $frequencyType->noun(),
        ]);
    }
}
