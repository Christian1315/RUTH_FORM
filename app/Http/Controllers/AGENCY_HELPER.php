<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyAccount;
use App\Models\AgencyAccountSold;
use App\Models\City;
use App\Models\Country;
use App\Models\Facture;
use App\Models\House;
use App\Models\ImmoAccount;
use App\Models\Locataire;
use App\Models\Location;
use App\Models\PaiementType;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AGENCY_HELPER extends BASE_HELPER
{
    ##======== AGENCY VALIDATION =======##
    static function Agency_rules(): array
    {
        return [
            'name' => ['required'],
            'ifu' => ['required'],
            'rccm' => ['required'],
            'phone' => ['required', "numeric"],

            'email' => ['required', 'email', Rule::unique('users')],
            'country' => ['required', "integer"],
            'city' => ['required', "integer"],
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

    static function Agency_Validator($formDatas)
    {
        $rules = self::Agency_rules();
        $messages = self::Agency_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== MANAGE_ACCOUNT VALIDATION =======##
    static function manage_account_rules(): array
    {
        return [
            'agency' => ['required', "integer"],
            'agency_account' => ['required', "integer"],
            // 'account' => ['required', "integer"],
            'sold' => ['required', "integer"],
            'description' => ['required'],
        ];
    }

    static function manage_account_messages(): array
    {
        return [
            'agency.required' => "L'agence est réquise!",
            'agency.integer' => 'Ce champ doit être de type entier',

            'agency_account.required' => "Le compte est réquis!",
            'agency_account.integer' => 'Ce champ doit être de type entier',

            'sold.required' => "Le montant est réquis!",
            'sold.integer' => 'Ce champ doit être de type entier',

            'description.required' => 'Veuillez bien préciser une pétite description!',
        ];
    }

    static function Manage_Account_Validator($formDatas)
    {
        $rules = self::manage_account_rules();
        $messages = self::manage_account_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== PAIEMENT VALIDATION =======##
    static function paiement_rules(): array
    {
        return [
            'echeance_date' => ["required", "date"],

            'location' => ['required', "integer"],
            'type' => ['required', "integer"],
            'facture_code' => ['required'],
        ];
    }

    static function paiement_messages(): array
    {
        return [
            'echeance_date.required' => "La date d'écheance est réquise!",
            'echeance_date.date' => 'Ce champ doit être de type date',

            'location.required' => 'La location  est réquise!',
            'type.required' => "Le type de paiement est réquis!",
            'location.integer' => "Ce champ doit être de type entier!",
            'type.integer' => "Ce champ doit être de type entier!",

            'facture_code.required' => "Veillez préciser le code de la facture!",
        ];
    }

    static function Paiement_Validator($formDatas)
    {
        $rules = self::paiement_rules();
        $messages = self::paiement_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }
    ##########______________

    static function _createAgency($request)
    {
        set_time_limit(0);
        $user = request()->user();
        $formData = $request->all();

        ###___VOYONS D'ABORD SI CETTE AGENCE EXISTE DEJA
        $agency = Agency::where(["name" => $formData["name"]])->first();

        if ($agency) {
            return self::sendError("Cette agence existe déjà!", 505);
        }

        ###___
        $country = Country::find($formData["country"]);
        $city = City::find($formData["city"]);
        if (!$country) {
            return self::sendError("Ce pays n'existe pas!", 404);
        }
        if (!$city) {
            return self::sendError("Cette ville n'existe pas!", 404);
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

        return self::sendResponse($agency_user, 'Agence ajoutée avec succès!!');
    }

    static function allAgencys()
    {
        $user = request()->user();
        $Agencys =  Agency::with(["_Locataires", "_Locations", "_AgencyAccounts", "_Owner", "_Proprietors", "_Users", "_Country", "_City"])->where(["visible" => 1])->orderBy("id", "desc")->get();

        return self::sendResponse($Agencys, 'Toutes les Agences récupérées avec succès!!');
    }

    static function _retrieveAgency($id)
    {
        $user = request()->user();
        $agency = Agency::with(["_Locataires", "_Locations", "_AgencyAccounts", "_Owner", "_Proprietors", "_Users", "_Country", "_City"])->find($id);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas!!", 404);
        }
        return self::sendResponse($agency, "Agence récupérée avec succès:!!");
    }

    static function _updateAgency($request, $id)
    {
        $formData = $request->all();

        $AGENCY = Agency::with(["owner"])->where(["visible" => 1])->find($id);
        if (!$AGENCY) {
            return self::sendError("Cette agence n'existe pas!", 404);
        };

        $AGENCY = Agency::with(["owner"])->where(["visible" => 1])->find($id);


        ###___
        if ($request->get("city")) {
            $city = City::find($formData["city"]);
            if (!$city) {
                return self::sendError("Cette ville n'existe pas!", 404);
            }
        }
        if ($request->get("country")) {
            $country = Country::find($formData["country"]);
            if (!$country) {
                return self::sendError("Ce pays n'existe pas!", 404);
            }
        }

        ##GESTION DES FICHIERS
        if ($request->file("ifu_file")) {
            $ifu_file = $request->file('ifu_file');
            $ifu_name = $ifu_file->getClientOriginalName();
            $request->file('ifu_file')->move("pieces", $ifu_name);
            //REFORMATION DU $formData AVANT SON ENREGISTREMENT DANS LA TABLE 
            $formData["ifu_file"] = asset("pieces/" . $ifu_name);
        }

        if ($request->file("rccm_file")) {
            $rccm_file = $request->file('rccm_file');

            $rccm_name = $rccm_file->getClientOriginalName();
            $request->file('rccm_file')->move("pieces", $rccm_name);
            //REFORMATION DU $formData AVANT SON ENREGISTREMENT DANS LA TABLE 
            $formData["rccm_file"] = asset("pieces/" . $rccm_name);
        }

        $AGENCY->update($formData);
        return self::sendResponse($AGENCY, 'Cette agence a été modifiée avec succès!');
    }

    static function AgencyDelete($id)
    {
        $user = request()->user();
        $Agence = Agency::where(['visible' => true])->find($id);
        if (!$Agence) {
            return self::sendError("Cette agence n'existe pas!", 404);
        };

        $Agence->delete_at = now();
        $Agence->visible = false;
        $Agence->save();
        return self::sendResponse($Agence, 'Cette Agence a été supprimée avec succès!');
    }

    static function search($request)
    {

        if (!$request->get("search")) {
            return self::sendError("Le champ **search** est réquis!", 505);
        }
        $search = $request->get("search");

        // search via name
        $result = collect(Agency::where(["visible" => 1])->with(["_Owner", "_Proprietors", "_Users", "_Country", "_City"])->get())->filter(function ($agency) use ($search) {
            return Str::contains(strtolower($agency['name']), strtolower($search));
        })->all();

        if (count($result) == 0) {
            // search via email
            $result = collect(Agency::where(["visible" => 1])->with(["_Owner", "_Proprietors", "_Users", "_Country", "_City"])->get())->filter(function ($agency) use ($search) {
                return Str::contains(strtolower($agency['email']), strtolower($search));
            })->all();

            if (count($result) == 0) {
                // search via phone
                $result = collect(Agency::where(["visible" => 1])->with(["_Owner", "_Proprietors", "_Users", "_Country", "_City"])->get())->filter(function ($agency) use ($search) {
                    return Str::contains(strtolower($agency['phone']), strtolower($search));
                })->all();
            }
        }

        ###__
        if (count($result) == 0) {
            return self::sendError("Aucun résultat trouvé pour cette recherche", 505);
        }

        // ##__
        return self::sendResponse($result, "Résultat de votre recherche");
    }

    ###___
    static function creditateAccount($request, $out_call = false)
    {
        $user = request()->user();

        if ($out_call) { ##__quand creditateAccount est appelée à l'interne
            $formData = $request;
        } else {
            ##__quand c'est un appel externe
            $formData = $request->all();
        }

        ##__
        $agency_account = AgencyAccount::with(["_Account"])->where(["agency" => $formData["agency"]])->find($formData["agency_account"]);
        if (!$agency_account) {
            return self::sendError("Ce compte d'agence n'existe pas! Vous ne pouvez pas le créditer!", 404);
        }

        $account = $agency_account->_Account;

        $formData["sold_added"] = $formData["sold"];

        ###___VERIFIONS LE SOLD ACTUEL DU COMPTE ET VOYONS SI ça DEPPASE OU PAS LE PLAFOND
        $agencyAccountSold = AgencyAccountSold::where(["agency_account" => $formData["agency_account"], "visible" => 1])->first();

        if ($agencyAccountSold) { ##__Si ce compte dispose déjà d'un sold
            $formData["old_sold"] = $agencyAccountSold->sold;

            ##__voyons si le sold atteint déjà le plafond de ce compte
            if ($agencyAccountSold->sold >= $account->plafond_max) {
                return self::sendError("Le sold de ce compte (" . $account->name . ") a déjà atteint son plafond! Vous ne pouvez plus le créditer", 505);
            } else {
                # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
                # ça depasserait le plafond maximum du compte

                if (($agencyAccountSold->sold + $formData["sold"]) > $account->plafond_max) {
                    return self::sendError("L'ajout de ce montant au sold de ce compte (" . $account->name . ") dépasserait son plafond! Veuillez diminuer le montant", 505);
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
                return self::sendError("L'ajout de ce montant au sold de ce compte (" . $account->name . ") dépasserait son plafond! Veuillez diminuer le montant", 505);
            }

            # on le crée
            $agencyAccountSold = AgencyAccountSold::create($formData);
        }

        if ($out_call) {
            return self::sendResponse($agencyAccountSold, "Encaissement éffectué avec succès!!");
        }
        return self::sendResponse($agencyAccountSold, "Le compte (" . $account->name . " (" . $account->description . ") " . ") a été crédité  avec succès!!");
    }

    static function addPaiement($request, $agencyId)
    {
        $formData = $request->all();
        $user = request()->user();

        ###___TRAITEMENT DES DATA
        $location = Location::with(["House", "Locataire", "Room"])->where(["agency" => $agencyId])->find($formData["location"]);
        $type = PaiementType::find($formData["type"]);

        ###___
        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas!", 404);
        }

        ###__
        if (!$location) {
            return self::sendError("Cette location n'existe pas!", 404);
        }

        if (!$type) {
            return self::sendError("Ce type de paiement n'existe pas!", 404);
        }

        ###___TRAITEMENT DU PAIEMENT SI LE LOCATAIRE EST UN PRORATA
        if ($location->Locataire->prorata) {
            $validator = Validator::make(
                $formData,
                [
                    "prorata_amount" => ["required", "numeric"],
                    "prorata_days" => ["required", "numeric"],
                    "prorata_date" => ["required", "date"],
                ],
                [
                    "prorata_amount.required" => "Veuillez préciser le montant du prorata",
                    "prorata_amount.numeric" => "Ce champ doit être de format numérique",

                    "prorata_days.required" => "Veuillez préciser le nombre de jour du prorata",
                    "prorata_days.numeric" => "Ce champ doit être de format numérique",

                    "prorata_date.required" => "Veuillez préciser le nombre de jour du prorata",
                    "prorata_date.date" => "Ce champ doit être de format date",
                ]
            );

            if ($validator->fails()) {
                return self::sendError($validator->errors(), 505);
            }


            ###___CHANGEMENT D'ETAT DU LOCATAIRE(NOTIFIONS Q'IL N'EST PLUS UN PRORATA)
            $locataire = Locataire::find($location->locataire);

            $locataire->prorata = false;
            $locataire->save();
        }

        ##__
        $formData["module"] = 2;
        $formData["status"] = 1;
        $formData["amount"] = $location->loyer;
        $formData["comments"] = "Encaissement de loyer pour le locataire <<" . $location->Locataire->name . " " . $location->Locataire->prenom . ">> de la maison <<" . $location->House->name . ">>  à la date de <<" . now() . ">> par <<" . $user->name . ">>";

        ###__GESTION DE LA REFERENCE
        // $payement_count = count(Payement::all());
        // $formData["reference"] = "REF_" . $payement_count . rand(0, 100) . "/" . substr($type->name, 0, 3); ###__ON RECUPERE LES TROIS PREMIERES LETTRES DE LA CATEGORIE DU DOSSIER QU'ON CONCATENE AVEC LE RAND

        $formData["owner"] = $user->id;

        ###__ENREGISTREMENT DU PAIEMENT DANS LA DB
        // $Paiement = Payement::create($formData);

        ###__ENREGISTREMENT DE LA FACTURE DE PAIEMENT DANS LA DB

        if ($request->file("facture")) {
            $factureFile = $formData["facture"];
            $fileName = $factureFile->getClientOriginalName();
            $factureFile->move("factures", $fileName);
            $formData["facture"] = asset("factures/" . $fileName);
        } else {
            $formData["facture"] = null;
        }
        ##___

        $factureDatas = [
            "owner" => $user->id,
            // "payement" => $Paiement->id,
            "location" => $formData["location"],
            "type" => 1,
            "facture" => $formData["facture"],
            "begin_date" => null,
            "end_date" => null,
            "comments" => $formData["comments"],
            "amount" => $formData["amount"],
            "facture_code" => $formData["facture_code"],
            "echeance_date" => $formData["echeance_date"],
            "is_penality" => $request->get("is_penality") ? true : false ##__Préciser si cette facture est liée à une pénalité ou pas
        ];
        Facture::create($factureDatas);
        ###_____

        ####__ACTUALISATION DE LA LOCATION
        // AJOUT D'UN MOIS DE PLUS SUR LA DERNIERE DATE DE LOYER
        $location_next_loyer_timestamp_plus_one_month = strtotime("+1 month", strtotime($location->next_loyer_date));
        $location_next_loyer_date = date("Y/m/d", $location_next_loyer_timestamp_plus_one_month);

        $location->latest_loyer_date = $location->next_loyer_date; ##__la dernière date de loyer revient maintenant au next_loyer_date
        $location->next_loyer_date = $location_next_loyer_date; ##__le next loyer date est donc incrementé de 1 mois

        $location->previous_echeance_date = $location->echeance_date; ##__la precedente date d'écheance revient maintenant à la date d'écheance qui est en train de passer
        ###__

        $location->echeance_date = $location->next_loyer_date; ###___ l'actuelle date d'écheance revient à la date du prochain loyer

        ####___
        $location->save();
        ##___

        ###____envoie e mail au locataire

        try {
            Send_Notification_Via_Mail(
                $location->Locataire->email,
                "Encaissement de paiement",
                "Votre paiement pour l'échéance du " . $location->latest_loyer_date . " dans la chambre (" . $location->Room->number . ") de la maison (" . $location->House->name . " ) a été fait avec succès!"
            );
        } catch (\Throwable $th) {
            //throw $th;
        }

        ###___INCREMENTATION DU COMPTE LOYER
        $creditateAccountData = [
            'agency' => $agencyId,
            'location' => $formData["location"],
            'agency_account' => 4,
            'sold' => $formData["amount"],
            'description' => "Encaissement de paiement à la date " . $location->latest_loyer_date . " par le locataire (" . $location->Locataire->name . " " . $location->Locataire->prenom . " ) habitant la chambre (" . $location->Room->number . ") de la maison (" . $location->House->name . " )",
        ];

        return self::creditateAccount($creditateAccountData, true);
    }

    ###___
    static function deCreditateAccount($request)
    {
        $user = request()->user();
        $formData = $request->all();

        ##__
        $agency_account = AgencyAccount::with(["_Account"])->find($formData["agency_account"]);
        if (!$agency_account) {
            return self::sendError("Ce compte d'agence n'existe pas! Vous ne pouvez pas le créditer!", 404);
        }

        $formData["sold_retrieved"] = $formData["sold"];

        $account = $agency_account->_Account;

        ###___VERIFIONS LE SOLD ACTUEL DU COMPTE ET VOYONS SI ça DEPPASE OU PAS LE PLAFOND
        $agencyAccountSold = AgencyAccountSold::where(["agency_account" => $formData["agency_account"], "visible" => 1])->first();


        ###___
        if (!$agencyAccountSold) {
            return self::sendError("Désolé! Ce compte ne dispose pas de solde!", 505);
        }

        $formData["old_sold"] = $agencyAccountSold->sold;

        # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
        # ça descendrait en bas de 0
        if (($agencyAccountSold->sold - $formData["sold"]) < 0) {
            return self::sendError("La décreditation de ce montant au sold de ce compte (" . $account->name . ") descendrait en dessous de 0!", 505);
        }

        ##__Quant il s'agit de la caisse CDR
        if ($account->id == 3) {
            if (!$request->get("house")) {
                return self::sendError("Pour le compte CDR, la maison est réquise!", 505);
            }

            ###___
            $house = House::find($request->get("house"));
            if (!$house) {
                return self::sendError("Désolé! Cette maison n'existe pas!", 404);
            }
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
        return self::sendResponse($agencyAccountSold, "Le compte (" . $account->name . " (" . $account->description . ") " . ") a été décrédité  avec succès!!");
    }

    ###____RECUPERATION DE TOUT LES MOUVEMENTS D'UN COMPTE AGENCE
    function retrieveAgenCyAccountMouvements($agency_account)
    {
        $agencyAccount = AgencyAccount::with(["_Account"])->find($agency_account);
        if (!$agencyAccount) {
            return self::sendError("Désolé! Cette caisse n'existe pas!", 404);
        }

        ###
        $agency_account_mouvements = AgencyAccountSold::with(["_Account", "WaterFacture", "House", "WaterFacture"])->where(["agency_account" => $agency_account])->orderBy("id", "desc")->get();

        $data["agency_account_mouvements"] = $agency_account_mouvements;
        $data["account"] = $agencyAccount->_Account;
        return self::sendResponse($data, "Tout les mouvements du compte récupéreés avec succès");
    }

    ####_____
    function _agencyBilan($agencyId, $supervisorId, $action)
    {
        $agency = Agency::find($agencyId);

        if ($action == "supervisor") {
            $supervisor = User::find($supervisorId);


            if (!$supervisor) {
                return self::sendError("Cet superviseur n'existe pas!", 404);
            }
        }

        $agency_houses = [];
        $locations = [];
        $locators = [];
        $moved_locators = [];
        $factures = [];
        $rooms = [];
        $factures_total_amount = [];

        foreach ($agency->_Proprietors as $proprio) {
            if ($proprio->Agency->id == $agencyId) { ##__si le proprio appartient à l'agence
                $proprio_houses = $proprio->Houses;

                foreach ($proprio_houses as $house) {
                    if ($action == "supervisor") {
                        if ($house->Supervisor->id == $supervisorId) {
                            array_push($agency_houses, $house->Rooms);
                            array_push($agency_houses, $house);

                            foreach ($house->Locations as $location) {

                                array_push($locations, $location);
                                array_push($locators, $location->Locataire);

                                ###___recuperons les locataires demenagés
                                if ($location["move_date"]) {
                                    array_push($moved_locators, $location->Locataire);
                                }

                                foreach ($location->AllFactures as $facture) {
                                    array_push($factures, $facture);
                                    array_push($factures_total_amount, $facture["amount"]);
                                }
                            }
                        }
                    } elseif ($action == "agency") {
                        array_push($agency_houses, $house->Rooms);
                        array_push($agency_houses, $house);

                        foreach ($house->Locations as $location) {

                            array_push($locations, $location);
                            array_push($locators, $location->Locataire);

                            ###___recuperons les locataires demenagés
                            if ($location["move_date"]) {
                                array_push($moved_locators, $location->Locataire);
                            }

                            foreach ($location->AllFactures as $facture) {
                                array_push($factures, $facture);
                                array_push($factures_total_amount, $facture["amount"]);
                            }
                        }
                    }
                }
            }
        }

        ####___
        $agency["agency_houses"] = $agency_houses;
        $agency["locations"] = $locations;
        $agency["locators"] = $locators;
        $agency["moved_locators"] = $moved_locators;
        $agency["_factures"] = $factures;
        $agency["rooms"] = $rooms;
        $agency["factures_total_amount"] = $factures_total_amount;

        return self::sendResponse($agency, "Rapport de l'agence recupéré avec succès!");
    }

    ####_____
    function agencyFactures($agencyId, $supervisorId, $action)
    {
        $agency = Agency::find($agencyId);

        if ($action == "supervisor") {
            $supervisor = User::find($supervisorId);

            if (!$supervisor) {
                return self::sendError("Cet superviseur n'existe pas!", 404);
            }
        }

        $factures = [];

        foreach ($agency->_Proprietors as $proprio) {
            if ($proprio->Agency->id == $agencyId) { ##__si le proprio appartient à l'agence
                $proprio_houses = $proprio->Houses;

                foreach ($proprio_houses as $house) {
                    if ($action == "supervisor") {
                        if ($house->Supervisor->id == $supervisorId) {
                            array_push($agency_houses, $house->Rooms);
                            array_push($agency_houses, $house);

                            foreach ($house->Locations as $location) {
                                foreach ($location->AllFactures as $facture) {
                                    array_push($factures, $facture);
                                }
                            }
                        }
                    } elseif ($action == "agency") {
                        foreach ($house->Locations as $location) {
                            foreach ($location->AllFactures as $facture) {
                                if (!$facture["state_facture"]) {
                                    array_push($factures, $facture);
                                }
                            }
                        }
                    }
                }
            }
        }

        return self::sendResponse($factures, "Factures de l'agence recupéré avec succès!");
    }

    static function _getAllSupervisors($agencyId)
    {
        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Désolé! Cette agence n'existe pas!", 404);
        }

        $users = $agency->_Users;

        ####___
        $supervisors = [];

        foreach ($users as $user) {
            $user_roles = $user->roles; ##recuperation des roles de ce user

            foreach ($user_roles as $user_role) {
                if ($user_role->id == 3) {
                    array_push($supervisors, $user);
                }
            }
        }

        return self::sendResponse($supervisors, "Tous les superviseurs de cette agence récupérés avec succès!");
    }
}
