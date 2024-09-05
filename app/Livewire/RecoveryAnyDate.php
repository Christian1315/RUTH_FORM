<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;

class RecoveryAnyDate extends Component
{
    public $agency;

    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $hearders = [];

    public $locators = [];
    public $date;
    public $date_error;

    public $generalError = "";
    public $generalSuccess = "";

    function refresh($message)
    {
        $this->generalSuccess = $message;
        $this->generalError = "";
        $this->date = "";
        $this->date_error = "";
    }

    function mount($agency)
    {
        set_time_limit(0);

        $this->agency = $agency;
       
        $this->locators = [];
    }

    function filtreByDate()
    {
        $data = [
            "date" => $this->date,
        ];

        $this->date = $this->date;

        $agencyId = $this->agency["id"];
        $response = Http::withHeaders($this->hearders)->post($this->BASE_URL . "immo/paiement/$agencyId/filtre_at_any_date", $data)->json();

        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez réessayez plus tard!";
        } else {
            if (!$response["status"]) {
                $errors = $response["erros"];
                if (gettype($errors) == "array") {
                    if (array_key_exists("date", $errors)) {
                        $this->date_error = $errors["date"][0];
                    }
                }
            } else {
                $this->refresh($response["message"]);
                $this->locators = $response["data"];
            }
        }
    }

    public function render()
    {
        return view('livewire.recovery-any-date');
    }
}
