<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\ChoreInstance.
 *
 * @property int $id
 * @property int $chore_id
 * @property mixed $due_date
 * @property mixed|null $completed_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property int|null $completed_by_id
 * @property-read \App\Models\Chore $chore
 * @property-read \App\Models\User|null $completedBy
 * @property-read mixed $is_completed
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance completed()
 * @method static \Database\Factories\ChoreInstanceFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance whereChoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance whereCompletedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance whereCompletedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChoreInstance whereUserId($value)
 * @mixin \Eloquent
 */
class ChoreInstance extends Model
{
    use HasFactory;

    protected $guarded;

    protected $casts = [
        'due_date'       => 'date:Y-m-d',
        'completed_date' => 'date:Y-m-d',
    ];

    public function chore()
    {
        return $this->belongsTo(Chore::class);
    }

    /**
     * Create next chore instance if required and mark this one complete.
     *
     * @param int $for User to complete the Chore for
     * @param \Carbon\Carbon $on date to set completed
     * @return void
     */
    public function complete($for = null, $on = null)
    {
        $this->completed_date  = $on  ?? today();
        $this->completed_by_id = $for ?? Auth::id();
        $this->save();

        $this->chore->createNewInstance($this->completed_date);
    }

    public function getIsCompletedAttribute()
    {
        return ! is_null($this->completed_date);
    }

    /**
     * Scope a query to only include completed ChoreInstances.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed_date', '!=', null);
    }

    /**
     * Scope a query to only include not completed ChoreInstances.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotCompleted($query)
    {
        return $query->where('completed_date', null);
    }

    /**
     * Scope a query to only include Choreinstance due today.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDueToday($query)
    {
        return $query->where('due_date', today());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to user who completed the chore.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by_id');
    }
}
