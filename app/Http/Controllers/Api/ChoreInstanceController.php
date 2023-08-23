<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ChoreInstanceController extends Controller
{
    public function index(): Collection
    {
        return Auth::user()
            ->chores()
            ->onlyWithNextInstance()
            ->orderBy('chore_instances.due_date')
            ->get();
    }
}
