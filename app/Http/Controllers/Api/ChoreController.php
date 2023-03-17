<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChoreResource;
use App\Models\Chore;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
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
        $input = $request->validate([
            'next_due_date' => 'date|nullable',
            'completed'     => 'boolean|nullable',
        ]);

        if (Arr::get($input, 'completed')) {
            $chore->complete();
        }

        if ($nextDueDate = Arr::get($input, 'next_due_date')) {
            $chore->snooze(Carbon::parse($nextDueDate));
        }

        return ChoreResource::make($chore->refresh());
    }
}
