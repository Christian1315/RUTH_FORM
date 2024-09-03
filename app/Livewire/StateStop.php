<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class StateStop extends Component
{

    public $houseId;
    public $locations=[];

    public $states=[];


    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $hearders = [];


    function mount($houseId)
    {
        $this->houseId = $houseId;
        // dd($houseId);
    }

    public function render()
    {
        return view('livewire.state-stop');
    }
}
