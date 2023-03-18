<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

/**
 * App\Models\CalendarToken.
 *
 * @property int $id
 * @property string|null $name
 * @property string $token
 * @property int $user_id
 * @property int|null $team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChoreInstance> $choreInstances
 * @property-read int|null $chore_instances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chore> $chores
 * @property-read int|null $chores_count
 * @property-read string $display_name
 * @property-read string $full_type_name
 * @property-read bool $is_team_calendar
 * @property-read bool $is_user_calendar
 * @property-read string $u_r_l
 * @property-read \App\Models\Team|null $team
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\CalendarTokenFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarToken whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarToken whereUserId($value)
 * @mixin \Eloquent
 */
class CalendarToken extends Model
{
    use HasFactory;

    const CALENDAR_TYPES = [
        [
            'label'       => 'User Calendar',
            'value'       => 'user',
            'description' => 'This calendar will include upcoming chores assigned to you, across Teams.',
        ],
        [
            'label'       => 'Team Calendar',
            'value'       => 'team',
            'description' => 'This calendar will include upcoming chores for everyone in a given Team.',
        ],
    ];

    protected $guarded = [];

    protected static function boot() : void
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->token) {
                $model->token = Str::uuid();
            }
        });
    }

    public static function getToken(string $token) : ?self
    {
        return self::firstWhere('token', $token);
    }

    public function chores() : HasManyThrough
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

    public function choreInstances() : Builder
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

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team() : BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function getIsTeamCalendarAttribute() : bool
    {
        return $this->team_id !== null;
    }

    public function getIsUserCalendarAttribute() : bool
    {
        return ! $this->is_team_calendar;
    }

    public function getDisplayNameAttribute() : string
    {
        return $this->name ?? (
            $this->is_team_calendar
                ? "{$this->team->name} Chores"
                : "{$this->user->name}'s Chores"
        );
    }

    public function getFullTypeNameAttribute() : string
    {
        return $this->is_team_calendar
            ? "Team: {$this->team->name}"
            : "User: {$this->user->name}";
    }

    public function getURLAttribute() : string
    {
        return route('icalendar.show', ['token' => $this->token]);
    }
}
