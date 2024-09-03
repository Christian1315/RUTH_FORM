<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Graph extends Component
{
    public $proprietors_count = 0;
    public $houses_count = 0;
    public $locators_count = 0;
    public $locations_count = 0;
    public $rooms_count = 0;
    public $paiement_count = 0;
    public $factures_count = 0;
    public $accountSold_count = 0;
    public $initiation_count = 0;

    function __construct()
    {
        // $BASE_URL = env("BASE_URL");
        // $token = session()->get("token");
        // $hearders = [
        //     "Authorization" => "Bearer " . $token,
        // ];

        // // PROPRETAIRES
        // $proprietors = Http::withHeaders($hearders)->get($BASE_URL . "immo/proprietor/all")->json();
        // $this->proprietors_count = count($proprietors["data"]);

        // // MAISONS
        // $houses = Http::withHeaders($hearders)->get($BASE_URL . "immo/house/all")->json();
        // $this->houses_count = count($houses["data"]);

        // // LOCATAIRES
        // $locators = Http::withHeaders($hearders)->get($BASE_URL . "immo/locataire/all")->json();
        // $this->locators_count = count($locators["data"]);

        // // LOCATIONS
        // $locations = Http::withHeaders($hearders)->get($BASE_URL . "immo/location/all")->json();
        // $this->locations_count = count($locations["data"]);

        // // ROOMS
        // $rooms = Http::withHeaders($hearders)->get($BASE_URL . "immo/room/all")->json();
        // $this->rooms_count = count($rooms["data"]);

        // // PAIEMENTS
        // $paiements = Http::withHeaders($hearders)->get($BASE_URL . "immo/paiement/all")->json();
        // $this->paiement_count = count($paiements["data"]);

        // // FACTURES
        // $factures = Http::withHeaders($hearders)->get($BASE_URL . "immo/facture/all")->json();
        // $this->factures_count = count($factures["data"]);

        // // COMPTES & SOLDES
        // $accountSolds = Http::withHeaders($hearders)->get($BASE_URL . "immo/account/all")->json();
        // $this->accountSold_count = count($accountSolds["data"]);

        // // INITIATIONS
        // $initiations = Http::withHeaders($hearders)->get($BASE_URL . "immo/payement_initiation/all")->json();
        // $this->initiation_count = count($initiations["data"]);
    }

    public function render()
    {
        return view('livewire.graph');
    }
}
