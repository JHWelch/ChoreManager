<?php

namespace App\Models;

use Database\Factories\ChoreInstanceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin \Eloquent
 * @mixin IdeHelperChoreInstance
 */
class ChoreInstance extends Model
{
    /** @use HasFactory<ChoreInstanceFactory> */
    use HasFactory;

    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'due_date' => 'date:Y-m-d',
            'completed_date' => 'date:Y-m-d',
        ];
    }

    public function complete(?int $for = null, ?Carbon $on = null): void
    {
        $this->completed_date = $on ?? today();
        $this->completed_by_id = $for ?? Auth::id();
        $this->save();

        $this->chore->createNewInstance($this->completed_date);
    }

    /** @return BelongsTo<Chore, self> */
    public function chore(): BelongsTo
    {
        return $this->belongsTo(Chore::class);
    }

    /** @return BelongsTo<User, self> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<User, self> */
    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by_id');
    }

    /** @return Attribute<bool, never> */
    public function isCompleted(): Attribute
    {
        return Attribute::get(fn (): bool => ! is_null($this->completed_date));
    }

    /** @param Builder<self> $query */
    public function scopeCompleted(Builder $query): void
    {
        $query->whereNotNull('completed_date');
    }

    /** @param Builder<self> $query */
    public function scopeNotCompleted(Builder $query): void
    {
        $query->whereNull('completed_date');
    }

    /** @param Builder<self> $query */
    public function scopeDueToday(Builder $query): void
    {
        $query->where('due_date', today());
    }

    /** @param Builder<self> $query */
    public function scopeDueTodayOrPast(Builder $query): void
    {
        $query->where('due_date', '<=', today());
    }

    public function snooze(Carbon $nextDueDate): void
    {
        $this->due_date = $nextDueDate;
        $this->save();
    }
}
