<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Jetstream\TeamInvitation as JetstreamTeamInvitation;

/**
 * @mixin \Eloquent
 * @mixin IdeHelperTeamInvitation
 */
class TeamInvitation extends JetstreamTeamInvitation
{
    /** @var array<string> */
    protected $fillable = [
        'email',
        'role',
    ];

    /** @return BelongsTo<Team, $this> */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
