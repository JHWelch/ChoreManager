<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StreakCount.
 *
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|StreakCount current()
 * @method static \Database\Factories\StreakCountFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|StreakCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StreakCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StreakCount query()
 * @mixin \Eloquent
 * @property-read \App\Models\Team $team
 * @property int $id
 * @property string|null $ended_at
 * @property int $count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property int|null $team_id
 * @method static \Illuminate\Database\Eloquent\Builder|StreakCount whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StreakCount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StreakCount whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StreakCount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StreakCount whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StreakCount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StreakCount whereUserId($value)
 */
class StreakCount extends Model
{
    use HasFactory;

    protected $guarded;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeCurrent($query)
    {
        return $query->whereNull('ended_at');
    }
}
