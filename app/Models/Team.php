<?php

namespace App\Models;

use App\Models\Concerns\HasChoreStreaks;
use App\Models\Concerns\HasUnfinishedChoreScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

/**
 * App\Models\Team.
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property bool $personal_team
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Chore[] $chores
 * @property-read int|null $chores_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TeamInvitation[] $teamInvitations
 * @property-read int|null $team_invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\TeamFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePersonalTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ChoreInstance[] $choreInstances
 * @property-read int|null $chore_instances_count
 * @property-read \App\Models\StreakCount|null $currentStreak
 * @method static \Illuminate\Database\Eloquent\Builder|Team withUnfinishedChores($on_or_before = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Team withoutUnfinishedChores($on_or_before = null)
 */
class Team extends JetstreamTeam
{
    use HasChoreStreaks;
    use HasFactory;
    use HasUnfinishedChoreScopes;

    protected $casts = [
        'personal_team' => 'boolean',
    ];

    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    public function chores()
    {
        return $this->hasMany(Chore::class);
    }

    public function choreInstances()
    {
        return $this->hasManyThrough(ChoreInstance::class, Chore::class);
    }
}
