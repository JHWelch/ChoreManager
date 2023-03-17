<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\ChoreInstance.
 *
 * @property int $id
 * @property int $chore_id
 * @property Carbon $due_date
 * @property Carbon|null $completed_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $user_id
 * @property int|null $completed_by_id
 * @property-read \App\Models\Chore $chore
 * @property-read \App\Models\User|null $completedBy
 * @property-read bool $is_completed
 * @property-read \App\Models\User $user
 * @method static Builder|ChoreInstance completed()
 * @method static Builder|ChoreInstance dueToday()
 * @method static Builder|ChoreInstance dueTodayOrPast()
 * @method static \Database\Factories\ChoreInstanceFactory factory($count = null, $state = [])
 * @method static Builder|ChoreInstance newModelQuery()
 * @method static Builder|ChoreInstance newQuery()
 * @method static Builder|ChoreInstance notCompleted()
 * @method static Builder|ChoreInstance query()
 * @method static Builder|ChoreInstance whereChoreId($value)
 * @method static Builder|ChoreInstance whereCompletedById($value)
 * @method static Builder|ChoreInstance whereCompletedDate($value)
 * @method static Builder|ChoreInstance whereCreatedAt($value)
 * @method static Builder|ChoreInstance whereDueDate($value)
 * @method static Builder|ChoreInstance whereId($value)
 * @method static Builder|ChoreInstance whereUpdatedAt($value)
 * @method static Builder|ChoreInstance whereUserId($value)
 * @mixin \Eloquent
 */
class ChoreInstance extends Model
{
    use HasFactory;

    protected $guarded = [];

    /** @var array<string, string> */
    protected $casts = [
        'due_date'       => 'date:Y-m-d',
        'completed_date' => 'date:Y-m-d',
    ];

    public function complete(?int $for = null, ?Carbon $on = null) : void
    {
        $this->completed_date  = $on  ?? today();
        $this->completed_by_id = $for ?? Auth::id();
        $this->save();

        $this->chore->createNewInstance($this->completed_date);
    }

    public function chore() : BelongsTo
    {
        return $this->belongsTo(Chore::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function completedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by_id');
    }

    public function getIsCompletedAttribute() : bool
    {
        return ! is_null($this->completed_date);
    }

    public function scopeCompleted(Builder $query) : Builder
    {
        return $query->whereNotNull('completed_date');
    }

    public function scopeNotCompleted(Builder $query) : Builder
    {
        return $query->whereNull('completed_date');
    }

    public function scopeDueToday(Builder $query) : Builder
    {
        return $query->where('due_date', today());
    }

    public function scopeDueTodayOrPast(Builder $query) : Builder
    {
        return $query->where('due_date', '<=', today());
    }

    public function snooze(Carbon $nextDueDate) : void
    {
        $this->due_date = $nextDueDate;
        $this->save();
    }
}
