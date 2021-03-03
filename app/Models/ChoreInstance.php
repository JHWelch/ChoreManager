<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoreInstance extends Model
{
    use HasFactory;

    protected $casts = [
        'due_date'   => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function chore()
    {
        return $this->belongsTo(Chore::class);
    }
}
