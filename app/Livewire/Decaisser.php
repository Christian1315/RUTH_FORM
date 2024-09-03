<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Decaisser extends Component
{
    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    public $house = null;
    public $houses = [];

    public $currentSold = 0;

    public $agency;
    public $agencyAccounts = [];

    ###___DATA TRAITEMENT
    public $agency_account;
    public $sold;
    public $description;

    public $agency_account_error;
    public $sold_error;
    public $description_error;

    public $generalError = "";
    public $generalSuccess = "";

    function mount($agency)
    {
        $this->agency = $agency;

        $this->refreshThisAgencyHouses();
        $this->refreshAgencyAccounts();
    }

    function refreshAgencyAccounts()
    {
        $this->agencyAccounts = $this->agency->_AgencyAccounts;
    }

    function  refresh($message)
    {
        $this->generalSuccess = $message;

        $this->agency_account = "";
        $this->sold = "";
        $this->description = "";

        $this->agency_account_error = "";
        $this->sold_error = "";
        $this->description_error = "";
        $this->refreshAgencyAccounts();
        $this->refreshThisAgencyHouses();
    }

    ###___HOUSES
    function refreshThisAgencyHouses()
    {
        $this->houses = $this->agency->_Houses;
    }

    function decaisser()
    {
        if ($this->agency_account == 3 && !$this->house) {
            $this->generalError = "Pour la caisse CDR, la maison est réquise!";
        }else {
            $data = [
                "agency" => $this->agency['id'],
                "agency_account" => $this->agency_account,
                "sold" => $this->sold,
                "description" => $this->description,
                "house" => $this->house,
            ];
    
            // dd($data);
            $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/account/sold/decreditate", $data)->json();
            // dd($response);
            if (!$response) {
                $this->generalError = "Une erreure est survenue! Veillez réessayez à nouveau!";
            } else {
                if (!$response["status"]) {
                    $errors = $response["erros"];
                    if (gettype($errors) == "array") {
                        if (array_key_exists("agency_account", $errors)) {
                            $this->agency_account_error = $errors["agency_account"][0];
                        }
                        if (array_key_exists("sold", $errors)) {
                            $this->sold_error = $errors["sold"][0];
                        }
                        if (array_key_exists("description", $errors)) {
                            $this->description_error = $errors["description"][0];
                        }
                    } else {
                        $this->generalError = $errors;
                    }
                } else {
                    $this->refresh($response["message"]);
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.decaisser');
    }
}
