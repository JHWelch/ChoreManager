<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChoreInstanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::user()
            ->chores()
            ->onlyWithNextInstance()
            ->orderBy('chore_instances.due_date')
            ->get();
    }
}
