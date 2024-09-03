<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;

class Initiation extends Component
{
    use WithFileUploads;

    public $initiations = [];
    public $initiations_count = [];

    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $hearders = [];

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
    public $generalSuccess = "";
    // 
    public $show_form = false;
    public $show_solds = false;

    public $currentActiveinitiation;

    public $click_count = 2;

    function __construct()
    {
        set_time_limit(0);

        $this->BASE_URL = env("BASE_URL");
        $this->token = session()->get("token");
        $this->userId = session()->get("userId");

        $this->hearders = [
            "Authorization" => "Bearer " . $this->token,
        ];

        // initiation
        $initiations = Http::withHeaders($this->hearders)->get($this->BASE_URL . "immo/payement_initiation/all")->json();
        if (!$initiations["status"]) {
            $this->initiations = [];
            $this->initiations_count = 0;
        } else {
            $this->initiations = $initiations["data"];
            $this->initiations_count = count($initiations["data"]);
        }

        // PROPRIETAIRES
        $proprietors = Http::withHeaders($this->hearders)->get($this->BASE_URL . "immo/proprietor/all")->json();
        if (!$proprietors["status"]) {
            $this->proprietors = [];
        } else {
            $this->proprietors = $proprietors["data"];
        }
    }

    function refresh($message)
    {
        set_time_limit(0);

        $this->generalSuccess = $message;
        $this->show_form = false;

        $initiations = Http::withHeaders($this->hearders)->get($this->BASE_URL . "immo/payement_initiation/all")->json();
        if (!$initiations["status"]) {
            $this->initiations = [];
            $this->initiations_count = 0;
        } else {
            $this->initiations = $initiations["data"];
            $this->initiations_count = count($initiations["data"]);
        }

        // PROPRIETAIRES
        $proprietors = Http::withHeaders($this->hearders)->get($this->BASE_URL . "immo/proprietor/all")->json();
        if (!$proprietors["status"]) {
            $this->proprietors = [];
        } else {
            $this->proprietors = $proprietors["data"];
        }

        $this->show_form = false;

        $this->sold_error = "";
        $this->proprietor_error = "";
    }

    function validate_Initiation($id)
    {
        set_time_limit(0);

        $response = Http::withHeaders($this->hearders)->get($this->BASE_URL . "immo/payement_initiation/$id/valide")->json();
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
            "proprietor" => $this->proprietor,
            "amount" => $this->sold,
        ];

        $response = Http::withHeaders($this->hearders)->post($this->BASE_URL . "immo/payement_initiation/initiateToProprio", $data)->json();
        // dd($response);
        if (!$response["status"]) {
            $errors = $response["erros"];
            if (gettype($errors) == "array") {
                if (array_key_exists("amount", $errors)) {
                    $this->sold_error = $errors["amount"][0];
                }

                if (array_key_exists("proprietor", $errors)) {
                    $this->proprietor_error = $errors["proprietor"][0];
                }
            } else {
                $message = $errors;
                $this->refresh($message);
            }
        } else {
            $successMsg = $response["message"];
            // return redirect("/initiation")->with("success", $successMsg);
            $this->refresh($successMsg);
        }
    }

    public function showSolds(int $id)
    {
        set_time_limit(0);

        $initiation = Http::withHeaders($this->hearders)->get($this->BASE_URL . "immo/initiation/{$id}/retrieve")->json();
        if (!$initiation["status"]) {
            $this->initiation_solds = [];
            $this->current_initiation = [];
        } else {
            $this->initiation_solds = $initiation["data"]["solds"];
            $this->current_initiation = $initiation["data"];
        }

        if ($this->show_solds) {
            $this->current_initiation = [];
            $this->show_solds = false;
            $this->show_form = false;
        } else {
            $this->show_solds = true;
            $this->current_initiation = $initiation["data"];
            $this->show_form = false;
        }
    }

    public function render()
    {
        return view('livewire.initiation');
    }
}
