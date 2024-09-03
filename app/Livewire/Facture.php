<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Facture extends Component
{

    public $show_form = false;
    public $agency = [];
    public $factures = [];

    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    public $search = '';

    public $generalError = "";
    public $generalSuccess = "";

    public $supervisors = [];
    public $supervisor;

    public $display_taux_options = false;
    public $generate_taux_by_supervisor = false;
    public $generate_taux_by_house = false;
    public $generate_caution_by_supervisor = false;

    function showForm()
    {
        if ($this->show_form) {
            $this->show_form = false;
        } else {
            $this->show_form = true;
        }
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

    // REFRESH SUPERVISOR
    function refreshSupervisors()
    {
        $users = User::with(["account_agents"])->get();
        $supervisors = [];

        foreach ($users as $user) {
            $user_roles = $user->roles; ##recuperation des roles de ce user

            foreach ($user_roles as $user_role) {
                if ($user_role->id == env("SUPERVISOR_ROLE_ID")) {
                    array_push($supervisors, $user);
                }
            }
        }
        $this->supervisors = array_unique($supervisors);
    }

    function displayTauxOptions()
    {
        if ($this->display_taux_options) {
            $this->display_taux_options = false;
        } else {
            $this->display_taux_options = true;
        }
    }

    function mount($agency)
    {
        $this->agency = $agency;
        $this->AgencyFactures();
        $this->refreshSupervisors();
    }

    function AgencyFactures()
    {
        $factures = [];

        foreach ($this->agency->_Proprietors as $proprio) {
            if ($proprio->Agency->id == $this->agency->id) { ##__si le proprio appartient à l'agence
                $proprio_houses = $proprio->Houses;

                foreach ($proprio_houses as $house) {
                    foreach ($house->Locations as $location) {
                        foreach ($location->AllFactures as $facture) {
                            if (!$facture["state_facture"]) {
                                array_push($factures, $facture);
                            }
                        }
                    }
                }
            }
        }

        $this->factures = $factures;
    }

    function GenerateBilanBySupervisor()
    {
        $supervisor = $this->supervisor;
        $action = "supervisor";

        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/agency/" . $this->agency['id'] . "/$supervisor/$action/factures")->json();
        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez réessayez plus tard!";
        } else {
            if (!$response["status"]) {
                $this->generalError = $response["erros"];
            } else {
                $this->factures = [];
                $this->factures =  $response["data"];
            }
        }
    }

    public function searching()
    {
        set_time_limit(0);

        $data = [
            "search" => $this->search
        ];

        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/facture/search", $data)->json();

        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez réessayez plus tard!";
        } else {
            if (!$response["status"]) {
                $this->generalError = $response["erros"];
                $this->generalSuccess = "";
            } else {
                $successMsg = $response["message"];
                $this->generalSuccess = $successMsg;
                $this->generalError = "";

                $agency_factures = [];

                foreach ($response["data"] as $facture) {
                    if ($facture["location"]["agency"] == $this->agency['id']) { ##__si le proprio appartient à l'agence
                        array_push($agency_factures, $facture);
                    }
                }
                $this->factures = $agency_factures;
            }
        }
    }

    public function render()
    {
        return view('livewire.facture');
    }
}
