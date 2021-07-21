<?php

namespace App\Enums;

use Carbon\Carbon;

/**
 * TODO: PHP 8.1 will have enums.
 * "All I want is to be a real Enum!".
 */
class Frequency
{
    const DOES_NOT_REPEAT = 0;
    const DAILY           = 1;
    const WEEKLY          = 2;
    const MONTHLY         = 3;
    const QUARTERLY       = 4;
    const YEARLY          = 5;

    const FREQUENCIES = [
        self::DOES_NOT_REPEAT,
        self::DAILY,
        self::WEEKLY,
        self::MONTHLY,
        self::QUARTERLY,
        self::YEARLY,
    ];

    const ADJECTIVES = [
        self::DOES_NOT_REPEAT => 'Does not repeat',
        self::DAILY           => 'Daily',
        self::WEEKLY          => 'Weekly',
        self::MONTHLY         => 'Monthly',
        self::QUARTERLY       => 'Quarterly',
        self::YEARLY          => 'Yearly',
    ];

    const NOUNS = [
        self::DOES_NOT_REPEAT => 'Does not repeat',
        self::DAILY           => 'Days',
        self::WEEKLY          => 'Weeks',
        self::MONTHLY         => 'Months',
        self::QUARTERLY       => 'Quarters',
        self::YEARLY          => 'Years',
    ];

    public $id;
    public $interval;

    public function __construct($id, $interval = 1)
    {
        $this->id       = $id;
        $this->interval = $interval;
    }

    public function adjective()
    {
        return self::ADJECTIVES[$this->id];
    }

    public function noun()
    {
        return self::NOUNS[$this->id];
    }

    public static function nounsAsSelectOptions()
    {
        return self::frequenciesAsSelectOptions(self::NOUNS);
    }

    public static function adjectivesAsSelectOptions()
    {
        return self::frequenciesAsSelectOptions(self::ADJECTIVES);
    }

    public static function frequenciesAsSelectOptions($words)
    {
        $frequencies = [];

        foreach ($words as $key => $frequency) {
            $frequencies[] = ['value' => $key, 'label' => $frequency];
        }

        return $frequencies;
    }

    public function __toString()
    {
        return $this->toPrefixedString();
    }

    public function toPrefixedString($prefix = '')
    {
        if ($this->id === self::DOES_NOT_REPEAT) {
            return self::ADJECTIVES[$this->id];
        }

        if ($this->interval === 1) {
            return $prefix
                ? $prefix . ' ' . lcfirst(self::ADJECTIVES[$this->id])
                : self::ADJECTIVES[$this->id];
        }

        return $prefix
            ? "$prefix every " . $this->interval . ' ' . lcfirst(self::NOUNS[$this->id])
            : 'Every ' . $this->interval . ' ' . lcfirst(self::NOUNS[$this->id]);
    }

    /**
     * Get the next date after a given date based on Frequency.
     *
     * @param Carbon $date optional
     * @return Carbon|null
     */
    public function getNextDate(Carbon $after = null)
    {
        $after = $after ?? today();

        $i = $this->interval;

        return match ($this->id) {
            0 => null,
            1 => $after->addDays($i),
            2 => $after->addWeeks($i),
            3 => $after->addMonthsNoOverflow($i),
            4 => $after->addQuartersNoOverflow($i),
            5 => $after->addYearsNoOverflow($i),
        };
    }
}
