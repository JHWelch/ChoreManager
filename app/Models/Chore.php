<?php

namespace App\Models;

use App\Enums\Frequency;
use App\Enums\FrequencyType;
use Database\Factories\ChoreFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;

/**
 * @mixin \Eloquent
 * @mixin IdeHelperChore
 */
class Chore extends Model
{
    /** @use HasFactory<ChoreFactory> */
    use HasFactory;

    const SCOPE_COLUMNS = [
        'chores.*',
        'chore_instances.due_date',
        'chore_instances.user_id',
        'chore_instances.id AS chore_instance_id',
    ];

    protected $guarded;

    /** @var array<string, mixed> */
    protected $attributes = [
        'frequency_id' => 0,
        'frequency_interval' => 1,
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'frequency_id' => FrequencyType::class,
        ];
    }

    /** @return BelongsTo<User, self> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Team, self> */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /** @return HasMany<ChoreInstance> */
    public function choreInstances(): HasMany
    {
        return $this->hasMany(ChoreInstance::class);
    }

    /** @return HasOne<ChoreInstance> */
    public function nextChoreInstance(): HasOne
    {
        return $this
            ->hasOne(ChoreInstance::class)
            ->whereNull('completed_date');
    }

    /** @return HasOne<ChoreInstance> */
    public function nextInstance(): HasOne
    {
        return $this->nextChoreInstance();
    }

    public function getFrequencyAttribute(): Frequency
    {
        return new Frequency(
            $this->frequency_id,
            $this->frequency_interval,
            $this->frequency_day_of
        );
    }

    /** @return HasMany<ChoreInstance> */
    public function pastChoreInstances(): HasMany
    {
        return $this->hasMany(ChoreInstance::class)
            ->completed()
            ->orderByDesc('completed_date');
    }

    public function choreInstanceScopeJoin(JoinClause $join): JoinClause
    {
        return $join->on('chores.id', 'chore_instances.chore_id')
            ->whereNull('chore_instances.completed_date');
    }

    public function scopeWithNextInstance(Builder $query): Builder
    {
        return $query->select(
            ...self::SCOPE_COLUMNS
        )
            ->leftJoin('chore_instances', fn ($join) => $this->choreInstanceScopeJoin($join))
            ->withCasts(['due_date' => 'date:Y-m-d']);
    }

    public function scopeOnlyWithNextInstance(Builder $query): Builder
    {
        return $query->select(
            ...self::SCOPE_COLUMNS
        )
            ->join('chore_instances', fn ($join) => $this->choreInstanceScopeJoin($join))
            ->withCasts(['due_date' => 'date:Y-m-d']);
    }

    public function scopeOnlyWithDueNextInstance(Builder $query): Builder
    {
        return $query->select(
            ...self::SCOPE_COLUMNS
        )
            ->join('chore_instances', fn ($join) => $this->choreInstanceScopeJoin($join)
                ->where('chore_instances.due_date', '<=', today()))
            ->withCasts(['due_date' => 'date:Y-m-d']);
    }

    public function scopeNullDueDatesAtEnd(Builder $query): Builder
    {
        return $query->orderByRaw('ISNULL(chore_instances.due_date), chore_instances.due_date ASC');
    }

    public function createNewInstance(?Carbon $after = null): void
    {
        if (! ($due_date = $this->frequency->getNextDate($after))) {
            return;
        }

        $this->choreInstances()->create([
            'due_date' => $due_date,
            'user_id' => $this->next_assigned_id,
        ]);
    }

    /**
     * Get the id of the next user who should be assigned to an instance of this chore.
     * Either the owner of the chore, or a member of the team if no owner is specified.
     */
    public function getNextAssignedIdAttribute(): int
    {
        $last_assigned = $this->choreInstances()
            ->orderByDesc('created_at')
            ->first();

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

    public function getNextDueDateAttribute(): ?Carbon
    {
        return $this->nextChoreInstance?->due_date;
    }

    public function getDueDateUpdatedAtAttribute(): ?Carbon
    {
        return $this->nextChoreInstance?->updated_at;
    }

    /**
     * Complete the next chore instance.
     */
    public function complete(?int $for = null, ?Carbon $on = null): void
    {
        if ($this->nextInstance) {
            $this->nextInstance->complete($for, $on);

            return;
        }

        $for ??= auth()->id();
        $on ??= today();

        $this->choreInstances()->create([
            'due_date' => $on,
            'completed_date' => $on,
            'user_id' => $for,
            'completed_by_id' => $for,
        ]);
    }

    public function snooze(Carbon $until): void
    {
        $this->nextChoreInstance?->snooze($until);
    }

    public function getIsWeeklyAttribute(): bool
    {
        return $this->frequency_id === FrequencyType::weekly;
    }

    public function getIsYearlyAttribute(): bool
    {
        return $this->frequency_id === FrequencyType::yearly;
    }

    public function getIsDoesNotRepeatAttribute(): bool
    {
        return $this->frequency_id === FrequencyType::doesNotRepeat;
    }
}
