<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DemoBanner extends Component
{
    public bool $show = true;

    public function mount() : void
    {
        $this->show = session('show_demo_banner', true);
    }

    public function updatedShow(bool $value) : void
    {
        session(['show_demo_banner' => $value]);
    }
}
