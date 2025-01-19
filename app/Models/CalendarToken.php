<?php

namespace App\Models;

use Database\Factories\CalendarTokenFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

/**
 * @mixin \Eloquent
 * @mixin IdeHelperCalendarToken
 */
class CalendarToken extends Model
{
    /** @use HasFactory<CalendarTokenFactory> */
    use HasFactory;

    const CALENDAR_TYPES = [
        [
            'label' => 'User Calendar',
            'value' => 'user',
            'description' => 'This calendar will include upcoming chores assigned to you, across Teams.',
        ],
        [
            'label' => 'Team Calendar',
            'value' => 'team',
            'description' => 'This calendar will include upcoming chores for everyone in a given Team.',
        ],
    ];

    protected $guarded = [];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->token) {
                $model->token = Str::uuid();
            }
        });
    }

    public static function getToken(string $token): ?self
    {
        return self::firstWhere('token', $token);
    }

    /** @return HasManyThrough<Chore, User, $this> | HasManyThrough<Chore, Team, $this> */
    public function chores(): HasManyThrough
    {
        return $this->is_user_calendar
            ? $this->hasManyThrough(
                Chore::class,
                User::class,
                'id',
                'user_id',
                'user_id',
                'id'
            )
            : $this->hasManyThrough(
                Chore::class,
                Team::class,
                'id',
                'team_id',
                'team_id',
                'id'
            );
    }

    /** @return Builder<ChoreInstance>|HasManyThrough<ChoreInstance, User, $this> */
    public function choreInstances(): Builder|HasManyThrough
    {
        return $this->is_user_calendar
            ? $this->hasManyThrough(
                ChoreInstance::class,
                User::class,
                'id',
                'user_id',
                'user_id',
                'id'
            )
                ->with('chore')
                ->orderBy('chore_instances.due_date')
            : ChoreInstance::join('chores', function ($join) {
                $join->on('chore_instances.chore_id', '=', 'chores.id');
            })
                ->where('chores.team_id', $this->team_id)
                ->where('chore_instances.completed_date', null)
                ->orderBy('chore_instances.due_date');
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Team, $this> */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /** @return Attribute<bool, never> */
    public function isTeamCalendar(): Attribute
    {
        return Attribute::get(fn (): bool => $this->team_id !== null);
    }

    /** @return Attribute<bool, never> */
    public function isUserCalendar(): Attribute
    {
        return Attribute::get(fn (): bool => ! $this->is_team_calendar);
    }

    /** @return Attribute<string, never> */
    public function displayName(): Attribute
    {
        return Attribute::get(fn (): string => $this->name ?? (
            $this->is_team_calendar
                ? "{$this->team->name} Chores"
                : "{$this->user->name}'s Chores"
        ));
    }

    /** @return Attribute<string, never> */
    public function fullTypeName(): Attribute
    {
        return Attribute::get(fn (): string => $this->is_team_calendar
            ? "Team: {$this->team->name}"
            : "User: {$this->user->name}");
    }

    /** @return Attribute<string, never> */
    public function url(): Attribute
    {
        return Attribute::get(
            fn (): string => route('icalendar.show', ['token' => $this->token])
        );
    }
}
