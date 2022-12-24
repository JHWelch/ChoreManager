<?php

namespace App\Http\Livewire\Concerns;

use Illuminate\Support\Facades\URL;

/**
 * A Livewire component that can go back to the previous page.
 * setGoBackState() function shoud be called in mount() of component.
 */
trait GoesBack
{
    public string $previousUrl;
    public string $currentUrl;
    public string $defaultBackUrl;

    /**
     * Should be called in the mount() of the component.
     *
     * @param string $defaultBackUrl If the page is accessed directly from the url, where to go.
     * @return void
     */
    private function setGoBackState($defaultBackUrl = '/'): void
    {
        $this->previousUrl    = URL::previous();
        $this->currentUrl     = URL::current();
        $this->defaultBackUrl = $defaultBackUrl;
    }

    /**
     * Return redirect to the previous page.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function back()
    {
        return $this->currentUrl === $this->previousUrl
            ? redirect($this->defaultBackUrl)
            : redirect($this->previousUrl);
    }
}
