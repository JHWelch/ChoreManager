<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property string $token
 * @property int $user_id
 * @property int|null $team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChoreInstance> $choreInstances
 * @property-read int|null $chore_instances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chore> $chores
 * @property-read int|null $chores_count
 * @property-read string $display_name
 * @property-read string $full_type_name
 * @property-read bool $is_team_calendar
 * @property-read bool $is_user_calendar
 * @property-read \App\Models\Team|null $team
 * @property-read string $url
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\CalendarTokenFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarToken whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CalendarToken whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCalendarToken {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $title
 * @property string|null $description
 * @property \App\Enums\FrequencyType $frequency_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $team_id
 * @property int|null $frequency_interval
 * @property int|null $frequency_day_of
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChoreInstance> $choreInstances
 * @property-read int|null $chore_instances_count
 * @property-read \Illuminate\Support\Carbon|null $due_date_updated_at
 * @property-read \App\Enums\Frequency $frequency
 * @property-read bool $is_does_not_repeat
 * @property-read bool $is_weekly
 * @property-read bool $is_yearly
 * @property-read int $next_assigned_id
 * @property-read \App\Models\ChoreInstance|null $nextChoreInstance
 * @property-read \Illuminate\Support\Carbon|null $next_due_date
 * @property-read \App\Models\ChoreInstance|null $nextInstance
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChoreInstance> $pastChoreInstances
 * @property-read int|null $past_chore_instances_count
 * @property-read \App\Models\Team|null $team
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\ChoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore nullDueDatesAtEnd()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore onlyWithDueNextInstance()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore onlyWithNextInstance()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore whereFrequencyDayOf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore whereFrequencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore whereFrequencyInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chore withNextInstance()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperChore {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $chore_id
 * @property \Illuminate\Support\Carbon $due_date
 * @property \Illuminate\Support\Carbon|null $completed_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property int|null $completed_by_id
 * @property-read \App\Models\Chore $chore
 * @property-read \App\Models\User|null $completedBy
 * @property-read bool $is_completed
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance dueToday()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance dueTodayOrPast()
 * @method static \Database\Factories\ChoreInstanceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance notCompleted()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance whereChoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance whereCompletedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance whereCompletedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChoreInstance whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperChoreInstance {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $token
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\DeviceTokenFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeviceToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeviceToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeviceToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeviceToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeviceToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeviceToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeviceToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeviceToken whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDeviceToken {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $team_id
 * @property int $user_id
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMembership {}
}

namespace App\Models{
/**
 * 
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StreakCount current()
 * @method static \Database\Factories\StreakCountFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StreakCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StreakCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StreakCount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StreakCount whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StreakCount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StreakCount whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StreakCount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StreakCount whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StreakCount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StreakCount whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStreakCount {}
}

namespace App\Models{
/**
 * 
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
 * @property-read \App\Models\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TeamInvitation> $teamInvitations
 * @property-read int|null $team_invitations_count
 * @property-read \App\Models\Membership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team wherePersonalTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team withUnfinishedChores(?\Illuminate\Support\Carbon $on_or_before = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team withoutUnfinishedChores(?\Illuminate\Support\Carbon $on_or_before = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTeam {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $team_id
 * @property string $email
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTeamInvitation {}
}

namespace App\Models{
/**
 * 
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
 * @property string|null $two_factor_confirmed_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CalendarToken> $calendarTokens
 * @property-read int|null $calendar_tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChoreInstance> $choreInstances
 * @property-read int|null $chore_instances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chore> $chores
 * @property-read int|null $chores_count
 * @property-read \App\Models\StreakCount|null $currentStreak
 * @property-read \App\Models\Team|null $currentTeam
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeviceToken> $deviceTokens
 * @property-read int|null $device_tokens_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read string $profile_photo_url
 * @property-read \App\Models\UserSetting|null $settings
 * @property-read \App\Models\Membership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withUnfinishedChores(?\Illuminate\Support\Carbon $on_or_before = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutUnfinishedChores(?\Illuminate\Support\Carbon $on_or_before = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property bool $has_daily_digest
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSetting whereHasDailyDigest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSetting whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUserSetting {}
}

