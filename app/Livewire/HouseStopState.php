<?php

namespace App\Livewire;

use App\Models\House;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class HouseStopState extends Component
{
    public $agency;
    public $house = [];

    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    public $recovery_rapport="";

    public $generalError = "";
    public $generalSuccess = "";

    
    function refresh($message) {
        $this->generalSuccess = $message;
    }

    function mount($agency, $house)
    {
        $this->house = GET_HOUSE_DETAIL($house);
    }

    function StopHouseState()
    {
        $data = [
            "owner" => $this->userId,
            "recovery_rapport" => $this->recovery_rapport,
            "house" => $this->house["id"],
        ];

        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/house_state/stop", $data)->json();

        if (!$response) {
            $this->generalError = "Désolé! Une erreure est survenue, veuille réessayer plus tard!";
        }
        if (!$response["status"]) {
            $this->generalError = $response["erros"];
        } else {
            ##___
            $this->refresh($response["message"]);
        }
    }
    public function render()
    {
        return view('livewire.house-stop-state');
    }
}
