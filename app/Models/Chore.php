<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chore extends Model
{
    use HasFactory;

    protected $guarded;

    const FREQUENCIES = [
        0 => 'Does not Repeat',
        1 => 'Daily',
        2 => 'Weekly',
        3 => 'Monthly',
        4 => 'Quarterly',
        5 => 'Yearly',
    ];

    public static function frequenciesAsSelectOptions()
    {
        $frequencies = [];

        foreach (self::FREQUENCIES as $key => $frequency) {
            $frequencies[] = ['value' => $key, 'label' => $frequency];
        }

        return $frequencies;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFrequencyAttribute()
    {
        return self::FREQUENCIES[$this->frequency_id];
    }

    public function choreInstances()
    {
        return $this->hasMany(ChoreInstance::class);
    }

    public function nextChoreInstance()
    {
        return $this->hasOne(ChoreInstance::class)->where('completed_date', null);
    }
}
