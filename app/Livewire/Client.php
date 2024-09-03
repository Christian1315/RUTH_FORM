<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Client extends Component
{

    // ####

    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $hearders = [];

    // ###
    public $clients;

    public $search = '';

    public $generalError = "";
    public $generalSuccess = "";


    function __construct()
    {
        set_time_limit(0);

        $BASE_URL = env("BASE_URL");
        $token = session()->get("token");
        $userId = session()->get("userId");

        $hearders = [
            "Authorization" => "Bearer " . $token,
        ];

        $this->hearders = $hearders;
        $this->token = $token;
        $this->userId = $userId;
        $this->BASE_URL = $BASE_URL;


        // les locations de cette maison
        $response = Http::withHeaders($hearders)->get($BASE_URL . "immo/client/all")->json();

        if (!$response["status"]) {
            $this->clients = [];
        } else {
            $this->clients = $response["data"];
        }
    }

    public function searching()
    {
        set_time_limit(0);

        $data = [
            "search" => $this->search
        ];

        $response = Http::withHeaders($this->hearders)->post($this->BASE_URL . "immo/client/search", $data)->json();
        // dd($response);
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

                $this->clients = $response["data"];
            }
        }
    }

    public function render()
    {
        return view('livewire.client');
    }
}
