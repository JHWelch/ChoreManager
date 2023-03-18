<?php

namespace App\Http\Controllers;

use App\Models\Chore;
use Illuminate\Http\Request;

class ChoreCompleteController extends Controller
{
    public function index(Request $request, Chore $chore)
    {
        session()->flash('complete', true);

        return redirect(route('chores.show', ['chore' => $chore]));
    }
}
