<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Default Setting values
    public $attributes = [
        'has_daily_digest' => false,
    ];

    public $casts = [
        'has_daily_digest' => 'boolean',
    ];
}
