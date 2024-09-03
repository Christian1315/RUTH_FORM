<?php
namespace App\Livewire;

// use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Caisses extends Component
{
    public $agency;
    public $agencyAccounts = [];

    function mount($agency)
    {
        $this->agency = $agency;
        $this->agencyAccounts = $this->agency->_AgencyAccounts;
    }

    public function render()
    {
        return view('livewire.caisses');
    }
}
