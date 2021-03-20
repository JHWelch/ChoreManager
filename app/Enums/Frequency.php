<?php

namespace App\Enums;

/**
 * TODO: Laravel 8.1 will have enums.
 * "All I want is to be a real Enum!".
 */
class Frequency
{
    const FREQUENCIES = [
        0 => 'Does not Repeat',
        1 => 'Daily',
        2 => 'Weekly',
        3 => 'Monthly',
        4 => 'Quarterly',
        5 => 'Yearly',
    ];

    public $frequency_id;

    public function __construct($frequency_id)
    {
        $this->frequency_id = $frequency_id;
    }

    public function adjective()
    {
        return match ($this->frequency_id) {
            0 => 'Does not Repeat',
            1 => 'Daily',
            2 => 'Weekly',
            3 => 'Monthly',
            4 => 'Quarterly',
            5 => 'Yearly',
        };
    }

    public function noun()
    {
        return match ($this->frequency_id) {
            0 => 'Does not Repeat',
            1 => 'Day',
            2 => 'Week',
            3 => 'Month',
            4 => 'Quarter',
            5 => 'Year',
        };
    }
}
