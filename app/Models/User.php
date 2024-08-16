<?php

namespace App\Models;

use App\Models\Concerns\HasChoreStreaks;
use App\Models\Concerns\HasUnfinishedChoreScopes;
use App\Scopes\OrderByNameScope;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin \Eloquent
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasChoreStreaks;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use HasTeams;

    /** @use HasUnfinishedChoreScopes<self> */
    use HasUnfinishedChoreScopes;

    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /** @var array<int, string> */
    protected $appends = [
        'profile_photo_url',
    ];

    protected ?bool $is_admin = null;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new OrderByNameScope);
        static::created(function ($user) {
            UserSetting::create(['user_id' => $user->id]);
        });
    }

    /**
     * Get all users with a specific setting.
     *
     * @return Collection<int, User>
     */
    public static function withSetting(
        string $setting,
        bool $value,
        string $operator = '='
    ): Collection {
        return self::with('settings')
            ->whereHas('settings', function ($query) use ($setting, $operator, $value) {
                $query->where($setting, $operator, $value);
            })
            ->get();
    }

    /**
     * Specifies the user's FCM tokens.
     *
     * @return string|string[]
     */
    public function routeNotificationForFcm(): string|array
    {
        return $this->deviceTokens->pluck('token')->toArray();
    }

    /** @return HasMany<Chore> */
    public function chores(): HasMany
    {
        return $this->hasMany(Chore::class);
    }

    /** @return HasMany<ChoreInstance> */
    public function choreInstances(): HasMany
    {
        return $this->hasMany(ChoreInstance::class);
    }

    /** @return HasMany<CalendarToken> */
    public function calendarTokens(): HasMany
    {
        return $this->hasMany(CalendarToken::class);
    }

    /** @return HasOne<UserSetting> */
    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    /** @return HasMany<DeviceToken> */
    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function isAdmin(): bool
    {
        if (! $this->is_admin) {
            $admin_team = Team::adminTeam();
            $this->is_admin = $this->ownsAdminTeam($admin_team)
                || $this->onAdminTeam($admin_team);
        }

        return $this->is_admin;
    }

    private function ownsAdminTeam(?Team $admin_team): bool
    {
        return $admin_team && $admin_team->user_id === $this->id;
    }

    private function onAdminTeam(?Team $admin_team): bool
    {
        return $admin_team && $admin_team
            ->users()
            ->where('user_id', $this->id)
            ->exists();
    }
}
