<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarToken extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getToken($token)
    {
        return self::firstWhere('token', $token);
    }

    public function chores()
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getIsTeamCalendarAttribute()
    {
        return $this->team_id !== null;
    }

    public function getIsUserCalendarAttribute()
    {
        return ! $this->is_team_calendar;
    }

    public function getDisplayNameAttribute()
    {
        return $this->name ?? (
            $this->is_team_calendar
                ? "{$this->team->name} Chores"
                : "{$this->user->name}'s Chores"
        );
    }

    public function getFullTypeNameAttribute()
    {
        return $this->is_team_calendar
            ? "Team: {$this->team->name}"
            : "User: {$this->user->name}";
    }
}
