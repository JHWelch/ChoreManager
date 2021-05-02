<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DemoBanner extends Component
{
    public $show = true;

    public function mount()
    {
        $this->show = session('show_demo_banner', true);
    }

    public function updatedShow($value)
    {
        session(['show_demo_banner' => $value]);
    }
}
