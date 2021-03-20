<?php

namespace App\Enums;

/**
 * TODO: Laravel 8.1 will have enums.
 * "All I want is to be a real Enum!".
 */
class Frequency
{
    const FREQUENCIES = [
        0,
        1,
        2,
        3,
        4,
        5,
    ];

    const ADJECTIVES = [
        0 => 'Does not Repeat',
        1 => 'Daily',
        2 => 'Weekly',
        3 => 'Monthly',
        4 => 'Quarterly',
        5 => 'Yearly',
    ];

    const NOUNS = [
        0 => 'Does not Repeat',
        1 => 'Days',
        2 => 'Weeks',
        3 => 'Months',
        4 => 'Quarters',
        5 => 'Years',
    ];

    public $frequency_id;

    public function __construct($frequency_id)
    {
        $this->frequency_id = $frequency_id;
    }

    public function adjective()
    {
        return self::ADJECTIVES[$this->frequency_id];
    }

    public function noun()
    {
        return self::NOUNS[$this->frequency_id];
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
}
