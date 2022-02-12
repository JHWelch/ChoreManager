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
