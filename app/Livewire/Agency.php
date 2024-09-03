<?php

namespace App\Livewire;

use App\Models\Agency as ModelsAgency;
use App\Models\City;
use App\Models\Country;
use App\Models\House;
use App\Models\Image;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Agency extends Component
{
    use WithFileUploads;

    public $agencies = [];
    public $agencies_count = [];
    public $agenciesLinks = [];
    public $showPrestations = false;

    // 
    public $countries = [];
    public $cities = [];


    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    // ADD AGENCY DATAS
    public $name = "";
    public $ifu = "";
    public $rccm = "";
    public $country = "";
    public $city = "";
    public $email = "";
    public $phone = "";
    public $rccm_file;
    public $ifu_file;

    // TRAITEMENT DES ERREURS
    public $name_error = "";
    public $ifu_error = "";
    public $rccm_error = "";
    public $country_error = "";
    public $city_error = "";
    public $email_error = "";
    public $phone_error = "";
    public $rccm_file_error = "";
    public $ifu_file_error = "";

    public $search = '';

    public $generalError = "";
    public $generalSuccess = "";


    public $showCautions = false;
    public $cautions_link = "";

    public $display_caution_options = false;

    public $generate_caution_by_periode = false;
    public $generate_caution_by_house = false;

    public $first_date = "";
    public $last_date = "";

    public $currentHouseId = null;
    public $house = "";
    public $houses = [];

    // 
    public $show_form = false;

    function mount()
    {
        set_time_limit(0);
        // $this->BASE_URL = env("BASE_URL");
        // $this->token = session()->get("token");
        // $this->userId = session()->get("userId");

        // $this->headers = [
        //     "Authorization" => "Bearer " . $this->token,
        // ];

        // HOUSES
        $this->refreshHouses();

        // PAYS
        $this->refreshCountries();

        // CITIES
        $this->refreshCities();

        // AGENCIES
        $this->refreshAgencies();

        // PAYS
        $this->refreshCountries();

        // CITIES
        $this->refreshCities();
    }

    // HOUSES
    function refreshHouses()
    {
        $houses = House::all(); # = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/house/all")->json();
        $this->houses = $houses;
    }

    function displayCautionOptions()
    {
        if ($this->display_caution_options) {
            $this->display_caution_options = false;
            $this->showCautions = false;
            $this->showPrestations = false;
            $this->generate_caution_by_periode = false;
            $this->generate_caution_by_house = false;
        } else {
            $this->display_caution_options = true;
        }
    }

    function showGenerateCautionByPeriodeForm()
    {
        if ($this->generate_caution_by_periode) {
            $this->generate_caution_by_periode = false;
        } else {
            $this->generate_caution_by_periode = true;
        }
        $this->showCautions = false;
        $this->showPrestations = false;
        $this->generate_caution_by_house = false;
    }

    function ShowGenerateCautionByHouseForm()
    {
        if ($this->generate_caution_by_house) {
            $this->generate_caution_by_house = false;
        } else {
            $this->generate_caution_by_house = true;
        }
        $this->showCautions = false;
        $this->showPrestations = false;
        $this->generate_caution_by_periode = false;
    }

    function GenerateAllAgencyiesCaution()
    {
        set_time_limit(0);

        $agencyId = "admin";
        
        $this->showCautions = true;
        $this->showPrestations = false;
        $this->cautions_link = env("APP_URL") . "/$agencyId/caution_html"; ## $response["data"]["caution_html_url"];

        $this->generate_caution_by_periode = false;
    }


    function GenerateAgencyCaution($agencyId)
    {
        $this->showCautions = true;
        $this->showPrestations = false;
        $this->cautions_link = env("APP_URL") . "/$agencyId/caution_html";

        // FERMETURE DES AUTRES 
        $this->show_form = false;
    }


    function generateCautionByPeriode()
    {

        $action = "period";

        $formData = [
            "first_date" => $this->first_date,
            "last_date" => $this->last_date,
        ];

        ##__

        ###__
        Validator::make(
            $formData,
            [
                "first_date" => ["required", "date"],
                "last_date" => ["required", "date"],
            ],
            [
                "first_date.required" => "Ce Champ est réquis!",
                "last_date.required" => "Ce Champ est réquis!",

                "first_date.date" => "Ce Champ est une date!",
                "last_date.date" => "Ce Champ est une date!",
            ]
        )->validate();


        ###__

        $this->showCautions = true;
        $this->showPrestations = false;
        $this->cautions_link = env("APP_URL") . "/" . $formData['first_date'] . "/" . $formData['last_date'] . "/caution_html_by_period";
    }

    function generateCautionByHouse()
    {
        set_time_limit(0);
        // $response = Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/location/" . $this->house . "/generate_cautions_by_house")->json();

        $house = House::find($this->house);

        $this->showCautions = true;
        $this->showPrestations = false;
        $this->cautions_link = env("APP_URL") . "/$house->id/caution_html_by_house";
    }

    function refreshCountries()
    {
        $countries = Country::all(); ## Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/country/all")->json();
        $this->countries = $countries;
    }

    function refreshCities()
    {
        $cities = City::all(); ## Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/city/all")->json();
        $this->cities = $cities;
    }

    function refreshAgencies()
    {
        $agencies = ModelsAgency::all(); ## Http::withHeaders($this->headers)->get($this->BASE_URL . "immo/agency/all")->json();
        $this->agencies_count = count($agencies);
        $this->agencies = $agencies;
    }

    public function searching()
    {
        $data = [
            "search" => $this->search
        ];

        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/agency/search", $data)->json();
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

                $this->agencies = $response["data"];
            }
        }
    }

    function refresh($message)
    {
        $this->generalSuccess = $message;
        $this->show_form = false;

        // #### neutralisation des infos
        $this->country = "";
        $this->city = "";
        $this->name = "";
        $this->ifu = "";
        $this->rccm = "";
        $this->email = "";
        $this->phone = "";
        $this->rccm_file = "";
        $this->ifu_file = "";

        $this->name_error = "";
        $this->ifu_error = "";
        $this->rccm_error = "";
        $this->country_error = "";
        $this->city_error = "";
        $this->email_error = "";
        $this->phone_error = "";
        $this->rccm_file_error = "";
        $this->ifu_file_error = "";


        // AGENCIES
        $this->refreshAgencies();
    }

    function showForm()
    {
        if ($this->show_form) {
            $this->show_form = false;
        } else {
            $this->show_form = true;
        }
    }

    function addAgency(Request $request)
    {
        set_time_limit(0);

        // $this->validate(
        //     [
        //         'rccm_file' => 'required',
        //         'ifu_file' => 'required',
        //     ],
        //     [
        //         "rccm_file.required" => "Le fichier rccm est réquis!",
        //         "ifu_file.required" => "Le fichier ifu est réquis!",
        //     ]
        // );

        $rccm_file_imgPath_imgUrl = null;
        $ifu_file_imgPath_imgUrl = null;

        if ($this->rccm_file) {
            $rccm_file_imgPath = $this->rccm_file->store('uploads', "public");
            $rccm_file_imgPath_imgUrl = env("APP_URL") . "/storage/" . $rccm_file_imgPath;
        }

        if ($this->ifu_file) {
            $ifu_file_imgPath = $this->ifu_file->store('uploads', "public");
            $ifu_file_imgPath_imgUrl = env("APP_URL") . "/storage/" . $ifu_file_imgPath;
        }


        $data = [
            "owner" => $this->userId,
            "name" => $this->name,
            "ifu" => $this->ifu,
            "ifu_file" => $rccm_file_imgPath_imgUrl ? $rccm_file_imgPath_imgUrl : '',
            "rccm" => $this->ifu,
            "rccm_file" => $ifu_file_imgPath_imgUrl ? $ifu_file_imgPath_imgUrl : '',

            "phone" => $this->phone,
            "email" => $this->email,

            "country" => $this->country,
            "city" => $this->city,
        ];

        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "immo/agency/add", $data)->json();

        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez réessayez plus tard!";
        } else {
            if (!$response["status"]) {
                $errors = $response["erros"];
                if (gettype($errors) == "array") {
                    if (array_key_exists("name", $errors)) {
                        $this->name_error = $errors["name"][0];
                    }
                    if (array_key_exists("ifu", $errors)) {
                        $this->ifu_error = $errors["ifu"][0];
                    }
                    if (array_key_exists("ifu_file", $errors)) {
                        $this->ifu_file_error = $errors["ifu_file"][0];
                    }
                    if (array_key_exists("rccm", $errors)) {
                        $this->rccm_error = $errors["rccm"][0];
                    }
                    if (array_key_exists("rccm_file", $errors)) {
                        $this->rccm_file_error = $errors["rccm_file"][0];
                    }
                    if (array_key_exists("phone", $errors)) {
                        $this->phone_error = $errors["phone"][0];
                    }
                    if (array_key_exists("email", $errors)) {
                        $this->email_error = $errors["email"][0];
                    }
                    if (array_key_exists("country", $errors)) {
                        $this->country_error = $errors["country"][0];
                    }
                    if (array_key_exists("city", $errors)) {
                        $this->city_error = $errors["city"][0];
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
    }

    public function delete(int $id)
    {
        Agency::find($id)->delete();

        $this->refresh("Agence supprimée avec succès!");
    }

    public function render()
    {
        return view('livewire.agency');
    }
}
