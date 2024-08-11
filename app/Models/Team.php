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
 * @mixin \Eloquent
 * @mixin IdeHelperTeam
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
