<?php

namespace App\Enums;

use Carbon\Carbon;
use InvalidArgumentException;

class Frequency
{
    const DAYS_OF_THE_WEEK_AS_SELECT_OPTIONS = [
        ['value' => Carbon::MONDAY,    'label' => 'Mondays'],
        ['value' => Carbon::TUESDAY,   'label' => 'Tuesdays'],
        ['value' => Carbon::WEDNESDAY, 'label' => 'Wednesdays'],
        ['value' => Carbon::THURSDAY,  'label' => 'Thursdays'],
        ['value' => Carbon::FRIDAY,    'label' => 'Fridays'],
        ['value' => Carbon::SATURDAY,  'label' => 'Saturdays'],
        ['value' => Carbon::SUNDAY,    'label' => 'Sundays'],
    ];

    /**
     * @param FrequencyType $id One of the frequency constants
     * @param int $interval - The interval of the Frequency (every 2 weeks, every 3 months, etc.)
     * @param int $dayOf - The day of the Frequency (day of week, day of month, etc.)
     */
    public function __construct(
        public FrequencyType $frequencyType,
        public int $interval = 1,
        public ?int $dayOf = null
    ) {
    }

    public function adjective(): string
    {
        return $this->frequencyType->adjective();
    }

    public function noun(): string
    {
        return $this->frequencyType->noun();
    }

    public function __toString(): string
    {
        return $this->toPrefixedString();
    }

    public function toPrefixedString(string $prefix = ''): string
    {
        if ($this->frequencyType === FrequencyType::doesNotRepeat) {
            return $this->frequencyType->adjective();
        }

        if ($this->interval === 1) {
            return $prefix
                ? $prefix . ' ' . lcfirst($this->frequencyType->adjective())
                : $this->frequencyType->adjective();
        }

        return $prefix
            ? "$prefix every " . $this->interval . ' ' . lcfirst($this->frequencyType->noun())
            : 'Every ' . $this->interval . ' ' . lcfirst($this->frequencyType->noun());
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
            return match ($this->frequencyType) {
                FrequencyType::doesNotRepeat => null,
                FrequencyType::daily         => $after->addDays($i),
                FrequencyType::weekly        => $after->addWeeks($i),
                FrequencyType::monthly       => $after->addMonthsNoOverflow($i),
                FrequencyType::quarterly     => $after->addQuartersNoOverflow($i),
                FrequencyType::yearly        => $after->addYearsNoOverflow($i),
            };
        }

        return match ($this->frequencyType) {
            FrequencyType::doesNotRepeat => null,
            FrequencyType::daily         => $after->addDays($i),
            FrequencyType::weekly        => $after->startOfWeek()->addDays($this->dayOf    - 1)->addWeeks($i),
            FrequencyType::monthly       => $after->startOfMonth()->addDays($this->dayOf   - 1)->addMonthsNoOverflow($i),
            FrequencyType::quarterly     => $after->startOfQuarter()->addDays($this->dayOf - 1)->addQuartersNoOverflow($i),
            FrequencyType::yearly        => $after->startOfYear()->addDays($this->dayOf    - 1)->addYearsNoOverflow($i),
        };
    }
}
