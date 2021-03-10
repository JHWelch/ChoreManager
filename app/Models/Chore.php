<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $attributes = [
        'frequency_id' => 0,
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

    public function team()
    {
        return $this->belongsTo(Team::class);
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

    /**
     * Join Chore to the Next Chore instance if available.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithNextInstance($query)
    {
        return $query->select('chores.*', 'chore_instances.due_date', 'chore_instances.id AS chore_instance_id')
            ->leftJoin('chore_instances', function ($join) {
                $join->on('chores.id', '=', 'chore_instances.chore_id')
                    ->where('chore_instances.completed_date', null);
            });
    }

    /**
     * Only return Chores that have their next chore instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyWithNextInstance($query)
    {
        return $query->select('chores.*', 'chore_instances.due_date', 'chore_instances.id AS chore_instance_id')
            ->join('chore_instances', function ($join) {
                $join->on('chores.id', '=', 'chore_instances.chore_id')
                    ->where('chore_instances.completed_date', null);
            });
    }

    public function scopeNullDueDatesAtEnd($query)
    {
        return $query->orderBy(DB::raw('ISNULL(chore_instances.due_date), chore_instances.due_date'), 'ASC');
    }

    public function createNewInstance()
    {
        $now       = Carbon::now();

        $next_date = match ($this->frequency_id) {
            0 => null,
            1 => $now->addDay(),
            2 => $now->addWeek(),
            3 => $now->addMonthNoOverflow(),
            4 => $now->addQuarterNoOverflow(),
            5 => $now->addYearNoOverflow(),
        };

        if ($next_date === null) {
            return;
        }

        ChoreInstance::create([
            'chore_id' => $this->id,
            'due_date' => $next_date,
        ]);
    }
}
