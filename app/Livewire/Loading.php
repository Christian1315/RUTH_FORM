<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;


class Loading extends Component
{
    public $loading = false;

    // LISTENERS LOADING
    protected $listeners = ["loading" => "HandleLoading"];

    // MANAGING LOADING
    #[On("loading")]
    function HandleLoading($value)
    {
        $this->loading = $value;
    }

    // 
    public function render()
    {
        return view('livewire.loading');
    }
}
