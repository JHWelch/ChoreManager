<?php

namespace App\Enums;

use Carbon\Carbon;
use InvalidArgumentException;

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

    const DAYS_OF_THE_WEEK_AS_SELECT_OPTIONS = [
        ['value' => Carbon::MONDAY,    'label' => 'Mondays'],
        ['value' => Carbon::TUESDAY,   'label' => 'Tuesdays'],
        ['value' => Carbon::WEDNESDAY, 'label' => 'Wednesdays'],
        ['value' => Carbon::THURSDAY,  'label' => 'Thursdays'],
        ['value' => Carbon::FRIDAY,    'label' => 'Fridays'],
        ['value' => Carbon::SATURDAY,  'label' => 'Saturdays'],
        ['value' => Carbon::SUNDAY,    'label' => 'Sundays'],
    ];

    public int $id;
    public int $interval;
    public ?int $dayOf;

    /**
     * Create a new Frequency.
     *
     * @param int $id One of the frequency constants
     * @param int $dayOf - The day of the Frequency (day of week, day of month, etc.)
     */
    public function __construct(int $id, int $interval = 1, int $dayOf = null)
    {
        $this->id       = $id;
        $this->interval = $interval;
        $this->dayOf    = $dayOf;
    }

    public function adjective(): string
    {
        return self::ADJECTIVES[$this->id];
    }

    public function noun(): string
    {
        return self::NOUNS[$this->id];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function nounsAsSelectOptions(): array
    {
        return self::frequenciesAsSelectOptions(self::NOUNS);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function adjectivesAsSelectOptions(): array
    {
        return self::frequenciesAsSelectOptions(self::ADJECTIVES);
    }

    /**
     * @param array<int, string> $words
     * @return array<int, array<string, mixed>>
     */
    public static function frequenciesAsSelectOptions(array $words): array
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

    public function toPrefixedString(string $prefix = ''): string
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
     * @param Carbon $after optional
     * @throws InvalidArgumentException if the frequency is invalid
     */
    public function getNextDate(Carbon $after = null): ?Carbon
    {
        $after = $after ?? today();

        $i = $this->interval; // Just to make the rest smaller.

        if (! $this->dayOf) {
            return match ($this->id) {
                self::DOES_NOT_REPEAT => null,
                self::DAILY           => $after->addDays($i),
                self::WEEKLY          => $after->addWeeks($i),
                self::MONTHLY         => $after->addMonthsNoOverflow($i),
                self::QUARTERLY       => $after->addQuartersNoOverflow($i),
                self::YEARLY          => $after->addYearsNoOverflow($i),
                default               => throw new InvalidArgumentException('Invalid interval ' . $i),
            };
        }

        return match ($this->id) {
            self::DOES_NOT_REPEAT => null,
            self::DAILY           => $after->addDays($i),
            self::WEEKLY          => $after->startOfWeek()->addDays($this->dayOf    - 1)->addWeeks($i),
            self::MONTHLY         => $after->startOfMonth()->addDays($this->dayOf   - 1)->addMonthsNoOverflow($i),
            self::QUARTERLY       => $after->startOfQuarter()->addDays($this->dayOf - 1)->addQuartersNoOverflow($i),
            self::YEARLY          => $after->startOfYear()->addDays($this->dayOf    - 1)->addYearsNoOverflow($i),
            default               => throw new InvalidArgumentException('Invalid interval ' . $i),
        };
    }
}
