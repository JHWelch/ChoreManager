<?php

namespace App\Http\Controllers;

use App\Models\Chore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChoreCompleteController extends Controller
{
    public function index(Request $request, Chore $chore): RedirectResponse
    {
        session()->flash('complete', true);

        return redirect(route('chores.show', ['chore' => $chore]));
    }
}
