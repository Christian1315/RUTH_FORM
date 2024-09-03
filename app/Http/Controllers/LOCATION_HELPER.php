<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Agency;
use App\Models\HomeStopState;
use App\Models\House;
use App\Models\Locataire;
use App\Models\Location;
use App\Models\LocationStatus;
use App\Models\LocationType;
use App\Models\Payement;
use App\Models\Room;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LOCATION_HELPER extends BASE_HELPER
{
    ##======== LOCATION VALIDATION =======##
    static function location_rules(): array
    {
        return [
            'agency' => ['required', "integer"],
            'house' => ['required', "integer"],
            'room' => ['required', "integer"],
            'locataire' => ['required', "integer"],
            'type' => ['required', "integer"],

            'pre_paid' => ['required', "boolean"],
            'post_paid' => ['required', "boolean"],
            'discounter' => ['required', "boolean"],

            // 'caution_bordereau' => ["file"],
            // 'loyer' => ['required', "numeric"],
            'water_counter' => ['required'],
            'electric_counter' => ['required'],

            'caution_number' => ['required', 'integer'],

            'prestation' => ['required', "numeric"],
            'numero_contrat' => ['required'],

            'comments' => ['required'],
            // 'img_contrat' => ["file"],
            // 'caution_water' => ['required', "numeric"],
            // 'echeance_date' => ['required', "date"],
            // 'latest_loyer_date' => ['required', "date"],
            // 'img_prestation' => ["file"],
            'caution_electric' => ['required', "numeric"],
            'effet_date' => ['required', "date"],
            'frais_peiture' => ['required', "numeric"],
        ];
    }

    static function location_messages(): array
    {
        return [
            'agency.required' => 'Veuillez préciser l\'agence!',
            'agency.integer' => "L'agence doit être de type entier",

            'house.required' => 'La maison est réquise!',
            'house.integer' => 'Ce champ doit être de type integer',

            'room.required' => "Le chambre est réquise!",
            'room.integer' => 'Ce champ doit être de type integer',

            'locataire.required' => "Le location est réquis!",
            'locataire.integer' => 'Ce champ doit être de type integer',

            'type.required' => "Le type de location est réquis!",
            'type.integer' => 'Ce champ doit être de type integer',

            // 'caution_bordereau.required' => "Le bordereau de la caution est réquise!",
            'caution_bordereau.file' => "Le bordereau de la caution doit être un fichier!",

            // 'loyer.required' => "Le loyer de la location est réquise!",
            // 'loyer.numeric' => "Ce champ doit être de type numeric!",

            'caution_number.required' => "Le nombre de caution est réquise!",
            'caution_number.integer' => "Le nombre de caution doit être de type integer!",

            'frais_peiture.required' => "Les frais de reprise de peinture sont réquis!",
            'frais_peiture.numeric' => "Ce champ  doit être de caractère numérique!",

            'water_counter.required' => "Le numéro du compteur d'eau est réquis",
            'electric_counter.required' => "Le numéro du compteur électrique est réquis",
            // 'water_counter.numeric' => "Le champ compteur d'eau doit être de caractère numérique",

            'prestation.required' => "La prestation est réquise",
            'prestation.file' => "La prestation doit être un fichier",

            'numero_contrat.required' => "Le numéro du contrat est réquis!",
            'comments.required' => "Le commentaire est réquis",

            'img_contrat.required' => "L'image du contrat est réquise",
            'img_contrat.file' => "L'image du contrat doit être un fichier",

            // 'caution_water.required' => "La caution d'eau est réquise",
            // 'caution_water.numeric' => "La caution d'eau doit être de caractère numérique",

            // 'echeance_date.required' => "La date d'écheance est réquise!",
            // 'echeance_date.date' => "Ce champ doit être de type date",

            // 'latest_loyer_date.required' => "La date du dernier loyer est réquis!",
            // 'latest_loyer_date.date' => "Ce champ doit être de type date",

            'pre_paid.boolean' => "Le champ doit être un booléen!",
            'post_paid.boolean' => "Le champ doit être un booléen",

            'discounter.required' => "Le decompteur est réquis!",
            'discounter.boolean' => "Ce champ doit être de type booléen",

            'img_prestation.file' => "L'image de la prestation doit être un fichier",

            'caution_electric.required' => "La caution d'electricité est réquise!",
            'caution_electric.numeric' => 'Le type de la caution d\'electricité doit être de type numéric!',

            'effet_date.required' => "La date d'effet est réquise!",
            'effet_date.date' => "Ce champ est de type date",
        ];
    }

    static function Location_Validator($formDatas)
    {
        $rules = self::location_rules();
        $messages = self::location_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== DEMENAGEMENT VALIDATION =======##
    static function demenagement_rules(): array
    {
        return [
            'move_comments' => ['required'],
        ];
    }

    static function demenagement_messages(): array
    {
        return [
            'move_comments.required' => "Veuillez préciser la raison de demenagement de cette location!",
        ];
    }

    static function Demenagement_Validator($formDatas)
    {
        $rules = self::demenagement_rules();
        $messages = self::demenagement_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ###___
    static function addlocation($request)
    {
        $formData = $request->all();
        $user = request()->user();

        ###___TRAITEMENT DES DATAS
        $house = House::find($formData["house"]);
        $room = Room::find($formData["room"]);
        $locataire = Locataire::find($formData["locataire"]);
        $type = LocationType::find($formData["type"]);
        $agency = Agency::find($formData["agency"]);

        ###___

        if ($formData["pre_paid"] == $formData["post_paid"]) {
            return self::sendError("Veuillez choisir soit l'option pré-payé, soit le post-payé!", 505);
        }

        if (!$house) {
            return self::sendError("Cette maison n'existe pas!", 404);
        }

        if (!$room) {
            return self::sendError("Cette chambre n'existe pas!", 404);
        }

        if (!$locataire) {
            return self::sendError("Ce locataire n'existe pas!", 404);
        }

        if (!$type) {
            return self::sendError("Ce type de location n'existe pas!", 404);
        }

        if (!$agency) {
            return self::sendError("Cette agence n'existe pas!", 404);
        }


        ####___ TRAITEMENT DE LA CHAMBRE
        if (!$room) {
            return self::sendError("Cette chambre n'existe pas!", 404);
        }

        $room_location = Location::where(["room" => $formData["room"]])->first();
        if ($room_location && $room_location->status != 3) {
            return self::sendError("Cette chambre est déjà occupée!", 505);
        }

        ##___TRAITEMENT DES IMAGES
        if ($request->file("caution_bordereau")) {
            $caution_bordereau = $request->file("caution_bordereau");
            $caution_bordereauName = $caution_bordereau->getClientOriginalName();
            $caution_bordereau->move("caution_bordereaus", $caution_bordereauName);
            $formData["caution_bordereau"] = asset("caution_bordereaus/" . $caution_bordereauName);
        }

        if ($request->file("img_contrat")) {
            $img_contrat = $request->file("img_contrat");
            $img_contratName = $img_contrat->getClientOriginalName();
            $img_contrat->move("img_contrats", $img_contratName);
            $formData["img_contrat"] = asset("img_contrats/" . $img_contratName);
        }

        if ($request->file("img_prestation")) {
            $img_prestation = $request->file("img_prestation");
            $img_prestationName = $img_contrat->getClientOriginalName();
            $img_prestation->move("img_prestations", $img_prestationName);
            $formData["img_prestation"] = asset("img_prestations/" . $img_prestationName);
        }

        ####___VERIFIONS S'IL Y A ELECTRICITE OU PAS
        if ($formData["discounter"] == true) {
            $validator = Validator::make(
                $formData,
                [
                    "kilowater_price" => ["required", "numeric"],
                ],
                [
                    "kilowater_price.required" => "Veuillez préciser le prix du kilowatère!",
                    "kilowater_price.date" => "Ce champ est de type numérique!",
                ]
            );

            if ($validator->fails()) {
                return self::sendError($validator->errors(), 505);
            }
        } else {
            $formData["kilowater_price"] = 0;
        }

        #ENREGISTREMENT DU LOCATION DANS LA DB
        if ($user) {
            $formData["owner"] = $user->id;
        }

        ##__
        $formData["loyer"] = $room->total_amount;


        ###__DETERMIONONS LA DATE D'ECHEANCE
        $echeance_date = "";
        // $effet_date = $formData["integration_date"]; ##__ date d'effet n'est rien d'autre que la date d'intégration

        if ($formData["pre_paid"] === true) {
            ##__En pre-payé, la date d'echeance revient à la date
            ##__de prise d'effet (date d'intégration)
            $echeance_date = $formData["effet_date"];
        } elseif ($formData["post_paid"] === true) {
            ##__En post-payé, la date d'echeance revient à la date
            ##__de prise d'effet (date d'intégration) + 1mois
            $integration_date_timestamp_plus_one_month = strtotime("+1 month", strtotime($formData["effet_date"]));
            $echeance_date = date("Y/m/d", $integration_date_timestamp_plus_one_month);
        }

        $formData["integration_date"] = $formData["effet_date"];

        $formData["previous_echeance_date"] = $echeance_date;
        $formData["echeance_date"] = $echeance_date;
        $formData["latest_loyer_date"] = $formData["effet_date"];

        ####___DESORMAIS LA DATE D'ECHEANCE REVIENT A LA DATE DU PROCHAIN PAIEMENT
        $formData["next_loyer_date"] = $formData["echeance_date"];

        $location = Location::create($formData);

        ###___FORMATION DE LA PROCHAINE DATE DE LOYER
        // ça revient à la précedente date de loyer + 1 mois

        // $location_latest_loyer_timestamp_plus_one_month = strtotime("+1 month", strtotime($location->latest_loyer_date));
        // $location_next_loyer_date = date("Y/m/d", $location_latest_loyer_timestamp_plus_one_month);
        // $location->next_loyer_date = $location_next_loyer_date;

        ###__DETERMIONONS LA DATE DU DERNIER LOYE PAYE (ça revient à la date d'effet aui n'est rien d'autre que la date d'intégration)
        $location->latest_loyer_date = $location->integration_date; ##(date d'effet)



        $location->save();
        ###___
        return self::sendResponse($location, "Location ajoutée avec succès!!");
    }

    static function getAllElectricityLocations($request, $agencyId)
    {
        $user = request()->user();

        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Désolé! Cette agence n'existe pas!", 404);
        }

        $locations = $agency->_Locations;

        $agency_locations = [];

        foreach ($locations as $location) {
            if ($location->Room->electricity) {
                if (count($location->ElectricityFactures) != 0) {
                    $latest_facture = $location->ElectricityFactures[0]; ##__dernier facture de cette location

                    ##___Cette variable determine si la derniere facture est pour un arrêt de state

                    $is_latest_facture_a_state_facture = false;
                    if ($latest_facture->state_facture) {
                        $is_latest_facture_a_state_facture = true; ###__la derniere facture est pour un arrêt de state
                    }

                    ###___l'index de fin de cette location revient à l'index de fin de sa dernière facture
                    $location["end_index"] = $latest_facture->end_index;

                    ###___le montant actuel à payer pour cette location revient au montant de sa dernière facture
                    ###__quand la dernière facture est payée, le current_amount devient 0 
                    $location["current_amount"] = $latest_facture["paid"] ? 0 : $latest_facture["amount"];

                    #####______montant payé
                    $paid_factures_array = [];

                    ###__determinons les arrièrees
                    $unpaid_factures_array = [];
                    $nbr_unpaid_factures_array = [];
                    $total_factures_to_pay_array = [];

                    foreach ($location->ElectricityFactures as $facture) {

                        ###__on recupere toutes les factures sauf la dernière(correspondante à l'arrêt d'état)
                        if ($facture["id"] != $latest_facture["id"]) {
                            ###__on recupere les factures non payés
                            if (!$facture["paid"]) {
                                if (!$facture->state_facture) { ##sauf la dernière(correspondante à l'arrêt d'état)
                                    array_push($unpaid_factures_array, $facture["amount"]);
                                    array_push($nbr_unpaid_factures_array, $facture);
                                }
                            }
                        }

                        ###__on recupere les factures  payées
                        if ($facture->paid) {
                            array_push($paid_factures_array, $facture["amount"]);
                        }
                        ###____
                        array_push($total_factures_to_pay_array, $facture["amount"]);
                    }

                    ###__Nbr d'arrieres
                    $location["nbr_un_paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : count($nbr_unpaid_factures_array);
                    ###__Montant d'arrieres
                    $location["un_paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : array_sum($unpaid_factures_array);

                    ###__Montant payés
                    $location["paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : array_sum($paid_factures_array);

                    ##__total amount to paid
                    $location["total_un_paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : array_sum($total_factures_to_pay_array);

                    ###__Montant dû
                    $location["rest_facture_amount"] = $location["total_un_paid_facture_amount"] - $location["paid_facture_amount"];
                } else {
                    ###___l'index de fin de cette location revient à l'index de fin de sa dernière facture
                    $location["end_index"] = 0;

                    ###___le montant actuel à payer pour cette location revient montant de sa dernière facture
                    ###__quand la dernière facture est payée, le current_amount devient 0 
                    $location["current_amount"] =  0;

                    ###__Nbr d'arrieres
                    $location["nbr_un_paid_facture_amount"] = 0;

                    ###__Montant d'arrieres
                    $location["un_paid_facture_amount"] = 0;

                    ###___
                    $location["water_factures"] = [];

                    ###__Montant payés
                    $location["paid_facture_amount"] = 0;

                    ##__total amount to paid
                    $location["total_un_paid_facture_amount"] = 0;

                    ###__Montant dû
                    $location["rest_facture_amount"] = 0;
                }
                // $location["factures"] = $location_factures;
                array_push($agency_locations, $location);
            }
        }


        return self::sendResponse($agency_locations, 'Toutes les locations ayant d\'électicité récupérés avec succès!!');
    }

    static function getAllWaterLocations($request, $agencyId)
    {
        $user = request()->user();

        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Désolé! Cette agence n'existe pas!", 404);
        }

        $locations = $agency->_Locations;

        $agency_locations = [];

        foreach ($locations as $location) {
            if ($location->Room->water) {
                if (count($location->WaterFactures) != 0) {
                    $latest_facture = $location->WaterFactures[0]; ##__dernier facture de cette location

                    ##___Cette variable determine si la derniere facture est pour un arrêt de state

                    $is_latest_facture_a_state_facture = false;
                    if ($latest_facture->state_facture) {
                        $is_latest_facture_a_state_facture = true; ###__la derniere facture est pour un arrêt de state
                    }

                    ###___l'index de fin de cette location revient à l'index de fin de sa dernière facture
                    $location["end_index"] = $latest_facture->end_index;

                    ###___le montant actuel à payer pour cette location revient au montant de sa dernière facture
                    ###__quand la dernière facture est payée, le current_amount devient 0 
                    $location["current_amount"] = $latest_facture["paid"] ? 0 : $latest_facture["amount"];

                    #####______montant payé
                    $paid_factures_array = [];

                    ###__determinons les arrièrees
                    $unpaid_factures_array = [];
                    $nbr_unpaid_factures_array = [];
                    $total_factures_to_pay_array = [];

                    foreach ($location->WaterFactures as $facture) {
                        ###__on recupere toutes les factures sauf la dernière(correspondante à l'arrêt d'état)
                        if ($facture["id"] != $latest_facture["id"]) {
                            ##sauf la dernière(correspondante à l'arrêt d'état)
                            if (!$facture->state_facture) {
                                if (!$facture["paid"]) {
                                    ###__on recupere les factures non payés
                                    array_push($unpaid_factures_array, $facture["amount"]);
                                    array_push($nbr_unpaid_factures_array, $facture);
                                } else {
                                    // ###__on recupere les factures  payées
                                    // array_push($paid_factures_array, $facture["amount"]);
                                }
                            }
                        }
                        ###__on recupere les factures  payées
                        if ($facture->paid) {
                            array_push($paid_factures_array, $facture["amount"]);
                        }
                        ###____
                        array_push($total_factures_to_pay_array, $facture["amount"]);
                    }

                    ###__Nbr d'arrieres
                    $location["nbr_un_paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : count($nbr_unpaid_factures_array);
                    ###__Montant d'arrieres
                    $location["un_paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : array_sum($unpaid_factures_array);

                    ###__Montant payés
                    $location["paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : array_sum($paid_factures_array);

                    ##__total amount to paid
                    $location["total_un_paid_facture_amount"] = $is_latest_facture_a_state_facture ? 0 : array_sum($total_factures_to_pay_array);

                    ###__Montant dû
                    $location["rest_facture_amount"] = $location["total_un_paid_facture_amount"] - $location["paid_facture_amount"];

                    // dd($location["rest_facture_amount"]);
                } else {
                    ###___l'index de fin de cette location revient à l'index de fin de sa dernière facture
                    $location["end_index"] = 0;

                    ###___le montant actuel à payer pour cette location revient montant de sa dernière facture
                    ###__quand la dernière facture est payée, le current_amount devient 0 
                    $location["current_amount"] =  0;

                    ###__Nbr d'arrieres
                    $location["nbr_un_paid_facture_amount"] = 0;

                    ###__Montant d'arrieres
                    $location["un_paid_facture_amount"] = 0;

                    ###___
                    $location["water_factures"] = [];

                    ###__Montant payés
                    $location["paid_facture_amount"] = 0;

                    ##__total amount to paid
                    $location["total_un_paid_facture_amount"] = 0;

                    ###__Montant dû
                    $location["rest_facture_amount"] = 0;
                }
                array_push($agency_locations, $location);
            }
        }

        return self::sendResponse($agency_locations, 'Toutes les locations ayant d\'eau récupérés avec succès!!');
    }

    static function _updateLocation($request, $id)
    {
        $user = request()->user();

        $formData = $request->all();
        $location = Location::where(["visible" => 1])->find($id);

        if (!$location) {
            return self::sendError("Cette location n'existe pas!", 404);
        };

        if ($location->owner != $user->id) {
            return self::sendError("Cette location ne vous appartient pas!", 404);
        }

        ####____TRAITEMENT DU HOUSE
        if ($request->get("house")) {
            $house = House::find($request->get("house"));
            if (!$house) {
                return self::sendError("Cette maison n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DE LA CHAMBRE
        if ($request->get("room")) {
            $room = Room::find($request->get("room"));

            if (!$room) {
                return self::sendError("Cette chambre n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU LOCATAIRE
        if ($request->get("locataire")) {
            $locataire = Locataire::find($request->get("locataire"));
            if (!$locataire) {
                return self::sendError("Ce locataire n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU TYPE DE LOCATION
        if ($request->get("type")) {
            $type = LocationType::find($request->get("type"));
            if (!$type) {
                return self::sendError("Ce type de location n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU CAUTION BORDEREAU
        if ($request->file("caution_bordereau")) {
            $caution_bordereau = $request->file("caution_bordereau");
            $caution_bordereauName = $caution_bordereau->getClientOriginalName();
            $caution_bordereau->move("caution_bordereaus", $caution_bordereauName);
            $formData["caution_bordereau"] = asset("caution_bordereaus/" . $caution_bordereauName);
        }

        ####____TRAITEMENT DE L'IMAGE DU CONTRAT
        if ($request->file("img_contrat")) {
            $img_contrat = $request->file("img_contrat");
            $img_contratName = $img_contrat->getClientOriginalName();
            $img_contrat->move("img_contrats", $img_contratName);
            $formData["img_contrat"] = asset("img_contrats/" . $img_contratName);
        }


        ####____TRAITEMENT DE L'IMAGE DE LA PRESTATION
        if ($request->file("img_prestation")) {
            $img_prestation = $request->file("img_prestation");
            $img_prestationName = $img_contrat->getClientOriginalName();
            $img_prestation->move("img_prestations", $img_prestationName);
            $formData["img_prestation"] = asset("img_prestations/" . $img_prestationName);
        }

        ####____TRAITEMENT DU STATUS DE LOCATION
        if ($request->get("status")) {
            $status = LocationStatus::find($request->get("status"));
            if (!$status) {
                return self::sendError("Ce status de location n'existe pas!", 404);
            }

            #===SI LE STATUS EST **SUSPEND**=====#
            if ($request->get("status") == 2) {
                if (!$request->get("suspend_comments")) {
                    return self::sendError("Veuillez préciser la raison de suspenssion de cette location!", 404);
                }
                $formData["suspend_date"] = now();
                $formData["suspend_by"] = $user->id;
            }

            #===SI LE STATUS EST **MOVED**=====#
            if ($request->get("status") == 3) {
                if (!$request->get("move_comments")) {
                    return self::sendError("Veuillez préciser la raison de demenagement de cette location!", 404);
                }
                $formData["move_date"] = now();
                $formData["visible"] = 0;
                $formData["delete_at"] = now();
            }
        }

        $location->update($formData);
        return self::sendResponse($location, 'Cette location a été modifiée avec succès!');
    }

    static function locationDelete($id)
    {
        $user = request()->user();
        $location = Location::where(["visible" => 1])->find($id);
        if (!$location) {
            return self::sendError("Cette location n'existe pas!", 404);
        };

        if (!Is_User_An_Admin($user->id)) {
            if ($location->owner != $user->id) {
                return self::sendError("Cette location ne vous appartient pas!", 404);
            }
        }

        $location->visible = 0;
        $location->delete_at = now();
        $location->save();
        return self::sendResponse($location, 'Cette location a été supprimée avec succès!');
    }

    static function locationDemenage($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $location = Location::where(["visible" => 1])->find($id);

        if (!$location) {
            return self::sendError("Cette location n'existe pas!", 404);
        };

        $house = House::find($location->house);

        ###___DERNIERE DATE D'ARRET DES ETATS DE CETTE MAISON ##
        $state_stop_date_of_this_house = HomeStopState::orderBy("id", "desc")->where(["house" => $location->house])->get();
        if (count($state_stop_date_of_this_house) != 0) { ##Quand la maison dispose d'une date des arrets des etats

            ###__DATE D'ARRET DES ETATS DE CETTE MAISON
            $state_stop_date_of_this_house = strtotime($state_stop_date_of_this_house[0]->stats_stoped_day);

            ###__LES LOCATIONS DE CETTE MAISON
            $this_house_locations = $house->Locations;

            ###__LES PAIEMENTS LIES A LA LOCATION DE CETTE MAISON
            $locations_that_paid_before_state_stoped_day = [];
            $locations_that_paid_after_state_stoped_day = [];

            foreach ($this_house_locations as $this_house_location) {
                ###__RECUPERONS LES LOCATIONS AYANT PAYES
                $location_payements = Payement::with(["Status", "Location", "Facture"])->where(["location" => $this_house_location->id])->get();

                ##__TRAITEMENT DE LA DATE DE PAIEMENT( puis filtrer les locations avant et après paiement)
                foreach ($location_payements as $location_payement) {
                    $location_payement_date = strtotime($location_payement->created_at);

                    if ($location_payement_date < $state_stop_date_of_this_house) {
                        array_push($locations_that_paid_before_state_stoped_day, $this_house_location);
                    } else {
                        array_push($locations_that_paid_after_state_stoped_day, $this_house_location);
                    }
                }
            };

            ###___Verifions si ce locataire fait parti des locataires qui ont payé
            ###___après arret des etats

            $result = false;
            foreach ($locations_that_paid_after_state_stoped_day as $locations_that_paid_after_state_stoped_day_) {
                if ($locations_that_paid_after_state_stoped_day_->locataire == $location->locataire) {
                    $result = true;
                }
            }

            if ($result) {
                return self::sendError("Ce locataire a effectué des paiements après l'arrêt des états! Vous ne pouvez pas le démenager!", 505);
            }
        }

        $formData["move_date"] = now();
        $formData["visible"] = 0;
        $formData["delete_at"] = now();

        $location->update($formData);
        return self::sendResponse($location, 'Cette location a été demenagée avec succès!');
    }

    function manageCautions($request, $agencyId)
    {
        $data["caution_html_url"] = env("APP_URL") . "/$agencyId/caution_html";
        return self::sendResponse($data, "Cautions generées en pdf avec succès!");
    }

    function imprimeStates($request, $agencyId, $houseId, $action)
    {
        $agency = Agency::find($agencyId);
        $house = House::find($houseId);

        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }
        if (!$house) {
            return self::sendError("Cette maison n'existe pas", 404);
        }

        ###___
        if ($action == "before" || $action == "after") {
            $data["caution_html_url"] = env("APP_URL") . "/$agencyId/$houseId/$action/locators_state_stoped";
        } else {
            return self::sendError("Cette action n'est pas valide", 404);
        }
        ###__
        return self::sendResponse($data, "Etat des locataires imprimés avec succès generées en pdf avec succès!");
    }

    function imprimeStatesForAllSystem($request, $houseId, $action)
    {
        $house = House::find($houseId);

        if (!$house) {
            return self::sendError("Cette maison n'existe pas", 404);
        }

        $agencyId = 0;
        ###___
        if ($action == "before" || $action == "after") {
            $data["caution_html_url"] = env("APP_URL") . "/$agencyId/$houseId/$action/locators_state_stoped";
        } else {
            return self::sendError("Cette action n'est pas valide", 404);
        }
        ###__
        return self::sendResponse($data, "Etat des locataires imprimés avec succès generées en pdf avec succès!");
    }

    function manageCautionsByHouse($request, $houseId)
    {
        $house = House::find($houseId);
        if (!$house) {
            return self::sendError("Désolé! Cette maison n'existe pas!", 404);
        }

        ###___
        $data["caution_html_url"] = env("APP_URL") . "/$houseId/caution_html_by_house";
        ###__
        return self::sendResponse($data, "Cautions generées en pdf avec succès!");
    }

    function manageCautionsForHouseByPeriod($request, $houseId)
    {
        $house = House::find($houseId);
        if (!$house) {
            return self::sendError("Désolé! Cette maison n'existe pas!", 404);
        }

        ##__
        $formData = $request->all();

        ###__
        $validator = Validator::make(
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
        );

        if ($validator->fails()) {
            return self::sendError($validator->errors(), 505);
        }

        ##__
        $data["caution_html_url"] = env("APP_URL") . "/$houseId/" . $formData['first_date'] . "/" . $formData['last_date'] . "/caution_html_for_house_by_period";

        return self::sendResponse($data, "Cautions generées en pdf avec succès!");
    }

    function manageCautionsByPeriode($request)
    {
        ##__
        $formData = $request->all();

        ###__
        $validator = Validator::make(
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
        );

        if ($validator->fails()) {
            return self::sendError($validator->errors(), 505);
        }

        $data["caution_html_url"] = env("APP_URL") . "/" . $formData['first_date'] . "/" . $formData['last_date'] . "/caution_html_by_period";
        ###__

        return self::sendResponse($data, "Cautions generées en pdf avec succès!");
    }

    function managePrestationStatistique($request, $agencyId)
    {
        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Désolé! Cette agence n'existe pas!", 404);
        }

        ###___
        $data["caution_html_url"] = env("APP_URL") . "/$agencyId/show_prestation_statistique";
        ###__
        return self::sendResponse($data, "Cautions generées en pdf avec succès!");
    }

    function managePrestationStatistiqueForAgencyByPeriod($request, $agencyId, $first_date, $last_date)
    {
        ##__
        $formData = $request->all();

        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Désolé! Cette Agence n'existe pas!", 404);
        }

        ###___
        $data["caution_html_url"] = env("APP_URL") . "/" . $agencyId . "/$first_date/$last_date/show_prestation_statistique_for_agency_by_period";
        ###__
        return self::sendResponse($data, "Cautions generées en pdf avec succès!");
    }

    function locatorsStateStoped($request,  $agencyId, $houseId, $action)
    {
        if ($agencyId != 0) {
            $agency = Agency::find($agencyId);
        }

        ###___
        $house = House::with(["Locations"])->find($houseId);

        ###_____
        $house = House::with(["Locations", "Rooms"])->where(["visible" => 1])->find($houseId);

        if (!$house) {
            return self::sendError("Cette maison n'existe pas", 404);
        }

        ###___DERNIERE DATE D'ARRET DES ETATS DE CETTE MAISON
        $state_stop_date_of_this_house = HomeStopState::orderBy("id", "desc")->where(["house" => $houseId])->get();
        if (count($state_stop_date_of_this_house) == 0) {
            return self::sendError("Cette maison ne dispose d'aucune date d'arrêt des états!", 505);
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


        $before_amont_total_array = [];
        $after_amont_total_array = [];

        ##__TRAITEMENT DE LA DATE DE PAIEMENT( puis filtrer les locations avant et après paiement)
        foreach ($last_state_factures as $facture) {
            $location_payement_date = date("Y/m/d", strtotime($facture->created_at));

            if ($location_payement_date < $state_stop_date_of_this_house) {

                $data["name"] = $facture->Location->Locataire->name;
                $data["prenom"] = $facture->Location->Locataire->prenom;
                $data["email"] = $facture->Location->Locataire->email;
                $data["phone"] = $facture->Location->Locataire->phone;
                $data["payement_date"] = $location_payement_date;
                $data["month"] = $facture->Location->next_loyer_date;
                $data["amount_paid"] = $facture->amount;
                $data["comments"] = $facture->Location->Locataire->comments;

                ##___
                array_push($before_amont_total_array, $data["amount_paid"]);
                array_push($locators_that_paid_before_state_stoped_day, $data);
            } else {
                $data["name"] = $facture->Location->Locataire->name;
                $data["prenom"] = $facture->Location->Locataire->prenom;
                $data["email"] = $facture->Location->Locataire->email;
                $data["phone"] = $facture->Location->Locataire->phone;
                $data["payement_date"] = $location_payement_date;
                $data["month"] = $facture->Location->next_loyer_date;
                $data["amount_paid"] = $facture->amount;
                $data["comments"] = $facture->Location->Locataire->comments;

                ####_______
                array_push($after_amont_total_array, $data["amount_paid"]);
                array_push($locators_that_paid_after_state_stoped_day, $data);
            }
        }

        $locationsFiltered["beforeStopDate"] = $locators_that_paid_before_state_stoped_day;
        $locationsFiltered["afterStopDate"] = $locators_that_paid_after_state_stoped_day;

        ###___
        $locataires = [];
        if ($action == "before") {
            $locataires = $locationsFiltered["beforeStopDate"];
        } elseif ($action == "after") {
            $locataires = $locationsFiltered["afterStopDate"];
        }

        $locators_count = count($locataires);
        $total_locators_count = count($locationsFiltered["beforeStopDate"]) + count($locationsFiltered["afterStopDate"]);

        ###___
        if ($agencyId == 0) {
            ###__Pout tout le system
            return view("locators-state-stoped_all_system", compact(["locators_count", "total_locators_count", "locataires", "house", "action", "after_amont_total_array", "before_amont_total_array", "house"]));
        } else {
            ###__Pout une agence
            return view("locators-state-stoped", compact(["locators_count", "total_locators_count", "locataires", "agency", "action", "after_amont_total_array", "before_amont_total_array", "house"]));
        }
    }

    function showCautionsByPeriod($request, $first_date, $last_date)
    {
        ###___
        $locations = Location::with(["Owner", "House", "Locataire", "Type", "Status", "Room"])->whereBetween('created_at', [$first_date, $last_date])->get();

        ###_____
        $cautions_eau = [];
        $cautions_electricity = [];
        $cautions_loyer = [];

        foreach ($locations as $location) {
            array_push($cautions_electricity, $location->caution_electric);
            array_push($cautions_eau, $location->caution_water);
            array_push($cautions_loyer, ($location->caution_number * $location->loyer));
        }
        ###_______

        return view("cautions", compact(["locations", "cautions_eau", "cautions_electricity", "cautions_loyer"]));
    }

    function showCautionsForHouseByPeriod($request, $houseId, $first_date, $last_date)
    {
        ###___
        $locations = Location::where(["house" => $houseId])->with(["Owner", "House", "Locataire", "Type", "Status", "Room"])->whereBetween('created_at', [$first_date, $last_date])->get();

        ###_____
        $cautions_eau = [];
        $cautions_electricity = [];
        $cautions_loyer = [];

        foreach ($locations as $location) {
            array_push($cautions_electricity, $location->caution_electric);
            array_push($cautions_eau, $location->caution_water);
            array_push($cautions_loyer, ($location->caution_number * $location->loyer));
        }
        ###_______

        return view("cautions", compact(["locations", "cautions_eau", "cautions_electricity", "cautions_loyer"]));
    }

    function showCautionsByHouse($request, $houseId)
    {
        ###___
        $house = House::find($houseId);
        ###___

        $locations = $house->Locations;

        ###_____
        $cautions_eau = [];
        $cautions_electricity = [];
        $cautions_loyer = [];

        foreach ($locations as $location) {
            array_push($cautions_electricity, $location->caution_electric);
            array_push($cautions_eau, $location->caution_water);
            array_push($cautions_loyer, ($location->caution_number * $location->loyer));
        }
        ###_______

        return view("cautions", compact(["locations", "cautions_eau", "cautions_electricity", "cautions_loyer"]));
    }

    function showAgencyCautions($request, $agencyId)
    {
        if ($agencyId == "admin") {
            $locations = Location::with(["Owner", "House", "Locataire", "Type", "Status", "Room"])->get();
        } else {
            $locations = Location::where(["agency" => $agencyId])->with(["Owner", "House", "Locataire", "Type", "Status", "Room"])->get();
        }

        $cautions_eau = [];
        $cautions_electricity = [];
        $cautions_loyer = [];

        foreach ($locations as $location) {
            array_push($cautions_electricity, $location->caution_electric);
            array_push($cautions_eau, $location->caution_water);
            array_push($cautions_loyer, ($location->caution_number * $location->loyer));
        }
        return view("cautions", compact(["locations", "cautions_eau", "cautions_electricity", "cautions_loyer"]));
    }

    function showPrestationStatistique($request, $agencyId)
    {
        $prestations = [];

        ####____
        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas!", 404);
        }

        ####____
        $locations = $agency->_Locations; # Location::where(["agency" => $agencyId])->with(["Owner", "House", "Locataire", "Type", "Status", "Room"])->get();
        foreach ($locations as $location) {
            array_push($prestations, $location->prestation);
        }

        return view("prestation-statistique", compact(["locations", "prestations", "agency"]));
    }

    function showPrestationStatistiqueForAgencyByPeriod($request, $agencyId, $first_date, $last_date)
    {
        $prestations = [];

        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas!", 404);
        }

        ####____
        $locations = Location::where(["agency" => $agencyId])->with(["House", "Locataire", "Type", "Status", "Room"])->whereBetween('created_at', [$first_date, $last_date])->get();
        foreach ($locations as $location) {
            array_push($prestations, $location->prestation);
        }

        return view("prestation-statistique", compact(["locations", "prestations", "agency"]));
    }

    static function search($request)
    {
        if (!$request->get("search")) {
            return self::sendError("Le champ **search** est réquis!", 505);
        }
        $search = $request->get("search");

        ###____ search via name
        $result = collect(Location::where(["visible" => 1])->with(["Owner", "House", "Locataire", "Type", "Room", "Status", "Factures"])->get())->filter(function ($location) use ($search) {
            return Str::contains(strtolower($location["House"]['name']), strtolower($search));
        })->all();

        ###___
        if (count($result) == 0) {
            // search via room
            $result = collect(Location::where(["visible" => 1])->with(["Owner", "House", "Locataire", "Type", "Room", "Status", "Factures"])->get())->filter(function ($location) use ($search) {
                return Str::contains(strtolower($location["Room"]['number']), strtolower($search));
            })->all();

            if (count($result) == 0) {
                // search via locataire name
                $result = collect(Location::where(["visible" => 1])->with(["Owner", "House", "Locataire", "Type", "Room", "Status", "Factures"])->get())->filter(function ($location) use ($search) {
                    return Str::contains(strtolower($location['Locataire']['name']), strtolower($search));
                })->all();

                if (count($result) == 0) {
                    // search via locataire prenom
                    $result = collect(Location::where(["visible" => 1])->with(["Owner", "House", "Locataire", "Type", "Room", "Status", "Factures"])->get())->filter(function ($location) use ($search) {
                        return Str::contains(strtolower($location['Locataire']['prenom']), strtolower($search));
                    })->all();
                }
            }
        }

        if (count($result) == 0) {
            return self::sendError("Aucun résultat trouvé pour cette recherche", 505);
        }

        // ##__
        return self::sendResponse($result, "Résultat de votre recherche");
    }
}
