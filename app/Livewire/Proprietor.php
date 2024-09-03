<?php

namespace App\Livewire;

use App\Models\Agency;
use App\Models\CardType;
use App\Models\City;
use App\Models\Country;
use App\Models\Image;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;

class Proprietor extends Component
{
    use WithFileUploads;
    public $current_agency;

    public $proprietors = [];
    public $proprietors_count = [];
    public $proprietorsLinks = [];

    // 
    public $countries = [];
    public $cities = [];
    public $card_types = [];


    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    public $proprietor_houses = [];
    public $current_proprietor = [];

    // ADD PROPRIO DATAS
    public $agency = "";
    public $firstname = "";
    public $lastname = "";
    public $phone = "";
    public $email = "";
    public $sexe = "";
    public $piece_number = "";
    public $mandate_contrat;
    public $adresse = "";
    public $country = "";
    public $city = "";
    public $card_type = "";
    public $comments = "";

    // TRAITEMENT DES ERREURS
    public $agency_error = "";
    public $firstname_error = "";
    public $lastname_error = "";
    public $phone_error = "";
    public $email_error = "";
    public $sexe_error = "";
    public $piece_number_error = "";
    public $mandate_contrat_error = "";
    public $adresse_error = "";
    public $country_error = "";
    public $city_error = "";
    public $card_type_error = "";
    public $comments_error = "";

    public $search = '';

    public $generalError = "";
    public $generalSuccess = "";
    // 
    public $show_form = false;
    public $click_count = 2;

    function refreshThisAgencyProprietors()
    {
        ###___PROPRIETORS
        $agency = Agency::findOrFail($this->current_agency['id']);

        $this->proprietors = $agency->_Proprietors;
        $this->proprietors_count = count($agency->_Proprietors);
    }

    public function mount($agency)
    {
        $this->current_agency = $agency;

        ###___PROPRIETORS
        $this->refreshThisAgencyProprietors();

        // PAYS
        $countries = Country::all();
        $this->countries = $countries;

        // CITIES
        $cities = City::all();
        $this->cities = $cities;

        // CARD TYPES
        $card_types = CardType::all();
        $this->card_types = $card_types;
    }



    function addProprio(Request $request)
    {

        $mandate_contrat_imgPath = $this->mandate_contrat->store('uploads', "public");
        $mandate_contrat_imgPath_imgUrl = env("APP_URL") . "/storage/" . $mandate_contrat_imgPath;

        ###___

        $data = [
            "owner" => $this->userId,
            "agency" => $this->current_agency["id"],
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "phone" => $this->phone,
            "email" => $this->email,
            "sexe" => $this->sexe,
            "piece_number" => $this->piece_number,
            "mandate_contrat" => $mandate_contrat_imgPath_imgUrl,
            "adresse" => $this->adresse,
            "country" => $this->country,
            "city" => $this->city,

            "card_type" => $this->card_type,
            "comments" => $this->comments,
        ];


        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/proprietor/add", $data)->json();

        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez réessayez plus tard!";
        } else {
            if (!$response["status"]) {
                $errors = $response["erros"];
                if (gettype($errors) == "array") {
                    if (array_key_exists("agency", $errors)) {
                        $this->agency_error = $errors["agency"][0];
                    }
                    if (array_key_exists("firstname", $errors)) {
                        $this->firstname_error = $errors["firstname"][0];
                    }
                    if (array_key_exists("piece_number", $errors)) {
                        $this->piece_number_error = $errors["piece_number"][0];
                    }
                    if (array_key_exists("lastname", $errors)) {
                        $this->lastname_error = $errors["lastname"][0];
                    }
                    if (array_key_exists("phone", $errors)) {
                        $this->phone_error = $errors["phone"][0];
                    }
                    if (array_key_exists("email", $errors)) {
                        $this->email_error = $errors["email"][0];
                    }
                    if (array_key_exists("sexe", $errors)) {
                        $this->sexe_error = $errors["sexe"][0];
                    }
                    if (array_key_exists("piece_number", $errors)) {
                        $this->piece_number = $errors["piece_number"][0];
                    }
                    if (array_key_exists("mandate_contrat", $errors)) {
                        $this->mandate_contrat_error = $errors["mandate_contrat"][0];
                    }
                    if (array_key_exists("adresse", $errors)) {
                        $this->adresse_error = $errors["adresse"][0];
                    }
                    if (array_key_exists("country", $errors)) {
                        $this->country_error = $errors["country"][0];
                    }
                    if (array_key_exists("city", $errors)) {
                        $this->city_error = $errors["city"][0];
                    }
                    if (array_key_exists("card_type", $errors)) {
                        $this->card_type_error = $errors["card_type"][0];
                    }
                    if (array_key_exists("comments", $errors)) {
                        $this->comments_error = $errors["comments"][0];
                    }
                } else {
                    $this->generalError = $errors;
                }
            } else {
                $this->refresh($response["message"]);
            }
        }
    }

    public function retrieve(int $id)
    {
        set_time_limit(0);

        $proprietor = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/proprietor/{$id}/retrieve")->json();
        if (!$proprietor["status"]) {
            $this->proprietor_houses = [];
            $this->current_proprietor = [];
        } else {
            $this->proprietor_houses = $proprietor["data"]["houses"];
            $this->current_proprietor = $proprietor["data"];
        }
        $this->show_form = false;
        // dd($this->proprietor_houses);
    }

    public function delete(int $id)
    {
        set_time_limit(0);

        $response = Http::withHeaders($this->headers)->delete($this->BASE_URL . "immo/proprietor/{$id}/delete")->json();
        if (!$response["status"]) {
            $this->generalError = $response["erros"];
        }
        $this->refresh($response["message"]);
    }

    function Update(int $id)
    {
        set_time_limit(0);
        ###___RETRIEVE DU PROPRIETAIRE
        $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/proprietor/$id/retrieve")->json();

        $this->current_proprietor = $response["data"];

        $data = [
            "firstname" => $this->firstname ? $this->firstname : $this->current_proprietor["firstname"],
            "lastname" => $this->lastname ? $this->lastname : $this->current_proprietor["lastname"],
            "phone" => $this->phone ? $this->phone : $this->current_proprietor["phone"],
            "email" => $this->email ? $this->email : $this->current_proprietor["email"],
            "adresse" => $this->adresse ? $this->firstname : $this->current_proprietor["adresse"],
        ];


        ########_________
        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/proprietor/{$id}/update", $data)->json();

        if ($response) {
            if (!$response["status"]) {
                $this->refresh($response["erros"]);
            } else {
                $this->refresh($response["message"]);
            }
        } else {
            $this->generalError = "Une erreure est survenue! Veuillez bien réessayer!";
        }
    }

    public function render()
    {
        return view('livewire.proprietor');
    }
}
