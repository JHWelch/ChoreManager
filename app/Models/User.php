<?php

namespace App\Models;

use App\Models\Concerns\HasChoreStreaks;
use App\Models\Concerns\HasUnfinishedChoreScopes;
use App\Scopes\OrderByNameScope;
use Filament\Models\Contracts\FilamentUser;
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
 * App\Models\User.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CalendarToken> $calendarTokens
 * @property-read int|null $calendar_tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChoreInstance> $choreInstances
 * @property-read int|null $chore_instances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chore> $chores
 * @property-read int|null $chores_count
 * @property-read \App\Models\StreakCount|null $currentStreak
 * @property-read \App\Models\Team|null $currentTeam
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read \App\Models\UserSetting|null $settings
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withUnfinishedChores(?\Illuminate\Support\Carbon $on_or_before = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutUnfinishedChores(?\Illuminate\Support\Carbon $on_or_before = null)
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasChoreStreaks;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
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

    /** @var array<string, string> */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** @var string[] */
    protected $appends = [
        'profile_photo_url',
    ];

    protected ?bool $is_admin = null;

    public function canAccessPanel(): bool
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

    public function chores(): HasMany
    {
        return $this->hasMany(Chore::class);
    }

    public function choreInstances(): HasMany
    {
        return $this->hasMany(ChoreInstance::class);
    }

    public function calendarTokens(): HasMany
    {
        return $this->hasMany(CalendarToken::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

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
