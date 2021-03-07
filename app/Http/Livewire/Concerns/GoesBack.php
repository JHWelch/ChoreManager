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
    public $previous_url;

    /**
     * Current page URL.
     *
     * @var string
     */
    public $current_url;
    public $default_back_url;

    /**
     * Should be called in the mount() of the component.
     *
     * @param string $default_back_url If the page is accessed directly from the url, where to go.
     * @return void
     */
    private function setGoBackState($default_back_url = '/')
    {
        $this->previous_url     = URL::previous();
        $this->current_url      = URL::current();
        $this->default_back_url = $default_back_url;
    }

    /**
     * Return redirect to the previous page.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function back()
    {
        if ($this->current_url === $this->previous_url) {
            return redirect($this->default_back_url);
        }

        return redirect($this->previous_url);
    }
}
