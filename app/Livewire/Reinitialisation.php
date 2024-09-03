<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Reinitialisation extends Component
{
    public $demand = true;
    public $alert = false;

    // DEMANDE 
    public $email = "";
    public $email_error = "";

    // COMFIRM 
    public $pass_code = "";
    public $new_password = "";

    public $pass_code_error = "";
    public $new_password_error = "";

    public $generalError = "";
    public $generalSuccess = "";


    function ReinitialisationDemande()
    {
        set_time_limit(0);

        $BASE_URL = env("BASE_URL");

        $data = [
            "email" => $this->email,
        ];

        $response = Http::post($BASE_URL . "user/password/demand_reinitialize", $data)->json();
        if (!$response) {
            $this->alert = true;
            $this->generalError = "Une erreur est survenue! Veuillez réessayer à nouveau!";
        }else {
            if (!$response["status"]) {
                $errors = $response["erros"];
                if (gettype($errors) == "array") {
                    if (array_key_exists("email", $errors)) {
                        $this->email_error = $errors["email"][0];
                    }
                } else {
                    $this->alert = true;
                    $this->generalError = $errors;
                    $this->generalSuccess = "";
                }
            } else {
                $this->alert = true;
                $this->demand = false;
                $this->generalError = "";
                $this->generalSuccess = $response["message"];
            }
        }
    }


    function ReinitialisationComfirm()
    {
        set_time_limit(0);

        $BASE_URL = env("BASE_URL");

        $data = [
            "pass_code" => $this->pass_code,
            "new_password" => $this->new_password,
        ];

        $response = Http::post($BASE_URL . "user/password/reinitialize", $data)->json();

        if (!$response) {
            $this->alert = true;
            $this->generalError = "Une erreur est survenue! Veuillez réessayer à nouveau!";
        }else {
            if (!$response["status"]) {
                $errors = $response["erros"];
                if (gettype($errors) == "array") {
                    if (array_key_exists("pass_code", $errors)) {
                        $this->pass_code_error = $errors["pass_code"][0];
                    }
    
                    if (array_key_exists("new_password", $errors)) {
                        $this->new_password_error = $errors["new_password"][0];
                    }
                } else {
                    $this->generalError = $errors;
                    $this->generalSuccess = "";
                }
            } else {
                $this->alert = true;
                $this->demand = false;
                $this->generalError = "";
                $this->generalSuccess = $response["message"];
                // return redirect("/")->back()->with("success", $response["message"]);
            }
        }
    }

    public function render()
    {
        return view('livewire.reinitialisation');
    }
}
