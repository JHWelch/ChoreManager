<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chore;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ChoreInstanceController extends Controller
{
    /** @return Collection<int, Chore> */
    public function index(): Collection
    {
        return Auth::user()
            ->chores()
            ->onlyWithNextInstance()
            ->orderBy('chore_instances.due_date')
            ->get();
    }
}
