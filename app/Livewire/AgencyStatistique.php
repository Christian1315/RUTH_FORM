<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;

class AgencyStatistique extends Component
{
    use WithFileUploads;

    public $agency = [];
    public $cautions_link = "";
    public $showCautions = false;
    public $generalSuccess = false;

    public $houses = [];
    public $houses_count = [];

    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    public $locatorsBefore = [];
    public $locatorsAfter = [];

    public $location_locatorsBefore = [];
    public $location_locatorsAfter = [];
    public $total_locators;
    public $afterStopDateTotal_to_paid;
    public $beforeStopDateTotal_to_paid;


    public $current_houseId = [];

    public $generalError = "";

    public $show_locatorsBefore = false;
    public $show_locatorsAfter = false;

    public $currentActivelocation;
    public $currentHouse = [];

    public $current_agency;

    ###___HOUSES
    function refreshThisAgencyHouses()
    {
        $this->houses = $this->current_agency->_Houses;
        $this->houses_count = count($this->houses);
    }

    function mount($agency)
    {
        set_time_limit(0);
        $this->current_agency = $agency;
        // LOCATIONS
        $this->refreshThisAgencyHouses();
    }

    function refresh($message)
    {
        $this->generalSuccess = $message;
        $this->showCautions = false;
        $this->show_locatorsBefore = false;
        $this->show_locatorsAfter = false;
        $this->currentHouse = [];
    }

    public function showLocatorBeforeStates(int $houseId)
    {
        set_time_limit(0);

        $this->show_locatorsAfter = false;
        $location = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/paiement/{$houseId}/filtre_after_stateDate_stoped")->json();

        if (array_key_exists("erros", $location)) {
            $this->locatorsBefore = [];
            $this->generalError = $location["erros"];
        } else {
            $this->locatorsBefore = $location["data"]["beforeStopDate"];
            $this->total_locators = $location["data"]["total_locators"];
            $this->beforeStopDateTotal_to_paid = $location["data"]["beforeStopDateTotal_to_paid"];
            $this->generalError = "";
        }
        $this->current_houseId = $houseId;

        if ($this->show_locatorsBefore) {
            $this->show_locatorsBefore = false;
        } else {
            $this->show_locatorsBefore = true;
        }

        $this->showCautions = false;
    }

    public function showLocatorAfterStates(int $houseId)
    {
        set_time_limit(0);

        $this->show_locatorsBefore = false;
        $location = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/paiement/{$houseId}/filtre_after_stateDate_stoped")->json();

        if (array_key_exists("erros", $location)) {
            $this->locatorsAfter = [];
            $this->generalError = $location["erros"];
        } else {
            $this->locatorsAfter = $location["data"]["afterStopDate"];
            $this->total_locators = $location["data"]["total_locators"];
            $this->afterStopDateTotal_to_paid = $location["data"]["afterStopDateTotal_to_paid"];
            $this->generalError = "";
        }

        // dd($this->afterStopDateTotal_to_paid);
        $this->current_houseId = $houseId;

        if ($this->show_locatorsAfter) {
            $this->show_locatorsAfter = false;
        } else {
            $this->show_locatorsAfter = true;
        }

        $this->showCautions = false;
    }

    function ImprimeLocatorsAfterStateStoped($houseId)
    {
        set_time_limit(0);
        $action = "after";
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/location/" . $this->agency["id"] . "/" . $houseId . "/$action/imprime_states")->json();

        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez bien réessayer plus tard";
        } else {
            if (!$response["status"]) {
                $this->generalError = $response["erros"];
            } else {
                $this->showCautions = true;
                $this->cautions_link = $response["data"]["caution_html_url"];
            }
        }
    }

    function ImprimeLocatorsBeforeStateStoped($houseId)
    {
        set_time_limit(0);
        $action = "before";
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/location/" . $this->agency["id"] . "/" . $houseId . "/$action/imprime_states")->json();

        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez bien réessayer plus tard";
        } else {
            if (!$response["status"]) {
                $this->generalError = $response["erros"];
            } else {
                $this->showCautions = true;
                $this->cautions_link = $response["data"]["caution_html_url"];
            }
        }
    }

    public function render()
    {
        return view('livewire.agency-statistique');
    }
}
