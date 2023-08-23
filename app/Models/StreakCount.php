<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\StreakCount.
 *
 * @property int $id
 * @property string|null $ended_at
 * @property int $count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property int|null $team_id
 * @property-read \App\Models\Team|null $team
 * @property-read \App\Models\User|null $user
 *
 * @method static Builder|StreakCount current()
 * @method static \Database\Factories\StreakCountFactory factory($count = null, $state = [])
 * @method static Builder|StreakCount newModelQuery()
 * @method static Builder|StreakCount newQuery()
 * @method static Builder|StreakCount query()
 * @method static Builder|StreakCount whereCount($value)
 * @method static Builder|StreakCount whereCreatedAt($value)
 * @method static Builder|StreakCount whereEndedAt($value)
 * @method static Builder|StreakCount whereId($value)
 * @method static Builder|StreakCount whereTeamId($value)
 * @method static Builder|StreakCount whereUpdatedAt($value)
 * @method static Builder|StreakCount whereUserId($value)
 *
 * @mixin \Eloquent
 */
class StreakCount extends Model
{
    use HasFactory;

    protected $guarded;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->whereNull('ended_at');
    }
}
