<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @mixin IdeHelperUserSetting
 */
class UserSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    /** @var array<string, mixed> */
    public $attributes = [
        'has_daily_digest' => false,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'has_daily_digest' => 'boolean',
        ];
    }
}
