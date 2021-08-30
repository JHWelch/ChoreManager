<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::user()->chores;
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
     * @param  \App\Models\Chore  $chore
     * @return \Illuminate\Http\Response
     */
    public function show(Chore $chore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chore  $chore
     * @return \Illuminate\Http\Response
     */
    public function edit(Chore $chore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chore  $chore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chore $chore)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chore  $chore
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chore $chore)
    {
        //
    }
}
