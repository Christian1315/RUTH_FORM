<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Encaisser extends Component
{
    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    public $house = null;
    public $houses = [];

    public $water = true;
    public $electricity = false;


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

    public $show_consomation_account_info = true;
    public $start_index = false;
    public $end_index = false;

    public $locators = "";
    public $locator = "";

    public $start_index_error = "";
    public $end_index_error = "";
    public $locator_error = "";
    public $house_error;

    public $currentAccount = "";

    ###___HOUSES
    function refreshThisAgencyHouses()
    {
        $this->houses = $this->agency->_Houses;
    }

    function refreshThisAgencyLocators()
    {
        ###___LOCATORS
        $this->locators = $this->agency-> _Locataires;
    }

    function mount($agency)
    {
        $this->agency = $agency;

        $this->refreshThisAgencyLocators();
        $this->agencyAccounts = $this->agency->_AgencyAccounts;

        $this->refreshThisAgencyHouses();
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

        $this->locator = "";
        $this->locator_error = "";
        $this->house_error = "";

        $this->start_index = "";
        $this->end_index = "";

        $this->start_index_error = "";
        $this->end_index_error = "";

        $this->house_error = "";

        $this->refreshAgencyAccounts();
    }

    function ancaisser()
    {
        $data = [
            "agency" => $this->agency["id"],
            "agency_account" => $this->agency_account,
            "locator" => $this->locator ? $this->locator : null,
            "start_index" => $this->start_index ? $this->start_index : null,
            "end_index" => $this->end_index ? $this->end_index : null,
            "house" => $this->house,
            "sold" => $this->sold,
            "description" => $this->description,
        ];

        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/account/sold/creditate", $data)->json();
        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veillez réessayez à nouveau!";
        } else {
            if (!$response["status"]) {
                $errors = $response["erros"];
                if (gettype($errors) == "array") {
                    if (array_key_exists("agency_account", $errors)) {
                        $this->agency_account_error = $errors["agency_account"][0];
                    }

                    if (array_key_exists("agency", $errors)) {
                        $this->generalError = $errors["agency"][0];
                    }

                    if (array_key_exists("sold", $errors)) {
                        $this->sold_error = $errors["sold"][0];
                    }
                    if (array_key_exists("description", $errors)) {
                        $this->description_error = $errors["description"][0];
                    }

                    if (array_key_exists("locator", $errors)) {
                        $this->locator_error = $errors["locator"][0];
                    }

                    if (array_key_exists("start_index", $errors)) {
                        $this->start_index_error = $errors["start_index"][0];
                    }
                    if (array_key_exists("end_index", $errors)) {
                        $this->end_index_error = $errors["end_index"][0];
                    }

                    if (array_key_exists("house", $errors)) {
                        $this->house_error = $errors["house"][0];
                    }
                } else {
                    $this->generalError = $errors;
                }
            } else {
                $this->refresh($response["message"]);
            }
        }
    }

    public function render()
    {
        return view('livewire.encaisser');
    }
}
