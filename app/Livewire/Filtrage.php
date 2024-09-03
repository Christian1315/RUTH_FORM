<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Filtrage extends Component
{
    public $agency;

    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    public $proprietors = [];
    public $locators = [];
    public $locations = [];
    public $rooms = [];
    public $houses = [];
    public $supervisors = [];

    public $factures = [];
    public $factures_total_amount = [];
    public $show_factures = false;

    public $show_moved_locators = false;
    public $moved_locators = [];

    public $start_date = "";
    public $end_date = "";

    public $generaleSuccess = "";
    public $generalError = "";

    public $showTaux = false;
    public $display_taux_options = false;

    public $generate_caution_by_supervisor = false;
    public $generate_taux_by_supervisor = false;
    public $generate_taux_by_house = false;

    public $supervisor = [];
    public $supervisor_error = '';

    // REFRESH SUPERVISOR
    function refreshSupervisors()
    {
        $supervisors = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/agency/" . $this->agency['id'] . "/supervisors")->json();
        if (!$supervisors["status"]) {
            $this->supervisors = 0;
        } else {
            $this->supervisors = $supervisors["data"];
        }
    }


    function displayTauxOptions()
    {
        if ($this->display_taux_options) {
            $this->display_taux_options = false;
        } else {
            $this->display_taux_options = true;
        }
    }

    function GenerateBilanBySupervisor()
    {
        set_time_limit(0);

        $supervisor = $this->supervisor;
        $action = "supervisor";

        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/agency/" . $this->agency['id'] . "/$supervisor/$action/bilan")->json();
        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez réessayez plus tard!";
        } else {
            if (!$response["status"]) {
                $this->houses = [];
            } else {
                ####____REINITIALISATION DES DATAS
                $this->proprietors = [];
                $this->houses = [];
                $this->locations = [];
                $this->rooms = [];
                $this->factures = [];
                $this->factures_total_amount = [];
                $this->rooms = [];
                ###_______

                $this->proprietors = $response["data"]["__proprietors"];
                $this->houses = $response["data"]["agency_houses"];
                $this->locators = $response["data"]["locators"];
                $this->locations = $response["data"]["locations"];
                $this->rooms = $response["data"]["rooms"];
                $this->factures = $response["data"]["_factures"];
                $this->factures_total_amount = $response["data"]["factures_total_amount"];
            }
        }


        $this->generate_taux_by_supervisor = false;
        $this->generate_taux_by_house = false;
    }

    function GenerateBilanByHouse()
    {
        set_time_limit(0);
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/house/" . $this->house . "/retrieve")->json();

        if (!$this->house) {
            $this->generalError = "Veuillez choisir la maison";
        } else {

            if (!$response) {
                $this->generalError = "Une erreure est survenue! Veuillez bien réessayer plus tard";
            } else {
                if (!$response["status"]) {
                    $this->generalError = $response["erros"];
                } else {

                    ####____REINITIALISATION DES DATAS
                    $this->proprietors = [];
                    $this->houses = [];
                    $this->locations = [];
                    $this->rooms = [];
                    $this->factures = [];
                    $this->factures_total_amount = [];
                    $this->rooms = [];
                    ###_______

                    $this->proprietors = $response["data"]["__proprietors"];
                    $this->houses = $response["data"]["agency_houses"];
                    $this->locators = $response["data"]["locators"];
                    $this->locations = $response["data"]["locations"];
                    $this->rooms = $response["data"]["rooms"];
                    $this->factures = $response["data"]["_factures"];
                    $this->factures_total_amount = $response["data"]["factures_total_amount"];
                }
            }
        }

        $this->generate_taux_by_supervisor = false;
        $this->generate_taux_by_house = false;

        $this->refreshSupervisors();
    }

    function ShowGenerateBilanBySupervisorForm()
    {
        if ($this->generate_taux_by_supervisor) {
            $this->generate_taux_by_supervisor = false;
        } else {
            $this->generate_taux_by_supervisor = true;
        }
        $this->generate_taux_by_house = false;
    }

    function ShowGenerateBilanByHouseForm()
    {
        if ($this->generate_taux_by_house) {
            $this->generate_taux_by_house = false;
        } else {
            $this->generate_taux_by_house = true;
        }
        $this->generate_taux_by_supervisor = false;
    }

    function mount($agency)
    {
        set_time_limit(0);

        $this->agency = $agency;

        $this->BASE_URL = env("BASE_URL");
        $this->token = session()->get("token");
        $this->userId = session()->get("userId");

        $this->headers = [
            "Authorization" => "Bearer " . $this->token,
        ];

        $this->refreshThisAgencyBilan();
        $this->refreshSupervisors();
    }

    ###___HOUSES
    function refreshThisAgencyBilan()
    {
        $supervisor = "null";
        $action = "agency";

        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/agency/" . $this->agency['id'] . "/$supervisor/$action/bilan")->json();
        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez réessayez plus tard!";
        } else {
            if (!$response["status"]) {
                $this->houses = [];
            } else {

                $this->proprietors = $response["data"]["__proprietors"];
                $this->houses = $response["data"]["agency_houses"];
                $this->locators = $response["data"]["locators"];
                $this->locations = $response["data"]["locations"];
                $this->rooms = $response["data"]["rooms"];
                $this->factures = $response["data"]["_factures"];
                $this->factures_total_amount = $response["data"]["factures_total_amount"];
            }
        }
    }

    function ShowFactures()
    {
        if ($this->show_factures) {
            $this->show_factures = false;
        } else {
            $this->show_factures = true;
        }
        $this->show_moved_locators = false;
    }

    function ShowMovedLocators()
    {
        if ($this->show_moved_locators) {
            $this->show_moved_locators = false;
        } else {
            $this->show_moved_locators = true;
        }
        $this->show_factures = false;
    }

    ###____

    public function render()
    {
        return view('livewire.filtrage');
    }
}
