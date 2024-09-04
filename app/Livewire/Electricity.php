<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use Livewire\Component;

class Electricity extends Component
{
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
    public $supervisors;

    public $locators = [];
    public $locator = [];
    public $search = "";

    public $filtre_by_house = false;
    public $filtre_by_locator = false;
    public $display_filtre_options = false;

    public $forLocation = false;


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

    ###__LOCATIONS
    function refreshThisAgencyLocations()
    {

        $locations = $this->current_agency->_Locations;
        ##___
        $agency_locations = [];

        foreach ($locations as $location) {
            if ($location->Room->electricity) {
                if (count($location->ElectricityFactures) != 0) {
                    $latest_facture = $location->ElectricityFactures[0]; ##__dernier facture de cette location

                    ##___Cette variable determine si la derniere facture est pour un arrêt de state

                    $is_latest_facture_a_state_facture = false;
                    if ($latest_facture->state_facture) {
                        $is_latest_facture_a_state_facture = true; ###__la derniere facture est pour un arrêt de state
                    }

                    ###___l'index de fin de cette location revient à l'index de fin de sa dernière facture
                    $location["end_index"] = $latest_facture->end_index;

                    ###___le montant actuel à payer pour cette location revient au montant de sa dernière facture
                    ###__quand la dernière facture est payée, le current_amount devient 0 
                    $location["current_amount"] = $latest_facture["paid"] ? 0 : $latest_facture["amount"];

                    #####______montant payé
                    $paid_factures_array = [];

                    ###__determinons les arrièrees
                    $unpaid_factures_array = [];
                    $nbr_unpaid_factures_array = [];
                    $total_factures_to_pay_array = [];

                    foreach ($location->ElectricityFactures as $facture) {

                        ###__on recupere toutes les factures sauf la dernière(correspondante à l'arrêt d'état)
                        if ($facture["id"] != $latest_facture["id"]) {
                            ###__on recupere les factures non payés
                            if (!$facture["paid"]) {
                                if (!$facture->state_facture) { ##sauf la dernière(correspondante à l'arrêt d'état)
                                    array_push($unpaid_factures_array, $facture["amount"]);
                                    array_push($nbr_unpaid_factures_array, $facture);
                                }
                            }
                        }

                        ###__on recupere les factures  payées
                        if ($facture->paid) {
                            array_push($paid_factures_array, $facture["amount"]);
                        }
                        ###____
                        array_push($total_factures_to_pay_array, $facture["amount"]);
                    }

                    ###__Nbr d'arrieres
                    $location["nbr_un_paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : count($nbr_unpaid_factures_array);
                    ###__Montant d'arrieres
                    $location["un_paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : array_sum($unpaid_factures_array);

                    ###__Montant payés
                    $location["paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : array_sum($paid_factures_array);

                    ##__total amount to paid
                    $location["total_un_paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : array_sum($total_factures_to_pay_array);

                    ###__Montant dû
                    $location["rest_facture_amount"] = $location["total_un_paid_facture_amount"] - $location["paid_facture_amount"];
                } else {
                    ###___l'index de fin de cette location revient à l'index de fin de sa dernière facture
                    $location["end_index"] = 0;

                    ###___le montant actuel à payer pour cette location revient montant de sa dernière facture
                    ###__quand la dernière facture est payée, le current_amount devient 0 
                    $location["current_amount"] =  0;

                    ###__Nbr d'arrieres
                    $location["nbr_un_paid_facture_amount"] = 0;

                    ###__Montant d'arrieres
                    $location["un_paid_facture_amount"] = 0;

                    ###___
                    $location["water_factures"] = [];

                    ###__Montant payés
                    $location["paid_facture_amount"] = 0;

                    ##__total amount to paid
                    $location["total_un_paid_facture_amount"] = 0;

                    ###__Montant dû
                    $location["rest_facture_amount"] = 0;
                }
                // $location["factures"] = $location_factures;
                array_push($agency_locations, $location);
            }
        }

        ####___
        $this->locations = $agency_locations;
    }


    function refreshThisAgencyLocators()
    {
        ###___LOCATORS
        $agency_locators = $this->current_agency->_Locataires;
        ##___
        $this->locators = $agency_locators;
    }


    ###___HOUSES
    function refreshThisAgencyHouses()
    {
        $this->houses = $this->current_agency->_Houses;
    }

    ###__REFRESH CURRENT LOCATION FACTURES
    function refreshsCurrentLocationFactures($locationId)
    {
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/location/$locationId/retrieve")->json();
        if (!$response["status"]) {
            $this->currentLocationFactures = [];
        } else {
            ##___
            $location = $response["data"];
            $this->currentLocationFactures = $location["electricity_factures"];
            $this->show_factures = true;
        }

        $this->showHouseFom = false;
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

        // dd($this->house);
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
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/facture/electricity_facture/$factureId/payement")->json();

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

    function _Show($forLocation)
    {
        if ($forLocation == 0) {
            $this->forLocation = false;
        } else {
            $this->forLocation = true;
            // $this->showForm();
        }

        $this->ShowHouseForm();
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
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/facture/electricity_facture/house_state/house/" . $this->houseId . "/all")->json();

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

        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/facture/electricity_facture/house_state/house/" . $this->state . "/electricity_imprime")->json();

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
            "house" => (int) $this->house
        ];

        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/facture/electricity_facture/house_state/stop", $data)->json();
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
        $this->refreshSupervisors();
        $this->refreshThisAgencyLocators();
    }

    function GenerateFacture()
    {
        $data = [
            "location" => $this->location,
            "end_index" => $this->end_index,
        ];


        ###__
        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/facture/electricity_facture/generate", $data)->json();

        // dd($response);
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
        return view('livewire.electricity');
    }
};
