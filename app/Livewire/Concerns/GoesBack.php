<?php

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\URL;

trait GoesBack
{
    /**
     * Default back url. Can be overridden in the component.
     */
    public string $defaultBackUrl = '/';

    public string $previousUrl;

    public string $currentUrl;

    public function mountGoesBack(): void
    {
        $this->previousUrl = URL::previous();
        $this->currentUrl = URL::current();
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
