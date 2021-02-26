<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function scopeWithNextInstance($query)
    {
        return $query->select('chores.*', 'chore_instances.due_date')
            ->leftJoin('chore_instances', function ($join) {
                $join->on('chores.id', '=', 'chore_instances.chore_id')
                    ->where('chore_instances.completed_date', null);
            });
    }

    public function scopeOnlyWithNextInstance($query)
    {
        return $query->select('chores.*', 'chore_instances.due_date')
            ->join('chore_instances', function ($join) {
                $join->on('chores.id', '=', 'chore_instances.chore_id')
                    ->where('chore_instances.completed_date', null);
            });
    }

    public function scopeNullDueDatesAtEnd($query)
    {
        return $query->orderBy(DB::raw('ISNULL(chore_instances.due_date), chore_instances.due_date'), 'ASC');
    }
}
