<?php

namespace App\Models;

use App\Enums\Frequency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chore extends Model
{
    use HasFactory;

    protected $guarded;

    protected $attributes = [
        'frequency_id'       => 0,
        'frequency_interval' => 1,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return Frequency
     */
    public function getFrequencyAttribute()
    {
        return new Frequency($this->frequency_id, $this->frequency_interval);
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
        return $query->select(
            'chores.*',
            'chore_instances.due_date',
            'chore_instances.user_id',
            'chore_instances.id AS chore_instance_id',
        )
            ->leftJoin('chore_instances', function ($join) {
                $join->on('chores.id', '=', 'chore_instances.chore_id')
                    ->where('chore_instances.completed_date', null);
            })
            ->withCasts(['due_date' => 'datetime']);
    }

    /**
     * Only return Chores that have their next chore instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyWithNextInstance($query)
    {
        return $query->select(
            'chores.*',
            'chore_instances.due_date',
            'chore_instances.user_id',
            'chore_instances.id AS chore_instance_id',
        )
            ->join('chore_instances', function ($join) {
                $join->on('chores.id', '=', 'chore_instances.chore_id')
                    ->where('chore_instances.completed_date', null);
            })
            ->withCasts(['due_date' => 'datetime']);
    }

    public function scopeNullDueDatesAtEnd($query)
    {
        return $query->orderBy(DB::raw('ISNULL(chore_instances.due_date), chore_instances.due_date'), 'ASC');
    }

    public function createNewInstance()
    {
        $i = $this->frequency_interval;

        $next_date = match ($this->frequency_id) {
            0 => null,
            1 => today()->addDays($i),
            2 => today()->addWeeks($i),
            3 => today()->addMonthNoOverflows($i),
            4 => today()->addQuarterNoOverflows($i),
            5 => today()->addYearNoOverflows($i),
        };

        if ($next_date === null) {
            return;
        }

        ChoreInstance::create([
            'chore_id' => $this->id,
            'due_date' => $next_date,
            'user_id'  => $this->user_id,
        ]);
    }
}
