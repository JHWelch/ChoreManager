<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoreInstance extends Model
{
    use HasFactory;

    public function chore()
    {
        return $this->belongsTo(Chore::class);
    }
}
