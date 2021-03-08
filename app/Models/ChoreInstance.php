<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoreInstance extends Model
{
    use HasFactory;

    protected $guarded;

    protected $casts = [
        'due_date' => 'date:Y-m-d',
    ];

    public function chore()
    {
        return $this->belongsTo(Chore::class);
    }

    public function complete()
    {
        $this->chore->createNewInstance();

        $this->completed_date = Carbon::now();
        $this->save();
    }

    public function getIsCompletedAttribute()
    {
        return ! is_null($this->completed_date);
    }
}
