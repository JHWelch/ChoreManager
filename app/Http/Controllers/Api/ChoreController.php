<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChoreResource;
use App\Models\Chore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChoreController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Chore::class, 'chore');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        return  ChoreResource::collection(
            Chore::where('user_id', $user->id)
                ->orWhere('team_id', $user->currentTeam->id)
                ->with('nextChoreInstance')
                ->get(),
        );
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chore  $chore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chore $chore)
    {
        if ($request->has('completed')) {
            if ($request->get('completed')) {
                $chore->complete();
            }
        }

        return ChoreResource::make($chore->refresh());
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
