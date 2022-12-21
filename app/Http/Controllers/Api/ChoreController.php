<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChoreResource;
use App\Models\Chore;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ChoreController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Chore::class, 'chore');
    }

    public function index() : AnonymousResourceCollection
    {
        $user = Auth::user();

        return  ChoreResource::collection(
            Chore::where('user_id', $user->id)
                ->orWhere('team_id', $user->currentTeam->id)
                ->with('nextChoreInstance')
                ->get(),
        );
    }

    public function update(Request $request, Chore $chore) : ChoreResource
    {
        if ($request->has('completed')) {
            if ($request->get('completed')) {
                $chore->complete();
            }
        }

        return ChoreResource::make($chore->refresh());
    }
}
