<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @mixin IdeHelperUserSetting
 */
class UserSetting extends Model
{
    protected $guarded = [];

    /** @var array<string, mixed> */
    public $attributes = [
        'has_daily_digest' => false,
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'has_daily_digest' => 'boolean',
        ];
    }
}
