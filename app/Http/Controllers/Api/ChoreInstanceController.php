<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChoreInstance;
use Illuminate\Http\Request;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChoreInstance  $choreInstance
     * @return \Illuminate\Http\Response
     */
    public function show(ChoreInstance $choreInstance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChoreInstance  $choreInstance
     * @return \Illuminate\Http\Response
     */
    public function edit(ChoreInstance $choreInstance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChoreInstance  $choreInstance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChoreInstance $choreInstance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChoreInstance  $choreInstance
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChoreInstance $choreInstance)
    {
        //
    }
}
