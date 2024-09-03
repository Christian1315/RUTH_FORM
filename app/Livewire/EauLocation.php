<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use Livewire\Component;
use Livewire\WithFileUploads;

class EauLocation extends Component
{
    use WithFileUploads;

    public $current_agency;

    public $old_locations = [];
    public $locations = [];

    public $houses = [];
    public $current_house = [];
    public $house;
    public $houseId = 0;
    public $state = 0;

    public $houseStates = [];
    public $currentHouseState = 0;


    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    public $current_location = [];
    public $activeLocationId;

    public $currentLocationFactures = [];

    public $end_index = "";
    public $end_index_error = "";
    public $location = "";
    public $location_error = "";

    public $generalError = "";
    public $generalSuccess = "";

    public $show_form = false;
    public $show_factures = false;
    public $show_house_for_state_imprime_form = false;
    public $show_state_imprime_form = false;
    public $show_state_imprime = false;

    public $showHouseFom = false;
    public $actualized = false;

    public $state_html_url = "";

    public $locators = [];
    public $locator = [];

    public $filtre_by_house = false;
    public $filtre_by_locator = false;
    public $display_filtre_options = false;

    public $forLocation = false;
    public $search = "";

    ###__LOCATIONS
    function refreshThisAgencyLocations()
    {
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/location/agency/" . $this->current_agency['id'] . "/water")->json();
        if (!$response) {
            $this->generalError = "Désolé! Une erreure est survenue, veuillez réessayer plus tard!";
        } else {
            if (!$response["status"]) {
                $this->locations = [];
            } else {

                ##___
                $this->locations = $response["data"];
                $this->old_locations = $response["data"];
            }
        }
    }

