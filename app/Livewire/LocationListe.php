<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class LocationListe extends Component
{

    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $hearders = [];

    public $house_rooms = [];
    public $current_house = [];
    public $locations = [];

    public $house;

    function __construct()
    {
        set_time_limit(0);

        // dd($this->house);
        $this->BASE_URL = env("BASE_URL");
        // session()->forget("token");
        $this->token = session()->get("token");
        $this->userId = session()->get("userId");

        $this->hearders = [
            "Authorization" => "Bearer " . $this->token,
        ];

        // LOCATIONS
        $locations = Http::withHeaders($this->hearders)->get($this->BASE_URL . "immo/location/all")->json();
        if (!$locations["status"]) {
            $this->locations = [];
        }else {
            $this->locations = $locations["data"];
        }
    }
    public function render()
    {
        return view('livewire.location-liste');
    }
}
