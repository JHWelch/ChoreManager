<?php

namespace App\Models;

use App\Models\Concerns\HasChoreStreaks;
use App\Models\Concerns\HasUnfinishedChoreScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChoreInstance> $choreInstances
 * @property-read int|null $chore_instances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chore> $chores
 * @property-read int|null $chores_count
 * @property-read \App\Models\StreakCount|null $currentStreak
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TeamInvitation> $teamInvitations
 * @property-read int|null $team_invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePersonalTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team withUnfinishedChores(?\Illuminate\Support\Carbon $on_or_before = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Team withoutUnfinishedChores(?\Illuminate\Support\Carbon $on_or_before = null)
 *
 * @mixin \Eloquent
 */
class Team extends JetstreamTeam
{
    use HasChoreStreaks;
    use HasFactory;

    /** @use HasUnfinishedChoreScopes<self> */
    use HasUnfinishedChoreScopes;

    public static ?Team $admin_team = null;

    protected $guarded = [];

    /** @var array<string, string> */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    public static function adminTeam(): ?self
    {
        if (! self::$admin_team) {
            self::$admin_team = self::firstWhere('name', 'Admins');
        }

        return self::$admin_team;
    }

    /** @return HasMany<Chore> */
    public function chores(): HasMany
    {
        return $this->hasMany(Chore::class);
    }

    /** @return HasManyThrough<ChoreInstance> */
    public function choreInstances(): HasManyThrough
    {
        return $this->hasManyThrough(ChoreInstance::class, Chore::class);
    }
}
