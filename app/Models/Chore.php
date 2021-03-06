<?php

namespace App\Models;

use App\Enums\Frequency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chore extends Model
{
    use HasFactory;

    const SCOPE_COLUMNS = [
        'chores.*',
        'chore_instances.due_date',
        'chore_instances.user_id',
        'chore_instances.id AS chore_instance_id',
    ];

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

    public function pastChoreInstances()
    {
        return $this->hasMany(ChoreInstance::class)
            ->completed()
            ->orderBy('completed_date', 'desc');
    }

    /**
     * Join used by scopes including Chore Instances.
     *
     * @param Illuminate\Database\Query\JoinClause $join
     * @return Illuminate\Database\Query\JoinClause
     */
    public function choreInstanceScopeJoin($join)
    {
        return $join->on('chores.id', '=', 'chore_instances.chore_id')
            ->where('chore_instances.completed_date', null);
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
            ...self::SCOPE_COLUMNS
        )
            ->leftJoin('chore_instances', fn ($join) => $this->choreInstanceScopeJoin($join))
            ->withCasts(['due_date' => 'date:m/d/Y']);
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
            ...self::SCOPE_COLUMNS
        )
            ->join('chore_instances', fn ($join) => $this->choreInstanceScopeJoin($join))
            ->withCasts(['due_date' => 'date:m/d/Y']);
    }

    /**
     * Join Chore to the Next Chore instance if available.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyWithDueNextInstance($query)
    {
        return $query->select(
            ...self::SCOPE_COLUMNS
        )
            ->join('chore_instances', fn ($join) => $this->choreInstanceScopeJoin($join)
                ->where('chore_instances.due_date', '<=', today()))
            ->withCasts(['due_date' => 'date:m/d/Y']);
    }

    public function scopeNullDueDatesAtEnd($query)
    {
        return $query->orderByRaw('ISNULL(chore_instances.due_date), chore_instances.due_date ASC');
    }

    public function createNewInstance($due_date = null)
    {
        if (! $due_date) {
            $i = $this->frequency_interval;

            $due_date = match ($this->frequency_id) {
                0 => null,
                1 => today()->addDays($i),
                2 => today()->addWeeks($i),
                3 => today()->addMonthNoOverflows($i),
                4 => today()->addQuarterNoOverflows($i),
                5 => today()->addYearNoOverflows($i),
            };

            if ($due_date === null) {
                return;
            }
        }

        ChoreInstance::create([
            'chore_id' => $this->id,
            'due_date' => $due_date,
            'user_id'  => $this->next_assigned_id,
        ]);
    }

    /**
     * Get the id of the next user who should be assigned to an instance of this chore.
     * Either the owner of the chore, or a member of the team if no owner is specified.
     *
     * @return int A User Id.
     */
    public function getNextAssignedIdAttribute()
    {
        $last_assigned = $this->choreInstances()->orderBy('created_at', 'desc')->first();

        return $this->user_id ?? (
            $last_assigned
            ? $this
                ->team
                ->allUsers()
                ->sortBy('name')
                ->map
                ->id
                ->nextAfter($last_assigned->user_id, false, true)
            : $this
                ->team
                ->allUsers()
                ->sortBy('name')
                ->first()
                ->id
        );
    }

    /**
     * Complete the next chore instance.
     *
     * @param int $for User to complete the Chore for
     * @return void
     */
    public function complete($for = null)
    {
        return $this->nextChoreInstance?->complete($for);
    }
}
