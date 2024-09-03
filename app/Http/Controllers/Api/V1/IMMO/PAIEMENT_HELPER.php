<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Facture;
use App\Models\HomeStopState;
use App\Models\House;
use App\Models\ImmoAccount;
use App\Models\Locataire;
use App\Models\Location;
use App\Models\PaiementModule;
use App\Models\PaiementStatus;
use App\Models\PaiementType;
use App\Models\Payement;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PAIEMENT_HELPER extends BASE_HELPER
{
    ##======== PAIEMENT VALIDATION =======##
    static function paiement_rules(): array
    {
        return [
            'location' => ['required', "integer"],
            'type' => ['required', "integer"],
            // 'month' => ['required', "date"],
            'facture_code' => ['required'],
        ];
    }

    static function paiement_messages(): array
    {
        return [
            'location.required' => 'La location  est réquise!',
            'type.required' => "Le type de paiement est réquis!",
            'location.integer' => "Ce champ doit être de type entier!",
            'type.integer' => "Ce champ doit être de type entier!",
            // 'month.required' => "Ce champ est réquis!",
            // 'month.date' => "Ce champ doit être de type date!",

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

    ##======== FILTRAGE DES PAIEMENTS  =======##
    static function filtre_rules(): array
    {
        return [
            'start_date' => ['required', "date"],
            'end_date' => ['required', "date"],
        ];
    }

    static function filtre_messages(): array
    {
        return [
            'start_date.required' => "La date de début est réquise",
            'end_date.required' => "La date de fin est réquise",

            'start_date.date' => "Ce champ doit être de type date!",
            'end_date.date' => "Ce champ doit être de type date!",
        ];
    }

    static function Filtre_Validator($formDatas)
    {
        $rules = self::filtre_rules();
        $messages = self::filtre_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ###___
    static function addPaiement($request)
    {
        $formData = $request->all();
        $user = request()->user();

        ###___TRAITEMENT DES DATAS
        $location = Location::with(["House", "Locataire", "Room"])->find($formData["location"]);
        $type = PaiementType::find($formData["type"]);

        $formData["module"] = 2;
        $formData["status"] = 1;
        $formData["amount"] = $location->loyer;
        $formData["comments"] = "Encaissement de loyer à la date " . now() . " pour le locataire (" . $location->Locataire->name . " " . $location->Locataire->prenom . " ) habitant la chambre (" . $location->Room->number . ") de la maison (" . $location->House->name . " ) par <<" . $user->name . ">> ";

        ###__GESTION DE LA REFERENCE
        // $payement_count = count(Payement::all());
        // $formData["reference"] = "REF_" . $payement_count . rand(0, 100) . "/" . substr($type->name, 0, 3); ###__ON RECUPERE LES TROIS PREMIERES LETTRES DE LA CATEGORIE DU DOSSIER QU'ON CONCATENE AVEC LE RAND

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

        ###__ENREGISTREMENT DU PAIEMENT DANS LA DB
        // $formData["owner"] = $user->id;
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
            "is_penality" => $request->get("is_penality") ? true : false ##__Préciser si cette facture est liée à une pénalité ou pas
        ];
        $facture = Facture::create($factureDatas);
        ###_____

        ####__ACTUALISATION DE LA LOCATION
        // AJOUT D'UN MOIS DE PLUS SUR LA DERNIERE DATE DE LOYER
        $location_next_loyer_timestamp_plus_one_month = strtotime("+1 month", strtotime($location->next_loyer_date));
        $location_next_loyer_date = date("Y/m/d", $location_next_loyer_timestamp_plus_one_month);

        $location->latest_loyer_date = $location->next_loyer_date; ##__la dernière date de loyer revient maintenant au next_loyer_date
        $location->next_loyer_date = $location_next_loyer_date; ##__le next loyer date est donc incrementé de 1 mois
        ###__

        ####___
        $location->save();
        ##___

        ###___INCREMENTATION DU COMPTE LOYER

        $rent_account = ImmoAccount::find(4);
        $request["description"] = "Encaissement de paiement à la date " . $facture->created_at . " par le locataire (" . $location->Locataire->name . " " . $location->Locataire->prenom . " ) habitant la chambre (" . $location->Room->number . ") de la maison (" . $location->House->name . " )";
        $request["sold"] = $formData["amount"];
        MANAGE_ACCOUNT_HELPER::creditateAccount($request, $rent_account->id);

        return self::sendResponse($facture, "Paiement ajouté avec succès!!");
    }

    static function getPaiements()
    {
        $user = request()->user();
        $Paiements = Payement::with(["Owner", "Module", "Type", "Client", "Status", "Location", "Facture"])->get();
        return self::sendResponse($Paiements, 'Tout les paiements récupérés avec succès!!');
    }

    static function _retrievePaiement($id)
    {
        $user = request()->user();
        $Paiement = Payement::with(["Owner", "Module", "Type", "Client", "Status", "Location", "Facture"])->find($id);
        if (!$Paiement) {
            return self::sendError("Ce paiement n'existe pas!", 404);
        }
        return self::sendResponse($Paiement, "Paiement récupéré avec succès:!!");
    }

    static function _updatePaiement($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $Paiement = Payement::find($id);
        if (!$Paiement) {
            return self::sendError("Paiement n'existe pas!", 404);
        };

        ####____TRAITEMENT DU TYPE DE PAIEMENT
        if ($request->get("type")) {
            $type = PaiementType::find($request->get("type"));
            if (!$type) {
                return self::sendError("Ce type de paiement n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU MODULE DE PROPRIETAIRE
        if ($request->get("module")) {
            $module = PaiementModule::find($request->get("module"));

            if (!$module) {
                return self::sendError("Ce module de paiement n'existe pas!", 404);
            }
        }
        ####____TRAITEMENT DU STATUS DU PAIEMENT
        if ($request->get("status")) {
            $status = PaiementStatus::find($request->get("status"));

            if (!$status) {
                return self::sendError("Ce status de paiement n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU CLIENT
        if ($request->get("client")) {
            $client = User::find($request->get("client"));

            if (!$client) {
                return self::sendError("Ce client n'existe pas!", 404);
            }
        }

        $Paiement->update($formData);
        return self::sendResponse($Paiement, 'Ce paiement a été modifié avec succès!');
    }

    static function filtreByDate($request)
    {
        $user = request()->user();
        $formData = $request->all();

        $start_date = $formData["start_date"];
        $end_date = $formData["end_date"];

        $payements = Payement::with(["Owner", "Module", "Type", "Client", "Status", "Location", "Facture"])->whereBetween('created_at', [$start_date, $end_date])->get();

        return self::sendResponse($payements, 'Filtrage éffectué avec succès!');
    }

    static function _filtreAfterStateDateStoped($request, $houseId)
    {
        $house = House::with(["Locations", "Rooms"])->where(["visible" => 1])->find($houseId);
        if (!$house) {
            return self::sendError("Cette maison n'existe pas", 404);
        }

        ###___DERNIERE DATE D'ARRET DES ETATS DE CETTE MAISON

        $last_state = $house->States->last();
        if (!$last_state) {
            return self::sendError("Cette maison ne dispose d'aucune date d'arrêt des états!", 505);
        }

        ###__DATE DU DERNIER ARRET DE CETTE MAISON
        $state_stop_date_of_this_house = date("Y/m/d", strtotime($last_state->stats_stoped_day));

        ###___LES FACTURES DU DERNIER ETAT
        $last_state_factures = $last_state->Factures;

        ###__LES PAIEMENTS LIES A LA LOCATION DE CETTE MAISON
        $locators_that_paid_before_state_stoped_day = [];
        $locators_that_paid_after_state_stoped_day = [];

        $amount_total_to_paid_before_array = [];
        $amount_total_to_paid_after_array = [];

        foreach ($last_state_factures as $facture) {
            $location_payement_date = date("Y/m/d", strtotime($facture->created_at));

            if ($location_payement_date < $state_stop_date_of_this_house) {
                $data["name"] = $facture->Location->Locataire->name;
                $data["prenom"] = $facture->Location->Locataire->prenom;
                $data["email"] = $facture->Location->Locataire->email;
                $data["phone"] = $facture->Location->Locataire->phone;
                $data["adresse"] = $facture->Location->Locataire->adresse;
                $data["comments"] = $facture->Location->Locataire->comments;
                $data["payement_date"] = $location_payement_date;
                $data["month"] = $facture->Location->next_loyer_date;
                $data["amount_paid"] = $facture->amount;
                
                ##___
                array_push($amount_total_to_paid_before_array, $data["amount_paid"]);
                array_push($locators_that_paid_before_state_stoped_day, $data);
            } else {
                $data["name"] = $facture->Location->Locataire->name;
                $data["prenom"] = $facture->Location->Locataire->prenom;
                $data["email"] = $facture->Location->Locataire->email;
                $data["phone"] = $facture->Location->Locataire->phone;
                $data["adresse"] = $facture->Location->Locataire->adresse;
                $data["comments"] = $facture->Location->Locataire->comments;
                $data["payement_date"] = $location_payement_date;
                $data["month"] = $facture->Location->next_loyer_date;
                $data["amount_paid"] = $facture->amount;

                ####_______
                array_push($amount_total_to_paid_after_array, $data["amount_paid"]);
                array_push($locators_that_paid_after_state_stoped_day, $data);
            }
        }

        // $locators_that_paid_before_state_stoped_day["amount_total_to_paid"] = array_sum($amount_total_to_paid_before_array);
        // $locators_that_paid_after_state_stoped_day["amount_total_to_paid"] = array_sum($amount_total_to_paid_after_array);
        ###____
        $locationsFiltered["beforeStopDate"] = $locators_that_paid_before_state_stoped_day;
        $locationsFiltered["afterStopDate"] = $locators_that_paid_after_state_stoped_day;

        $locationsFiltered["afterStopDateTotal_to_paid"] =  array_sum($amount_total_to_paid_after_array);
        $locationsFiltered["beforeStopDateTotal_to_paid"] =  array_sum($amount_total_to_paid_before_array);

        $locationsFiltered["total_locators"] = count($locationsFiltered["beforeStopDate"]) + count($locationsFiltered["afterStopDate"]);
        return self::sendResponse($locationsFiltered, 'Rapport éffectué avec succès!');
    }

    static function _filtreAfterEcheanceDate($request, $agencyId)
    {
        $user = request()->user();

        $payements = Payement::with(["Location"])->get();

        $locators_that_payed_at_echeance_date = [];
        $locators_that_payed_not_at_echeance_date = [];

        ###___RECUPERATION DES PAYEMENTS LIES A CETTE LOCATION
        $agency_paiements = [];
        foreach ($payements as $payement) {
            if ($payement->Location->agency == $agencyId) {
                array_push($agency_paiements, $payement);
            }
        }

        ##__

        foreach ($agency_paiements as $agency_paiement) {
            $payement_date = date("d/m/Y", strtotime($agency_paiement->created_at));
            $location_echeance_date = date("d/m/Y", strtotime($agency_paiement->Location->echeance_date));
            // return $agency_paiement->Location->Locataire;
            if (strtotime($payement_date) == strtotime($location_echeance_date)) {
                array_push($locators_that_payed_at_echeance_date, $agency_paiement->Location->Locataire);
            } else {
                array_push($locators_that_payed_not_at_echeance_date, $agency_paiement->Location->Locataire);
            }
        }

        $data["locators_that_payed_at_echeance_date"] = $locators_that_payed_at_echeance_date;
        $data["locators_that_payed_not_at_echeance_date"] = $locators_that_payed_not_at_echeance_date;

        return self::sendResponse($data, 'Rapport éffectué avec succès!');
    }

    static function _filtreByDateInAgency($request, $agencyId)
    {
        $user = request()->user();
        $formData = $request->all();

        ###__VALIDATION
        $validator = Validator::make(
            $formData,
            [
                "date" => ["required", "date"],
            ],
            [
                "date.required" => "Veuillez préciser la date",
                "date.date" => "Le champ doit être de format date",
            ]
        );

        if ($validator->fails()) {
            return self::sendError($validator->errors(), 505);
        }
        ###___

        $payements = Payement::with(["Location"])->get();

        $locators = [];

        ###___RECUPERATION DES PAYEMENTS LIES A CETTE LOCATION
        $agency_paiements = [];
        foreach ($payements as $payement) {
            if ($payement->Location->agency == $agencyId) {
                array_push($agency_paiements, $payement);
            }
        }

        ##__
        $date = date("d-m-Y", strtotime($formData["date"]));
        foreach ($agency_paiements as $agency_paiement) {
            $payement_date = date("d-m-Y", strtotime($agency_paiement->created_at));
            if (strtotime($payement_date) == strtotime($date)) {
                array_push($locators, $agency_paiement->Location->Locataire);
            }
        }

        return self::sendResponse($locators, 'Rapport éffectué avec succès!');
    }
};
