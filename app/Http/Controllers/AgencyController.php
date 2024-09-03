<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyAccount;
use App\Models\AgencyAccountSold;
use App\Models\City;
use App\Models\Country;
use App\Models\House;
use App\Models\ImmoAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class AgencyController extends Controller
{
    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    #########========== VALIDATION  =========#########
    // ======== AGENCY VALIDATION =======//
    static function Agency_rules(): array
    {
        return [
            'name' => ['required'],
            'ifu' => ['required'],
            'rccm' => ['required'],
            'phone' => ['required', "numeric"],

            'email' => ['required', 'email', Rule::unique('users')],
            'country' => ['required', "integer"],
            'city' => ['required'],
        ];
    }

    static function Agency_messages(): array
    {
        return [
            'name.required' => "Le nom de l'agence est réquis!",

            'ifu.required' => "L'ifu nom de l'agence est réquis!",

            'rccm.required' => "Le rccm de l'agence est réquis!",

            'phone.required' => "Le phone de l'agence est réquis!",
            'phone.numeric' => "Le phone de l'agence doit être de type numérique!",

            'email.required' => "Le mail de l'agence est réquis!",
            'email.email' => "Le mail de l'agence doit être de type mail!",
            'email.unique' => 'Un compte existe déjà au nom de ce mail!',

            'country.required' => "Le pays de l'agence est réquis!",
            'country.integer' => "Le champ *country* de l'agence doit être de type entier!",

            'city.required' => "La commune de l'agence est réquise!",
            'city.integer' => "Le champ *city* de l'agence doit être de type entier!",
        ];
    }


    ##########======= GESTION DES METHODES ==============#########
    function AddAgency(Request $request)
    {
        $rules = self::Agency_rules();
        $messages = self::Agency_messages();

        Validator::make($request->all(), $rules, $messages)->validate();

        #ENREGISTREMENT DANS LA DB VIA **_createAgency** DE LA CLASS BASE_HELPER HERITEE PAR AGENCY_HELPER
        $user = request()->user();
        $formData = $request->all();

        ###___VOYONS D'ABORD SI CETTE AGENCE EXISTE DEJA
        $agency = Agency::where(["name" => $formData["name"]])->first();

        if ($agency) {
            alert()->error('Echec', "Cette agence existe déjà!");
            return redirect()->back()->withInput();
        }

        ###___
        $country = Country::find($formData["country"]);
        if (!$country) {
            alert()->error('Echec', "Ce pays n'existe pas!");
            return redirect()->back()->withInput();
        }

        $type = "AGY";

        ##GESTION DES FICHIERS
        if ($request->file('ifu_file')) {
            $ifu_file = $request->file('ifu_file');
            $ifu_name = $ifu_file->getClientOriginalName();
            $ifu_file->move("pieces", $ifu_name);
            $formData["ifu_file"] = asset("pieces/" . $ifu_name);
        }

        if ($request->file('rccm_file')) {
            $rccm_file = $request->file('rccm_file');
            $rccm_name = $rccm_file->getClientOriginalName();
            $rccm_file->move("pieces", $rccm_name);
            $formData["rccm_file"] = asset("pieces/" . $rccm_name);
        }

        ####___

        $formData["number"] = Add_Number($user, $type); ##Add_Number est un helper qui genère le **number** 
        if (!$request->get("owner")) {
            $formData["owner"] = $user->id;
        } else {
            $formData["owner"] = $request->get("owner");
        }

        #ENREGISTREMENT DE L'AGENCE DANS LA DB
        $created_agency = Agency::create($formData);

        // 
        #ENREGISTREMENT DE L'AGENCE ENTANT QUE USER DANS LA DB
        $userData = [
            "user_agency" => $created_agency->id,
            "owner" => $formData["owner"],
            "name" => $created_agency->name,
            "username" => $created_agency->number,
            "password" => $created_agency->number,
            "phone" => $created_agency->phone,
            "email" => $created_agency->email,

            "rang_id" => 2,
            "profil_id" => 5,
        ];

        ###__
        $agency_user = User::create($userData);


        ###___GENERATION DES COMPTES DE CETTE AGENCE
        $all_accounts = ImmoAccount::all();
        foreach ($all_accounts as $account) {
            AgencyAccount::create(
                [
                    "agency" => $created_agency->id,
                    "account" => $account->id,
                ]
            );
        }
        ###___

        try {
            Send_Notification(
                $agency_user,
                "Création de compte sur Perfect ERP",
                "Votre compte agence a été crée avec succès sur Perfect ERP",
            );
        } catch (\Throwable $th) {
            //throw $th;
        }

        alert()->success('Succès', "Agence ajoutée avec succès!!");
        return redirect()->back()->withInput();
    }


    ######____CREDITATION D'UN COMPTE
    function _CreditateAccount(Request $request, $out_call = false)
    {

        $user = request()->user();

        ##__quand c'est un appel externe
        $formData = $request->all();

        ##__
        $agency_account = AgencyAccount::with(["_Account"])->where(["agency" => $formData["agency"]])->find($formData["agency_account"]);
        if (!$agency_account) {
            alert()->error("Echec", "Ce compte d'agence n'existe pas! Vous ne pouvez pas le créditer!");
            return back()->withInput();
        }

        $account = $agency_account->_Account;

        $formData["sold_added"] = $formData["sold"];

        ###___VERIFIONS LE SOLD ACTUEL DU COMPTE ET VOYONS SI ça DEPPASE OU PAS LE PLAFOND
        $agencyAccountSold = AgencyAccountSold::where(["agency_account" => $formData["agency_account"], "visible" => 1])->first();

        if ($agencyAccountSold) { ##__Si ce compte dispose déjà d'un sold
            $formData["old_sold"] = $agencyAccountSold->sold;

            ##__voyons si le sold atteint déjà le plafond de ce compte
            if ($agencyAccountSold->sold >= $account->plafond_max) {
                alert()->error("Echec", "Le sold de ce compte (" . $account->name . ") a déjà atteint son plafond! Vous ne pouvez plus le créditer");
                return back()->withInput();
            } else {
                # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
                # ça depasserait le plafond maximum du compte

                if (($agencyAccountSold->sold + $formData["sold"]) > $account->plafond_max) {
                    alert()->error("Echec", "L'ajout de ce montant au sold de ce compte (" . $account->name . ") dépasserait son plafond! Veuillez diminuer le montant");
                    return back()->withInput();
                }
            }

            ###__creditation proprement dite du compte
            #__Deconsiderons l'ancien sold
            $agencyAccountSold->visible = 0;
            $agencyAccountSold->delete_at = now();
            $agencyAccountSold->save();

            #__Construisons un nouveau sold(en se basant sur les datas de l'ancien sold)
            $formData["agency_account"] = $agencyAccountSold->agency_account; ##__ça revient à l'ancien compte
            $formData["sold"] = $agencyAccountSold->sold + $formData["sold"];

            $agencyAccountSold = AgencyAccountSold::create($formData);
        } else {
            # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
            # ça depasserait le plafond maximum du compte
            $formData["old_sold"] = 0;

            if ($formData["sold"] > $account->plafond_max) {
                alert()->error("Echec", "L'ajout de ce montant au sold de ce compte (" . $account->name . ") dépasserait son plafond! Veuillez diminuer le montant");
                return back()->withInput();
            }

            # on le crée
            $agencyAccountSold = AgencyAccountSold::create($formData);
        }

        ####____
        alert()->success("Succès", "Le compte (" . $account->name . " (" . $account->description . ") " . ") a été crédité  avec succès!!");
        return back()->withInput();
    }

    ######____DECREDITATION DE COMPTE
    function _DeCreditateAccount(Request $request)
    {
        $user = request()->user();
        $formData = $request->all();

        ##__
        $agency_account = AgencyAccount::find($formData["agency_account"]);
        if (!$agency_account) {
            alert()->error('Echèc', "L'agence ne dispose pas de ce compte !");
            return redirect()->back()->withInput();
        }

        $formData["sold_retrieved"] = $formData["sold"];

        $account = $agency_account->_Account;

        ###___VERIFIONS LE SOLD ACTUEL DU COMPTE ET VOYONS SI ça DEPPASE OU PAS LE PLAFOND
        $agencyAccountSold = AgencyAccountSold::where(["agency_account" => $formData["agency_account"], "visible" => 1])->first();


        ###___
        if (!$agencyAccountSold) {
            alert()->error('Echèc', "Désolé! Ce compte ne dispose pas de solde!");
            return redirect()->back()->withInput();
        }

        $formData["old_sold"] = $agencyAccountSold->sold;

        # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
        # ça descendrait en bas de 0
        if (($agencyAccountSold->sold - $formData["sold"]) < 0) {
            alert()->error('Echèc', "La décreditation de ce montant au sold de ce compte (" . $account->name . ") descendrait en dessous de 0!");
            return redirect()->back()->withInput();
        }

        ##__Quant il s'agit de la caisse CDR
        if ($account->id == 3) {
            if (!$request->get("house")) {
                alert()->error('Echèc', "Pour le compte CDR, la maison est réquise!");
                return redirect()->back()->withInput();
            }

            ###___
            $house = House::find($request->get("house"));
            if (!$house) {
                alert()->error('Echèc', "Désolé! Cette maison n'existe pas!");
                return redirect()->back()->withInput();
            }
        }else {
            $formData["house"] = null;
        }

        #__Construisons un nouveau sold(en se basant sur les datas de l'ancien sold)
        $formData["agency_account"] = $agencyAccountSold->agency_account; ##__ça revient à l'ancien compte
        $formData["sold_retrieved"] = $formData["sold"];
        $formData["sold"] = $agencyAccountSold->sold - $formData["sold"];

        ###__creditation proprement dite du compte
        #__Deconsiderons l'ancien sold
        $agencyAccountSold->visible = 0;
        $agencyAccountSold->delete_at = now();
        $agencyAccountSold->save();

        $agencyAccountSold = AgencyAccountSold::create($formData);

        ###___
        alert()->success('Succès', "Le compte (" . $account->name . " (" . $account->description . ") " . ") a été décrédité  avec succès!!");
        return redirect()->back()->withInput();
    }






    function DeleteAgency(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "DELETE") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS AGENCY_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->AgencyDelete($id);
    }

    function SearchAgency(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->search($request);
    }

    function _AddAgencyPaiement(Request $request, $agencyId)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #VALIDATION DES DATAs DEPUIS LA CLASS BASE_HELPER HERITEE PAR 
        $validator = $this->Paiement_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR 
            return $this->sendError($validator->errors(), 404);
        }

        #ENREGISTREMENT DANS LA DB VIA **addPaiement** DE LA CLASS 
        return $this->addPaiement($request, $agencyId);
    }

    ###____RECUPERATION DE TOUT LES MOUVEMENTS D'UN COMPTE AGENCE
    function _RetrieveAgencyAccountMouvements(Request $request, $agencyAccount)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->retrieveAgenCyAccountMouvements($agencyAccount);
    }

    ###___BILAN DE L'AGENCE
    function AgencyBilan(Request $request, $agencyId, $supervisor, $action)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_agencyBilan($agencyId, $supervisor, $action);
    }

    ###___FACTURES DE L'AGENCE
    function _AgencyFactures(Request $request, $agencyId, $supervisor, $action)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->agencyFactures($agencyId, $supervisor, $action);
    }

    ###___SUPERVISEURS DE L'AGENCE
    function GetAgencySupervisors(Request $request, $agencyId)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_getAllSupervisors($agencyId);
    }
}
