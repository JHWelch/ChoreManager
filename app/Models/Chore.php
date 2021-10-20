<?php

namespace App\Models;

use App\Enums\Frequency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;

/**
 * App\Models\Chore.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $title
 * @property string|null $description
 * @property int $frequency_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $team_id
 * @property int|null $frequency_interval
 * @property int|null $frequency_day_of
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ChoreInstance[] $choreInstances
 * @property-read int|null $chore_instances_count
 * @property-read Frequency $frequency
 * @property-read int $next_assigned_id
 * @property-read \App\Models\ChoreInstance|null $nextChoreInstance
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ChoreInstance[] $pastChoreInstances
 * @property-read int|null $past_chore_instances_count
 * @property-read \App\Models\Team|null $team
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\ChoreFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Chore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chore nullDueDatesAtEnd()
 * @method static \Illuminate\Database\Eloquent\Builder|Chore onlyWithDueNextInstance()
 * @method static \Illuminate\Database\Eloquent\Builder|Chore onlyWithNextInstance()
 * @method static \Illuminate\Database\Eloquent\Builder|Chore query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chore whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chore whereFrequencyDayOf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chore whereFrequencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chore whereFrequencyInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chore whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chore whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chore whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chore withNextInstance()
 * @mixin \Eloquent
 */
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
        return new Frequency(
            $this->frequency_id,
            $this->frequency_interval,
            $this->frequency_day_of
        );
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
     * @param JoinClause $join
     * @return JoinClause
     */
    public function choreInstanceScopeJoin(JoinClause $join)
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
            ->withCasts(['due_date' => 'date:Y-m-d']);
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
            ->withCasts(['due_date' => 'date:Y-m-d']);
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
            ->withCasts(['due_date' => 'date:Y-m-d']);
    }

    public function scopeNullDueDatesAtEnd($query)
    {
        return $query->orderByRaw('ISNULL(chore_instances.due_date), chore_instances.due_date ASC');
    }

    public function createNewInstance($after = null)
    {
        if (! ($due_date = $this->frequency->getNextDate($after))) {
            return;
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
     * @param \Carbon\Carbon $on date to set completed
     * @return void
     */
    public function complete($for = null, $on = null)
    {
        $this->nextChoreInstance?->complete($for, $on);
    }
}
