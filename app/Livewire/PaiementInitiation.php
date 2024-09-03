<?php

namespace App\Livewire;

use App\Models\PaiementInitiation as ModelsPaiementInitiation;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;


class PaiementInitiation extends Component
{
    use WithFileUploads;

    public $agency;

    public $initiations = [];
    public $initiations_count = [];

    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    public $initiation_solds = [];
    public $current_initiation = [];
    public $proprietors = [];

    // creadited initiation DATAS
    public $proprietor = "";
    public $sold = "";

    // TRAITEMENT DES ERREURS
    public $proprietor_error = "";
    public $sold_error = "";

    public $generalError = "";
    public $generalSucces = "";
    // 
    public $show_form = false;
    public $show_solds = false;

    public $currentActiveinitiation;
    public $rejet_comments = null;

    function refreshInitiations()
    {
        $agency_initiations = $this->agency->_PayementInitiations;
        ###___
        $this->initiations = $agency_initiations;
        $this->initiations_count = count($agency_initiations);
    }

    function refreshThisAgencyProprietors()
    {
        ###___PROPRIETORS
        $this->proprietors = $this->agency->_Proprietors;
    }

    function mount($agency)
    {
        set_time_limit(0);

        $this->agency = $agency;

        // initiation
        $this->refreshInitiations();

        // PROPRIETAIRES
        $this->refreshThisAgencyProprietors();
    }

    function refresh($message)
    {
        set_time_limit(0);

        $this->generalSucces = $message;

        // initiation
        $this->refreshInitiations();

        // PROPRIETAIRES
        $this->refreshThisAgencyProprietors();

        $this->sold_error = "";
        $this->proprietor_error = "";
    }

    function validate_Initiation($id)
    {
        set_time_limit(0);

        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/payement_initiation/$id/valide")->json();
        if (!$response["status"]) {
            if (array_key_exists("erros", $response)) {
                $message = $response["erros"];
            }
            if (array_key_exists("message", $response)) {
                $message = $response["message"];
            }
            $this->generalError = $message;
        } else {
            $this->refresh($response["message"]);
        }
    }

    function rejet_Initiation($id)
    {
        set_time_limit(0);

        foreach ($this->initiations as $initiation) {
            if ($initiation["id"] == $id) {
                $this->current_initiation = $initiation;
            }
        }

        if (!$this->rejet_comments) {
            $this->generalError = "Veuillez préciser le motif du rejet";
        } else {
            $data = [
                "house" => $this->current_initiation["house"]["id"],
                "state" => $this->current_initiation["house_last_state"]["id"],
                "rejet_comments" => $this->rejet_comments,
            ];

            $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/payement_initiation/$id/rejet", $data)->json();
            // dd($response);
            if (!$response) {
                $this->generalError = "Désolé! Une erreure est survenue, veuillez réessayer pluis tard!";
            } else {
                if (!$response["status"]) {
                    $errors = $response["erros"];
                    if (gettype($errors) == "array") {
                        if (array_key_exists("house", $errors)) {
                            $this->generalError = $errors["house"][0];
                        } elseif (array_key_exists("state", $errors)) {
                            $this->generalError = $errors["state"][0];
                        } elseif (array_key_exists("rejet_comments", $errors)) {
                            $this->generalError = $errors["rejet_comments"][0];
                        }
                    }
                } else {
                    $this->refresh($response["message"]);
                }
            }
        }
    }

    function showForm()
    {
        set_time_limit(0);

        if ($this->show_form) {
            $this->show_form = false;
            $this->show_solds = false;
        } else {
            $this->show_form = true;
            $this->show_solds = false;
        }

        $this->generalError = "";
    }

    function Initiate_Sold()
    {
        set_time_limit(0);

        $data = [
            "agency" => $this->agency["id"],
            "proprietor" => $this->proprietor,
            "amount" => $this->sold,
        ];

        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/payement_initiation/initiateToProprio", $data)->json();

        if (!$response["status"]) {
            $errors = $response["erros"];
            if (gettype($errors) == "array") {
                if (array_key_exists("amount", $errors)) {
                    $this->sold_error = $errors["amount"][0];
                }

                if (array_key_exists("proprietor", $errors)) {
                    $this->proprietor_error = $errors["proprietor"][0];
                }

                if (array_key_exists("agency", $errors)) {
                    $this->generalError = $errors["agency"][0];
                }
            } else {
                $this->generalError = $errors;
            }
        } else {
            $this->refresh($response["message"]);
        }
    }

    public function render()
    {
        return view('livewire.paiement-initiation');
    }
}
