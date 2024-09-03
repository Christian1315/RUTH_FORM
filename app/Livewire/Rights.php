<?php

namespace App\Livewire;

use App\Models\Action;
use App\Models\Profil;
use App\Models\Rang;
use App\Models\Right;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;

class Rights extends Component
{
    use WithFileUploads;

    public $rights = [];
    public $rangs = [];
    public $actions = [];
    public $profils = [];

    public $users = [];


    public $BASE_URL = "";
    public $token = "";
    public $userId;

    public $headers = [];

    // LES DATAS
    public $rang;
    public $profil;
    public $action;
    public $description;

    // LES ERREURES
    public $rang_error = "";
    public $profil_error = "";
    public $action_error = "";
    public $description_error = "";

    // 
    public $showAddForm = false;
    public $showUserRoles = false;

    public $showRightForm = false;
    public $retrieveRightForm = false;

    public $currentActiveRigtId;
    public $currentActiveRight = [];

    public $user = "";
    public $user_error = "";


    public $search = '';

    public $generalError = "";
    public $generalSuccess = "";

    // USERS
    function refreshUsers()
    {
        $users = User::all();
        $this->users = $users;
    }

    // RIGHTS
    function refreshRights($message)
    {
        $rights = Right::all();
        $this->rights = $rights;

        $this->rang;
        $this->profil;
        $this->action;
        $this->description;

        // LES ERREURES
        $this->rang_error = "";
        $this->profil_error = "";
        $this->action_error = "";
        $this->description_error = "";
    }

    // PROFILS
    function refreshProfils()
    {
        $profils = Profil::all();
        $this->profils = $profils;
    }

    // RANG
    function refreshRangs()
    {
        $rangs = Rang::all();
        $this->rangs = $rangs;
    }

    // ACTION
    function refreshActions()
    {
        $actions = Action::all();
        $this->actions = $actions;
    }


    function mount()
    {
        // USERS
        $this->refreshUsers();

        // RIGHTS
        $this->refreshRights("");

        // RANGS
        $this->refreshRangs();

        // ACTIONS
        $this->refreshActions();

        // PROFILS
        $this->refreshProfils();

        // ROLES
        $roles = Role::all();
        // $this->allRoles = $roles["data"];
    }

    function RetrieveRight()
    {
        set_time_limit(0);

        $data = [
            "right_id" => $this->currentActiveRigtId,
            "user_id" => $this->user,
        ];
        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "user/desattach-user", $data)->json();
        // dd($response);
        if ($response) {
            if (!$response["status"]) {
                $errors = $response["erros"];
                $this->generalError = $errors;
            } else {
                $this->refreshRights($response["message"]);
            }
        } else {
            $this->refreshRights("Une erreure est survenue! Veuillez bien réessayer!");
        }
    }

    public function searching()
    {
        set_time_limit(0);

        $data = [
            "search" => $this->search
        ];

        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "right/search", $data)->json();

        if (!$response) {
            $this->generalError = "Une erreure est survenue! Veuillez réessayez plus tard!";
        } else {
            if (!$response["status"]) {
                $this->generalError = $response["erros"];
                $this->generalSuccess = "";
            } else {
                $this->rights = $response["data"];
                $this->refreshRights($response["message"]);
            }
        }
    }

    function addRight()
    {
        set_time_limit(0);

        $data = [
            "rang" => $this->rang,
            "profil" => $this->profil,
            "action" => $this->action,
            "description" => $this->description,
        ];

        $response = Http::withHeaders($this->headers)->post($this->BASE_URL . "right/add", $data)->json();
        // dd($response);
        if ($response) {
            if (!$response["status"]) {
                $errors = $response["erros"];
                if (gettype($errors) == "array") {
                    if (array_key_exists("rang", $errors)) {
                        $this->rang_error = $errors["rang"][0];
                    }
                    if (array_key_exists("profil", $errors)) {
                        $this->profil_error = $errors["profil"][0];
                    }
                    if (array_key_exists("action", $errors)) {
                        $this->action_error = $errors["action"][0];
                    }
                    if (array_key_exists("description", $errors)) {
                        $this->description_error = $errors["description"][0];
                    }
                } else {
                    $this->generalError = "Une erreure est survenue";
                }
            } else {

                $this->refreshRights($response["message"]);
            }
        } else {
            $this->refreshRights("Une erreure est survenue! Veuillez bien réessayer!");
        }
    }

    public function render()
    {
        return view('livewire.rights');
    }
}
