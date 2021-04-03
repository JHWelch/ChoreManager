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

    /**
     * Create next chore instance if required and mark this one complete.
     *
     * @return void
     */
    public function complete()
    {
        $this->chore->createNewInstance();

        $this->completed_date = today();
        $this->save();
    }

    public function getIsCompletedAttribute()
    {
        return ! is_null($this->completed_date);
    }

    /**
     * Scope a query to only include completed ChoreInstances.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed_date', '!=', null);
    }
}
