<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Performance extends Component
{
    public $agency;

    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    public $houses = [];
    public $house = [];

    ###___chambres occupées
    public $all_busy_rooms = [];
    ###___chambres libre
    public $all_frees_rooms_at_first_month = [];

    public $generalSuccess = "";
    public $generalError = "";

    public $showTaux = false;
    public $display_taux_options = false;

    public $generate_caution_by_supervisor = false;
    public $generate_taux_by_supervisor = false;
    public $generate_taux_by_house = false;

    public $supervisors = [];
    public $supervisor = [];
    public $supervisor_error = '';

    public $month = "";
    public $end_date = "";

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

    function GeneratePerformanceBySupervisor()
    {

        $supervisor = $this->supervisor;
        $house = "null";

        $action = "supervisor";

        $data = [
            "month" => $this->month,
        ];
        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/house/" . $this->agency['id'] . "/$supervisor/$house/$action/performance", $data)->json();

        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez réessayez plus tard!";
        } else {
            if (!$response["status"]) {
                $this->houses = [];
            } else {
                $this->generalSuccess = "Résultat de votre recherche";
                $this->houses = $response["data"];
            }
        }

        ####____REINITIALISATION DES DATAS
        $this->all_busy_rooms = [];
        $this->all_frees_rooms_at_first_month = [];
        ###_______

        foreach ($this->houses as $house) {
            array_push($this->all_busy_rooms, $house["busy_rooms"]);
            array_push($this->all_frees_rooms_at_first_month, $house["busy_rooms_at_first_month"]);
        };

        $this->generate_taux_by_supervisor = false;
        $this->generate_taux_by_house = false;
    }

    function GeneratePerformanceByHouse()
    {
        set_time_limit(0);
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/house/" . $this->house . "/retrieve")->json();

        if (!$this->house) {
            $this->generalError = "Veuillez choisir la maison";
        } else {
            if (!$response["status"]) {
                $this->houses = [];
            } else {
                $this->generalSuccess = "Résultat de votre recherche";
                $this->houses = $response["data"];
            }
        }

        ####____REINITIALISATION DES DATAS
        $this->all_busy_rooms = [];
        $this->all_frees_rooms_at_first_month = [];
        ###_______

        ####____
        $this->all_busy_rooms = $response["data"]["busy_rooms"];
        $this->all_frees_rooms_at_first_month = $response["data"]["frees_rooms_at_first_month"];
        ###_______


        $this->generate_taux_by_supervisor = false;
        $this->generate_taux_by_house = false;

        $this->refreshSupervisors();
    }

    function ShowGeneratePerformanceBySupervisorForm()
    {
        if ($this->generate_taux_by_supervisor) {
            $this->generate_taux_by_supervisor = false;
        } else {
            $this->generate_taux_by_supervisor = true;
        }
        $this->generate_taux_by_house = false;
    }

    function ShowGeneratePerformanceByHouseForm()
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

        $this->GenerateAgencyPerformance();
        $this->refreshSupervisors();
    }

    ###___AGENCY PERFORMANCE
    function GenerateAgencyPerformance()
    {
        $supervisor = "null";
        $house = "null";
        $action = "agency";

        $data = [];

        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/house/" . $this->agency['id'] . "/$supervisor/$house/$action/performance", $data)->json();
        if (!$response) {
            return redirect("/")->with("error", "Une erreure est survenue! Veuillez réessayez plus tard!");
        } else {
            if (!$response["status"]) {
                $this->houses = [];
            } else {
                $this->houses = $response["data"];
            }
        }

        foreach ($this->houses as $house) {
            array_push($this->all_busy_rooms, $house["busy_rooms"]);
            array_push($this->all_frees_rooms_at_first_month, $house["busy_rooms_at_first_month"]);
        };
    }

    ###____
    public function render()
    {
        return view('livewire.performance');
    }
}
