<?php

namespace App\Http\Livewire\Concerns;

use Illuminate\Support\Facades\URL;

/**
 * A Livewire component that can go back to the previous page.
 * setPrevious() function shoud be called in mount() of component.
 */
trait GoesBack
{
    /**
     * Previous page URL.
     *
     * @var string
     */
    public $previous;

    /**
     * Should be called in the mount() of the component.
     *
     * @return void
     */
    private function setPrevious()
    {
        $this->previous = URL::previous();
    }

    /**
     * Return redirect to the previous page.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function back()
    {
        return redirect($this->previous);
    }
}
