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

    /**
     * Join Chore to the Next Chore instance if available.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithNextInstance($query)
    {
        return $query->select('chores.*', 'chore_instances.due_date')
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
        return $query->select('chores.*', 'chore_instances.due_date')
            ->join('chore_instances', function ($join) {
                $join->on('chores.id', '=', 'chore_instances.chore_id')
                    ->where('chore_instances.completed_date', null);
            })
            ->withCasts(['due_date' => 'date']);
    }

    public function scopeNullDueDatesAtEnd($query)
    {
        return $query->orderBy(DB::raw('ISNULL(chore_instances.due_date), chore_instances.due_date'), 'ASC');
    }

    public function createNewInstance()
    {
        $now       = Carbon::now();
        $next_date = null;

        switch ($this->frequency_id) {
            case 0:
                return;
                break;
            case 1:
                $next_date = $now->addDay();
                break;
            case 2:
                $next_date = $now->addWeek();
                break;
            case 3:
                $next_date = $now->addMonthNoOverflow();
                break;
            case 4:
                $next_date = $now->addQuarterNoOverflow();
                break;
            case 5:
                $next_date = $now->addYearNoOverflow();
                break;
        }

        ChoreInstance::create([
            'chore_id' => $this->id,
            'due_date' => $next_date,
        ]);
    }
}
