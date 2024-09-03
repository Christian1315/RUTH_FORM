<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;

class Recovery10 extends Component
{
    use WithFileUploads;

    public $agency;

    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    public $locators = [];

    public $generaleSuccess = "";
    public $generalError = "";

    public $showTaux = false;
    public $taux_link = "";

    public $display_taux_options = false;

    public $generate_caution_by_supervisor = false;
    public $generate_taux_by_supervisor = false;
    public $generate_taux_by_house = false;

    public $supervisors = [];
    public $supervisor = [];
    public $supervisor_error = '';

    public $houses = [];
    public $house = [];
    public $house_error = '';

    public $start_date = "";
    public $end_date = "";


    ###___HOUSES
    function refreshThisAgencyHouses()
    {
        $agency_response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/agency/" . $this->agency['id'] . "/retrieve")->json();
        if (!$agency_response) {
            return redirect("/")->with("error", "Une erreure est survenue! Veuillez réessayez plus tard!");
        } else {
            if (!$agency_response["status"]) {
                $this->houses = [];
            } else {
                ###__TRIONS CEUX QUI SE TROUVENT DANS L'AGENCE ACTUELLE
                ##__on recupere les maisons qui appartiennent aux propriétaires
                ##__ se trouvant dans cette agence
                $agency_houses = [];
                foreach ($agency_response["data"]["__proprietors"] as $proprio) {
                    if ($proprio["agency"]["id"] == $this->agency['id']) { ##__si le proprio appartient à l'agence
                        $proprio_houses = $proprio["houses"];
                        foreach ($proprio_houses as $house) {
                            array_push($agency_houses, $house);
                        }
                    }
                }
                $this->houses = $agency_houses;
            }
        }
    }

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

        $this->showTaux = false;
    }

    function showGenerateTaux()
    {
        set_time_limit(0);

        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/locataire/" . $this->agency['id'] . "/imprime_taux_10_agency")->json();

        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez bien réessayer plus tard";
        } else {
            if (!$response["status"]) {
                $this->generalError = $response["erros"];
            } else {
                $this->showTaux = true;
                $this->taux_link = $response["data"]["taux_html_url"];
            }
        }
        $this->generate_taux_by_supervisor = false;
        $this->generate_taux_by_house = false;
    }

    function GenerateTauxBySupervisor()
    {
        set_time_limit(0);

        if (!$this->supervisor) {
            $this->generalError = "Veuillez choisir un superviseur";
        } else {
            $data = [
                "start_date" => $this->start_date,
                "end_date" => $this->end_date,
            ];

            $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/locataire/" . $this->agency['id'] . "/$this->supervisor/imprime_taux_10_supervisor", $data)->json();
            if (!$response) {
                $this->generalError = "Une erreure est survenue! Veuillez bien réessayer plus tard";
            } else {
                if (!$response["status"]) {
                    $this->generalError = $response["erros"];
                } else {
                    $this->showTaux = true;
                    $this->taux_link = $response["data"]["taux_html_url"];
                }
            }
        }

        $this->generate_taux_by_supervisor = false;
        $this->generate_taux_by_house = false;
    }

    function GenerateTauxByHouse()
    {
        set_time_limit(0);

        if (!$this->house) {
            $this->generalError = "Veuillez choisir la maison";
        } else {
            $data = [
                "start_date" => $this->start_date,
                "end_date" => $this->end_date,
            ];

            $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/locataire/" . $this->agency['id'] . "/$this->house/imprime_taux_10_house", $data)->json();

            if (!$response) {
                $this->generalError = "Une erreure est survenue! Veuillez bien réessayer plus tard";
            } else {
                if (!$response["status"]) {
                    $this->generalError = $response["erros"];
                } else {
                    $this->showTaux = true;
                    $this->taux_link = $response["data"]["taux_html_url"];
                }
            }
        }

        $this->generate_taux_by_supervisor = false;
        $this->generate_taux_by_house = false;
    }

    function ShowGenerateTauxBySupervisorForm()
    {
        if ($this->generate_taux_by_supervisor) {
            $this->generate_taux_by_supervisor = false;
        } else {
            $this->generate_taux_by_supervisor = true;
        }
        $this->showTaux = false;
        $this->generate_taux_by_house = false;
    }

    function ShowGenerateTauxByHouseForm()
    {
        if ($this->generate_taux_by_house) {
            $this->generate_taux_by_house = false;
        } else {
            $this->generate_taux_by_house = true;
        }
        $this->showTaux = false;
        $this->generate_taux_by_supervisor = false;
    }


    function refreshLocators($agencyId)
    {
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/locataire/$agencyId/recovery_10_to_echeance_date")->json();

        if (!$response) {
            $this->generalError = "Désolé! Une erreure est survenue! Veuillez réeesayer plus tard";
        } else {
            if (!$response["status"]) {
                $this->locators = [];
            } else {
                $this->locators = $response["data"];
            }
        }
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

        $this->refreshLocators($this->agency["id"]);
        $this->refreshSupervisors();
        $this->refreshThisAgencyHouses();
    }

    public function render()
    {
        return view('livewire.recovery10');
    }
}