    ###___HOUSES
    function refreshThisAgencyHouses()
    {
        $agency_response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/agency/" . $this->current_agency['id'] . "/retrieve")->json();
        if (!$agency_response) {
            return redirect("/")->with("error", "Une erreure est survenue! Veuillez réessayez plus tard!");
        } else {
            if (!$agency_response["status"]) {
                $this->houses = "[]";
            } else {
                ###__TRIONS CEUX QUI SE TROUVENT DANS L'AGENCE ACTUELLE
                ##__on recupere les maisons qui appartiennent aux propriétaires
                ##__ se trouvant dans cette agence
                $agency_houses = [];
                foreach ($agency_response["data"]["__proprietors"] as $proprio) {
                    if ($proprio["agency"]["id"] == $this->current_agency['id']) { ##__si le proprio appartient à l'agence
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

    ###__REFRESH CURRENT LOCATION FACTURES
    function refreshsCurrentLocationFactures($locationId)
    {
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/location/$locationId/retrieve")->json();

        if (!$response) {
            $this->generalError = "Désolé! Une erreure est survenue, veuillez réessayer plus tard!";
        } else {
            if (!$response["status"]) {
                $this->currentLocationFactures = [];
            } else {
                ##___
                $location = $response["data"];
                $this->currentLocationFactures = $location["water_factures"];
                $this->show_factures = true;
            }
        }

        $this->showHouseFom = false;
    }

    ###___LOCATORS
    function refreshThisAgencyLocators()
    {
        ###___LOCATORS
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/locataire/" . $this->current_agency['id'] . "/all")->json();
        if (!$response["status"]) {
            $this->locators = [];
        } else {
            ##___
            $this->locators = $response["data"];
        }
    }

    function DisplayFiltreOptions()
    {
        if ($this->display_filtre_options) {
            $this->display_filtre_options = false;
        } else {
            $this->display_filtre_options = true;
        }
    }

    function FiltreByLocator()
    {
        $locations_reformed = [];

        if (!$this->locator) {
            $this->generalError = "Veuillez choisir un locataire";
        } else {

            foreach ($this->old_locations as $location) {

                if ($location["locataire"]["id"] == $this->locator) {
                    array_push($locations_reformed, $location);
                }
            }
        }

        $this->filtre_by_locator = false;
        $this->filtre_by_house = false;

        ####____

        if (count($locations_reformed) != 0) {
            $this->generalSuccess = "Résultat de votre recherche!";
            $this->locations = $locations_reformed;
        } else {
            $this->generalError = "Aucun résultat trouvé!";
        }
    }

    function FiltreByHouse()
    {
        $locations_reformed = [];

        if (!$this->house) {
            $this->generalError = "Veuillez choisir un locataire";
        } else {

            foreach ($this->old_locations as $location) {

                if ($location["house"]["id"] == $this->house) {
                    array_push($locations_reformed, $location);
                }
            }
        }

        $this->filtre_by_locator = false;
        $this->filtre_by_house = false;

        ####____

        if (count($locations_reformed) != 0) {
            $this->generalSuccess = "Résultat de votre recherche!";
            $this->generalError = "";
            $this->locations = $locations_reformed;
        } else {
            $this->generalError = "Aucun résultat trouvé!";
            $this->generalSuccess = "";
        }
    }

    function ShowFiltreByLocatorForm()
    {
        if ($this->filtre_by_locator) {
            $this->filtre_by_locator = false;
        } else {
            $this->filtre_by_locator = true;
        }
        $this->filtre_by_house = false;
    }

    function ShowFiltreByHouseForm()
    {
        if ($this->filtre_by_house) {
            $this->filtre_by_house = false;
        } else {
            $this->filtre_by_house = true;
        }
        $this->filtre_by_locator = false;
    }

    function mount($agency)
    {
        set_time_limit(0);
        $this->current_agency = $agency;

        $this->BASE_URL = env("BASE_URL");
        $this->token = session()->get("token");
        $this->userId = session()->get("userId");

        $this->headers = [
            "Authorization" => "Bearer " . $this->token,
        ];

        $this->refresh("");
    }

    function showForm()
    {
        if ($this->show_form) {
            $this->show_form = false;
        } else {
            $this->show_form = true;
        }

        ##__
        $this->showHouseFom = false;
        $this->show_factures = false;

        if (!$this->house) {
            $this->generalError = "Désolé! Veuillez choisir une maison";
        } else {

            $actualized_locations = [];

            foreach ($this->old_locations as $location) {
                if ($location["house"]["id"] == $this->house) {
                    array_push($actualized_locations, $location);
                }
            }

            ###_____
            foreach ($this->houses as $house) {
                if ($house["id"] == $this->house) {
                    $this->current_house = $house;
                }
            }

            ##___
            $this->locations = $actualized_locations;
            $this->houseId = $this->house;
        }
    }

    function _Show($forLocation)
    {
        if ($forLocation == 0) {
            $this->forLocation = false;
        } else {
            $this->forLocation = true;
        }

        $this->ShowHouseForm();
    }

    function ShowLocationFactures($locationId)
    {
        $this->refreshsCurrentLocationFactures($locationId);

        $this->showHouseFom = false;
        $this->show_form = false;
    }

    function CloseFcaturesForm()
    {
        $this->show_factures = false;
    }

    function PayFacture($factureId)
    {
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/facture/water_facture/$factureId/payement")->json();

        if (!$response) {
            $this->generalError = "Désolé! Une erreure est survenue, veuille réessayer plus tard!";
        } else {
            if (!$response["status"]) {
                $this->generalError = $response["erros"];
            } else {
                ##___
                $this->refresh($response["message"]);
            }
        }
    }

    function ShowHouseForm()
    {
        if ($this->showHouseFom) {
            $this->showHouseFom = false;
        } else {
            $this->showHouseFom = true;
        }

        $this->show_form = false;
        $this->show_factures = false;
        $this->actualized = false;
        $this->show_house_for_state_imprime_form = false;
        $this->show_state_imprime_form = false;
    }

    function ShowHouseForStateImprimeForm()
    {
        if ($this->show_house_for_state_imprime_form) {
            $this->show_house_for_state_imprime_form = false;
        } else {
            $this->show_house_for_state_imprime_form = true;
        }

        $this->show_form = false;
        $this->show_factures = false;
        $this->actualized = false;
        $this->show_state_imprime_form = false;
        $this->show_state_imprime = false;
    }

    function ActualizeLocations()
    {
        if (!$this->house) {
            $this->generalError = "Désolé! Veuillez choisir une maison";
        } else {
            if ($this->actualized) {
                $this->locations = $this->old_locations;
            }
            ###___
            $actualized_locations = [];
            foreach ($this->locations as $location) {
                if ($location["house"]["id"] == $this->house) {
                    array_push($actualized_locations, $location);
                }
            }

            ###___
            if (count($this->locations) == 0) {
                $this->actualized = false;
            } else {
                $this->actualized = true;
            }
            ###___

            ##___
            $this->locations = $actualized_locations;
            $this->houseId = $this->house;
        }
    }

    function SelectHouseForStateImprime()
    {

        $this->houseId = $this->house;
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/facture/water_facture/house_state/house/" . $this->houseId . "/all")->json();

        if (!$response) {
            $this->generalError = "Désolé! Une erreure est survenue, veuille réessayer plus tard!";
        }
        if (!$response["status"]) {
            $this->generalError = $response["erros"];
        } else {
            ##___
            $this->houseStates = $response["data"];
            $this->show_state_imprime_form = true;

            $this->show_form = false;
            $this->show_factures = false;
            $this->actualized = false;
            $this->show_house_for_state_imprime_form = false;
            $this->show_state_imprime = false;
        }
    }

    function ImprimeSelectState()
    {
        set_time_limit(0);

        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/facture/water_facture/house_state/house/" . $this->state . "/water_imprime")->json();

        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez bien réessayer plus tard";
        } else {
            if (!$response["status"]) {
                $this->generalError = $response["erros"];
            } else {
                $this->state_html_url = $response["data"]["state_html_url"];

                $this->show_form = false;
                $this->show_factures = false;
                $this->actualized = false;
                $this->show_state_imprime_form = false;
                $this->show_state_imprime = true;
            }
        }
    }

    function StopElectricityHouseState($houseId)
    {
        $data = [
            "owner" => $this->userId,
            "house" => $houseId
        ];

        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/facture/water_facture/house_state/stop", $data)->json();
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

    function  refresh($message)
    {
        $this->generalSuccess = $message;

        $this->current_location = [];
        $this->activeLocationId;

        $this->end_index_error = "";
        $this->houseId = 0;

        $this->location = "";
        $this->location_error = "";

        $this->end_index = "";
        $this->end_index_error = "";

        $this->location_error = "";

        $this->show_form = false;
        $this->show_factures = false;
        $this->currentLocationFactures = [];

        $this->showHouseFom = false;
        $this->actualized = false;
        $this->show_house_for_state_imprime_form = false;
        $this->show_state_imprime_form = false;
        $this->show_state_imprime = false;

        $this->refreshThisAgencyLocations();
        $this->refreshThisAgencyHouses();
        $this->refreshThisAgencyLocators();
    }

    function GenerateFacture()
    {
        $data = [
            "location" => $this->location,
            "end_index" => $this->end_index,
        ];

        ###__
        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/facture/water_facture/generate", $data)->json();

        if (!$response) {
            $this->generalError = "Désolé! Une erreure est survenue, veuillez réesayer plus tard";
        } else {
            if (!$response["status"]) {
                $errors = $response["erros"];
                if (gettype($errors) == "array") {

                    if (array_key_exists("location", $errors)) {
                        $this->location_error = $errors["location"][0];
                    }

                    if (array_key_exists("end_index", $errors)) {
                        $this->end_index_error = $errors["end_index"][0];
                    }
                } else {
                    $this->generalError = $errors;
                }
            } else {
                $this->refresh($response["message"]);
            }
        }
    }

    public function searching()
    {
        $result = collect($this->locations)->filter(function ($location) {
            return Str::contains(strtolower($location["house"]['name']), strtolower($this->search));
        })->all();

        if (count($result) == 0) {
            $result = collect($this->locations)->filter(function ($location) {
                return Str::contains(strtolower($location["room"]['number']), strtolower($this->search));
            })->all();

            if (count($result) == 0) {
                $result = collect($this->locations)->filter(function ($location) {
                    return Str::contains(strtolower($location["locataire"]['name']), strtolower($this->search));
                })->all();

                if (count($result) == 0) {
                    $result = collect($this->locations)->filter(function ($location) {
                        return Str::contains(strtolower($location["locataire"]['prenom']), strtolower($this->search));
                    })->all();

                    if (count($result) == 0) {
                        $result = collect($this->locations)->filter(function ($location) {
                            return Str::contains(strtolower($location["locataire"]['phone']), strtolower($this->search));
                        })->all();

                        if (count($result) == 0) {
                            $result = collect($this->locations)->filter(function ($location) {
                                return Str::contains(strtolower($location["locataire"]['email']), strtolower($this->search));
                            })->all();
                        }
                    }
                }
            }
        }

        $this->locations = $result;
        $this->generalSuccess = "Résultat de votre recherche";
    }


    public function render()
    {
        return view('livewire.eau-location');
    }
}
