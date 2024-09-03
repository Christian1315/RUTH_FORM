<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Agency;
use App\Models\CardType;
use App\Models\Country;
use App\Models\Departement;
use App\Models\House;
use App\Models\Locataire;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LOCATAIRE_HELPER extends BASE_HELPER
{
    ##======== LOCATAIRE VALIDATION =======##
    static function locataire_rules(): array
    {
        return [
            'agency' => ['required', "integer"],
            'name' => ['required'],
            'prenom' => ['required'],
            // 'email' => ['required', "email"],
            'sexe' => ['required'],
            'phone' => ['required', "numeric"],
            // 'piece_number' => ['required'],
            // 'mandate_contrat' => ['required', "file"],
            // 'comments' => ['required'],
            'adresse' => ['required'],
            'card_id' => ['required'],
            'card_type' => ['required', "integer"],
            'departement' => ['required', "integer"],
            'country' => ['required', "integer"],
            // 'prorata' => ['required', "boolean"],
            // 'discounter' => ['required', "boolean"],
        ];
    }

    ###________
    static function locataire_messages(): array
    {
        return [
            'agency.required' => "Veillez préciser l'agence!",
            'agency.integer' => "L'agence doit être un entier",

            'name.required' => 'Le nom du locataire est réquis!',
            'prenom.required' => "Le prénom est réquis!",
            'email.required' => "Le mail est réquis!",
            'email.email' => "Ce champ doit être de format mail",
            'sexe.required' => "Le sexe est réquis",
            'phone.required' => "Le phone est réquis",
            'phone.numeric' => "Le phone doit être de type numéric",
            'piece_number.required' => "Le numéro de la pièce est réquise",
            // 'mandate_contrat.required' => "Le contrat du mandat est réquis",
            // 'mandate_contrat.file' => "Le contrat du mandat doit être un fichier",
            'comments.required' => "Le commentaire est réquis",
            'adresse.required' => "L'adresse est réquis!",
            'card_id.required' => "L'ID de la carte est réquis",
            'card_type.required' => "Le type de la carte est réquis",
            'card_type.integer' => 'Le type de la carte doit être de type entier!',

            'departement.required' => "Le departement est réquis",
            'departement.integer' => "Ce champ doit être de type entier",
            'country.required' => "Le pays est réquis",
            'country.integer' => "Ce champ doit être de type entier",

            // 'prorata.required' => "Veuillez préciser s'il s'agit d'un prorata ou pas!",
            // 'prorata.boolean' => "Ce champ doit être de type booléen",

            // 'discounter.required' => "Veuillez préciser s'il y a un décompteur ou pas!",
            // 'discounter.boolean' => "Ce champ doit être de type booléen",
        ];
    }

    ###________
    static function Locataire_Validator($formDatas)
    {
        $rules = self::locataire_rules();
        $messages = self::locataire_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ###___
    static function addLocataire($request)
    {
        $formData = $request->all();
        $user = request()->user();

        ###___TRAITEMENT DES DATAS
        $cardType = CardType::find($formData["card_type"]);
        $departement = Departement::find($formData["departement"]);
        $country = Country::find($formData["country"]);
        $agency = Agency::find($formData["agency"]);


        ####___VERIFIONS S'IL S'AGIT D'UN PRORANA OU PAS
        if ($request->get("prorata")) {
            $validator = Validator::make(
                $formData,
                [
                    "prorata_date" => ["required", "date"],
                ],
                [
                    "prorata_date.required" => "Veuillez préciser la date du prorata!",
                    "prorata_date.date" => "Ce champ est de type date",
                ]
            );

            if ($validator->fails()) {
                return self::sendError($validator->errors(), 505);
            }
        }


        if (!$cardType) {
            return self::sendError("Ce Type de carte n'existe pas!", 404);
        }

        if (!$departement) {
            return self::sendError("Ce département n'existe pas!", 404);
        }

        if (!$country) {
            return self::sendError("Ce pays n'existe pas!", 404);
        }

        if (!$agency) {
            return self::sendError("Cette agence n'existe pas!", 404);
        }

        ##___TRAITEMENT DE L'IMAGE
        if ($request->file("mandate_contrat")) {
            $img = $request->file("mandate_contrat");
            $imgName = $img->getClientOriginalName();
            $img->move("mandate_contrats", $imgName);

            #ENREGISTREMENT DU LOCATAIRE DANS LA DB
            if ($user) {
                $formData["owner"] = $user->id;
            }
            $formData["mandate_contrat"] = asset("mandate_contrats/" . $imgName);
        }

        $formData["prorata"] = $request->prorata ? 1 : 0;
        
        ###___
        $locataire = Locataire::create($formData);
        // return $user;


        ###___CREATION DU CLIENT___###
        // $client = new Client();
        // $client->type = 1;
        // $client->phone = $formData["phone"];
        // $client->email = $formData["email"];
        // $client->name = $formData["name"] . " " . $formData["prenom"];
        // $client->sexe = $formData["sexe"];
        // $client->is_locator = true;
        // $client->comments = $formData["comments"];
        // $client->save();
        ###___FIN CREATION DU CLIENT___###

        return self::sendResponse($locataire, "Locataire ajouté avec succès!!");
    }

    ###________
    static function getLocataires()
    {
        $user = request()->user();
        $locataires = Locataire::where(["visible" => 1])->with(["_Agency", "Owner", "CardType", "CardType", "Departement", "Country", "Locations"])->get();

        $locataires_data_revued = [];

        foreach ($locataires as $locataire) {
            ###____
            $houses = [];
            $rooms = [];

            $nbr_month_paid_array = [];
            $nbr_facture_amount_paid_array = [];
            ####___________

            foreach ($locataire->Locations as $location) {
                array_push($houses, $location->House);
                array_push($rooms, $location->Room);

                $location_states = $location->House->States;
                if (count($location_states) != 0) {
                    ###___on recupère les factures du dernier état de la maison
                    $location_last_state = $location->House->States->last();
                    $location_last_state_factures = $location_last_state->Factures;

                    ##_____
                    foreach ($location_last_state_factures as $facture) {
                        array_push($nbr_month_paid_array, $facture);
                        array_push($nbr_facture_amount_paid_array, $facture->amount);
                    }
                    ###______
                } else {

                    ###__s'il n'y a pas d'état, on tient compte de tout les factures
                    ##___liées à cette location
                    foreach ($location->Factures as $facture) {
                        array_push($nbr_month_paid_array, $facture);
                        array_push($nbr_facture_amount_paid_array, $facture->amount);
                    }
                }
            }

            ####_____
            $locataire["nbr_month_paid_array"] = count($nbr_month_paid_array);
            $locataire["nbr_facture_amount_paid_array"] = array_sum($nbr_facture_amount_paid_array);
            ####____

            $locataire["houses"] = $houses;
            $locataire["rooms"] = $rooms;

            array_push($locataires_data_revued, $locataire);
        }

        ###_____
        return self::sendResponse($locataires_data_revued, 'Tout les locataires récupérés avec succès!!');
    }

    ###________
    static function getAgencyLocataires($agencyId)
    {
        $user = request()->user();

        ###____
        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas!", 404);
        }
        $locataires = $agency->_Locataires; ## Locataire::where(["visible" => 1])->with(["_Agency", "Owner", "CardType", "CardType", "Departement", "Country", "Locations"])->get();
        ###______

        $locataires_data_revued = [];
        foreach ($locataires as $locataire) {
            ###____
            $houses = [];
            $rooms = [];

            $nbr_month_paid_array = [];
            $nbr_facture_amount_paid_array = [];
            ####___________

            foreach ($locataire->Locations as $location) {
                array_push($houses, $location->House);
                array_push($rooms, $location->Room);

                $location_states = $location->House->States;
                if (count($location_states) != 0) {
                    ###___on recupère les factures du dernier état de la maison
                    $location_last_state = $location->House->States->last();
                    $location_last_state_factures = $location_last_state->Factures;

                    ##_____
                    foreach ($location_last_state_factures as $facture) {
                        array_push($nbr_month_paid_array, $facture);
                        array_push($nbr_facture_amount_paid_array, $facture->amount);
                    }
                    ###______
                } else {

                    ###__s'il n'y a pas d'état, on tient compte de tout les factures
                    ##___liées à cette location
                    foreach ($location->Factures as $facture) {
                        array_push($nbr_month_paid_array, $facture);
                        array_push($nbr_facture_amount_paid_array, $facture->amount);
                    }
                }
            }

            ####_____
            $locataire["nbr_month_paid_array"] = count($nbr_month_paid_array);
            $locataire["nbr_facture_amount_paid_array"] = array_sum($nbr_facture_amount_paid_array);
            ####____

            $locataire["houses"] = $houses;
            $locataire["rooms"] = $rooms;

            array_push($locataires_data_revued, $locataire);
        }

        ###_____
        return self::sendResponse($locataires_data_revued, 'Tout les locataires récupérés avec succès!!');
    }

    static function getPaidLocataires($agency, $action, $supervisorId, $houseId)
    {
        $user = request()->user();
        $agency = Agency::find($agency);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas!", 404);
        }

        ####_________
        if ($action == "supervisor") {
            $supervisor = User::find($supervisorId);
            if (!$supervisor) {
                return self::sendError("Ce superviseur n'existe pas!", 404);
            }
        } elseif ($action == "house") {
            $house = User::find($houseId);
            if (!$house) {
                return self::sendError("Ce superviseur n'existe pas!", 404);
            }
        }
        #####________

        $locataires = [];
        ###____

        $locations = $agency->_Locations;

        $now = strtotime(date("Y/m/d", strtotime(now())));

        foreach ($locations as $location) {
            ###__la location
            $location_previous_echeance_date = strtotime(date("Y/m/d", strtotime($location->previous_echeance_date)));
            ###__derniere facture de la location
            $last_facture = $location->Factures->last();
            if ($last_facture) {
                $last_facture_created_date = strtotime(date("Y/m/d", strtotime($last_facture->created_at)));
                $last_facture_echeance_date = strtotime(date("Y/m/d", strtotime($last_facture->echeance_date)));

                // return $location_previous_echeance_date;##1722211200
                // return $now;##1714435200
                ###__si la date de payement de la dernière facture de la location
                ####___est égale à la date d'écheance de la location,
                ###___alors ce locataire est à jour

                $is_location_paid_before_or_after_echeance_date = $last_facture_created_date == $last_facture_echeance_date; ###__quand le paiement a été effectué avant ou après la date d'écheance 
                $is_location_paid_at_echeance_date = $last_facture_created_date == $location_previous_echeance_date; ###__quand le paiement a été effectué exactement à la date d'écheance

                // return $is_location_paid_at_echeance_date;
                if ($is_location_paid_at_echeance_date) {
                    if ($action == "supervisor") {
                        #___ il s'agit d'un filtre par superviseur
                        if ($location->House->Supervisor->id == $supervisorId) {
                            array_push($locataires, $location);
                        }
                    } elseif ($action == "house") {
                        #___ il s'agit d'un filtre par maison
                        if ($location->House->id == $houseId) {
                            array_push($locataires, $location);
                        }
                    } elseif ($action == "agency") {
                        #___cette fois-ci c'est pour toute l'agence
                        array_push($locataires, $location);
                    }
                }
            }
        }

        ####________
        return self::sendResponse($locataires, 'Tout les locataires à jour récupérés avec succès!!');
    }

    static function getUnPaidLocataires($agency)
    {
        $user = request()->user();
        $agency = Agency::find($agency);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas!", 404);
        }

        ###___
        $locataires = [];
        ###____
        $locations = $agency->_Locations;

        foreach ($locations as $location) {
            ###__la location
            $location_previous_echeance_date = strtotime(date("Y/m/d", strtotime($location->previous_echeance_date)));
            ###__derniere facture de la location
            $last_facture = $location->Factures->last();
            if ($last_facture) {
                $last_facture_created_date = strtotime(date("Y/m/d", strtotime($last_facture->created_at)));
                $last_facture_echeance_date = strtotime(date("Y/m/d", strtotime($last_facture->echeance_date)));

                ###__si la date de payement de la dernière facture de la location
                ####___est different de la date d'écheance de la location,
                ###___alors ce locataire est en impayé

                $is_location_paid_before_or_after_echeance_date = $last_facture_created_date == $last_facture_echeance_date; ###__quand le paiement a été effectué avant ou après la date d'écheance 
                $is_location_paid_at_echeance_date = $last_facture_created_date == $location_previous_echeance_date; ###__quand le paiement a été effectué exactement à la date d'écheance

                if (!$is_location_paid_at_echeance_date) {
                    array_push($locataires, $location);
                }
            } else {
                ###___s'il n'a même pas de facture,
                ##__cela revient qu'il est en impayé
                array_push($locataires, $location);
            }
        }
        return self::sendResponse($locataires, 'Tout les locataires en impayés récupérés avec succès!!');
    }

    static function _retrieveLocataire($id)
    {
        $user = request()->user();
        $locataire = Locataire::where(["visible" => 1])->with(["_Agency", "Owner", "CardType", "CardType", "Departement", "Country", "Locations"])->find($id);
        if (!$locataire) {
            return self::sendError("Ce locataire n'existe pas!", 404);
        }

        ###____
        $houses = [];
        $rooms = [];

        $nbr_month_paid_array = [];
        $nbr_facture_amount_paid_array = [];
        ####___________

        foreach ($locataire->Locations as $location) {
            array_push($houses, $location->House);
            array_push($rooms, $location->Room);

            $location_states = $location->House->States;
            if (count($location_states) != 0) {
                ###___on recupère les factures du dernier état de la maison
                $location_last_state = $location->House->States->last();
                $location_last_state_factures = $location_last_state->Factures;

                ##_____
                foreach ($location_last_state_factures as $facture) {
                    array_push($nbr_month_paid_array, $facture);
                    array_push($nbr_facture_amount_paid_array, $facture->amount);
                }
                ###______
            } else {

                ###__s'il n'y a pas d'état, on tient compte de tout les factures
                ##___liées à cette location
                foreach ($location->Factures as $facture) {
                    array_push($nbr_month_paid_array, $facture);
                    array_push($nbr_facture_amount_paid_array, $facture->amount);
                }
            }
        }

        ####_____
        $locataire["nbr_month_paid_array"] = count($nbr_month_paid_array);
        $locataire["nbr_facture_amount_paid_array"] = array_sum($nbr_facture_amount_paid_array);
        ####____

        $locataire["houses"] = $houses;
        $locataire["rooms"] = $rooms;

        return self::sendResponse($locataire, "Locataire récupéré avec succès:!!");
    }

    static function _updateLocataire($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $locataire = Locataire::where(["visible" => 1])->find($id);
        if (!$locataire) {
            return self::sendError("Ce locataire n'existe pas!", 404);
        };

        if ($locataire->owner != $user->id) {
            return self::sendError("Ce locataire ne vous appartient pas!", 404);
        }

        ####____TRAITEMENT DU TYPE DE CARTE
        if ($request->get("card_type")) {
            $type = CardType::find($request->get("card_type"));

            if (!$type) {
                return self::sendError("Ce type de carte n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU DEPARTEMENT
        if ($request->get("departement")) {
            $departement = Departement::find($request->get("departement"));

            if (!$departement) {
                return self::sendError("Ce departement n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU COUNTRY
        if ($request->get("country")) {
            $country = Country::find($request->get("country"));
            if (!$country) {
                return self::sendError("Ce pays n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DE L'IMAGE
        if ($request->file("mandate_contrat")) {
            $img = $request->file("mandate_contrat");
            $imgName = $img->getClientOriginalName();
            $img->move("mandate_contrats", $imgName);
            $formData["mandate_contrat"] = asset("mandate_contrats/" . $imgName);
        }

        $locataire->update($formData);
        return self::sendResponse($locataire, 'Ce locataire a été modifié avec succès!');
    }

    static function locataireDelete($id)
    {
        $user = request()->user();
        $locataire = Locataire::where(["visible" => 1])->find($id);
        if (!$locataire) {
            return self::sendError("Ce locataire n'existe pas!", 404);
        };

        $locataire->visible = 0;
        $locataire->delete_at = now();
        $locataire->save();
        return self::sendResponse($locataire, 'Ce locataire a été supprimé avec succès!');
    }

    static function search($request)
    {
        if (!$request->get("search")) {
            return self::sendError("Le champ **search** est réquis!", 505);
        }
        $search = $request->get("search");

        // search via name
        $result = collect(Locataire::where(["visible" => 1])->with(["Owner", "CardType", "CardType", "Departement", "Country", "Locations"])->get())->filter(function ($locataire) use ($search) {
            return Str::contains(strtolower($locataire['name']), strtolower($search));
        })->all();

        if (count($result) == 0) {
            // search via prenom
            $result = collect(Locataire::where(["visible" => 1])->with(["Owner", "CardType", "CardType", "Departement", "Country", "Locations"])->get())->filter(function ($locataire) use ($search) {
                return Str::contains(strtolower($locataire['prenom']), strtolower($search));
            })->all();

            if (count($result) == 0) {
                // search via phone
                $result = collect(Locataire::where(["visible" => 1])->with(["Owner", "CardType", "CardType", "Departement", "Country", "Locations"])->get())->filter(function ($locataire) use ($search) {
                    return Str::contains(strtolower($locataire['phone']), strtolower($search));
                })->all();
            }
        }

        if (count($result) == 0) {
            return self::sendError("Aucun résultat trouvé pour cette recherche", 505);
        }

        // ##__
        return self::sendResponse($result, "Résultat de votre recherche");
    }

    #####_____FILTRATGE
    static function _recovery05ToEcheanceDate($request, $agencyId, $inner_call = false)
    {
        $agency = Agency::with(["_Proprietors"])->where(["visible" => 1])->find($agencyId);

        ####___HOUSES
        $agency_houses = [];
        foreach ($agency->_Proprietors as $proprio) {
            if ($proprio->agency == $agencyId) { ##__si le proprio appartient à l'agence
                $proprio_houses = $proprio->Houses;
                foreach ($proprio_houses as $house) {
                    array_push($agency_houses, $house);
                }
            }
        }

        #####____locataires ayant payés après l'arrêt d'etat du dernier state dans toutes les maisons
        $locators_that_paid_after_state_stoped_day_of_all_houses = [];

        #####____location ayant payés après l'arrêt d'etat du dernier state dans toutes les maisons
        $locations_that_paid_after_state_stoped_day_of_all_houses = [];
        $locations_that_do_not__paid_after_state_stoped_day_of_all_houses = [];

        ###____PARCOURONS TOUTES LES MAISONS DE CETTE AGENCE, PUIS FILTRONS LES ETATS
        foreach ($agency_houses as $house) {

            ###___DERNIER ETAT D'ARRET DE CETTE MAISON
            $house_last_state = $house->States->last();
            if ($house_last_state) {
                ###__DATE DU DERNIER ARRET DES ETATS DE CETTE MAISON
                $house_last_state_date = date("Y/m/d", strtotime($house_last_state->stats_stoped_day));

                ###__LES FACTURES DE CET DERNIER ETAT
                $house_last_state_factures = $house_last_state->Factures;

                foreach ($house_last_state_factures as $facture) {
                    ###___Echéance date
                    $location_echeance_date = date("Y/m/d", strtotime($facture->Location->previous_echeance_date));

                    $location_payement_date = date("Y/m/d",  strtotime($facture->echeance_date));

                    // dd($house_last_state_date, $location_echeance_date, $location_payement_date);
                    ####___determinons le jour de la date d'écheance
                    $day_of_this_date = explode("/", $location_echeance_date)[2];
                    ###____
                    ###___on verifie si la date de paiement se trouve entre *la date d'arrêt* de l'etat et *la date d'échéance*
                    if ($house_last_state_date > $location_payement_date && $location_payement_date <= $location_echeance_date) {
                        ###___on verifie si le jour de la date d'écheance est le 05
                        if ($day_of_this_date == 05) {
                            if ($inner_call) {
                                ###___pour un out_call, 
                                ###____on renvoie les locations en lieu et place des locataires
                                array_push($locations_that_paid_after_state_stoped_day_of_all_houses, $facture->Location);
                            }
                            array_push($locators_that_paid_after_state_stoped_day_of_all_houses, $facture->Location->Locataire);
                        } else {
                            if ($inner_call) {
                                ###___pour un out_call, 
                                ###____on renvoie aussi les locations n'ayant pas payés dans la période
                                array_push($locations_that_do_not__paid_after_state_stoped_day_of_all_houses, $facture->Location);
                            }
                        }
                    }
                };
            }
        };
        // dd($locators_that_paid_after_state_stoped_day_of_all_houses);

        if ($inner_call) {
            $data["locations_that_paid"] = $locations_that_paid_after_state_stoped_day_of_all_houses;
            $data["locations_that_do_not_paid"] = $locations_that_do_not__paid_after_state_stoped_day_of_all_houses;
            return $data;
        }

        return self::sendResponse($locators_that_paid_after_state_stoped_day_of_all_houses, "Locataires ayant payés après les arrêts d'etats dans toutes les maisons de cette agence, pour le recouvrement de 05!");
    }

    static function _recovery10ToEcheanceDate($request, $agencyId, $inner_call = false)
    {
        $agency = Agency::with(["_Proprietors"])->where(["visible" => 1])->find($agencyId);

        ####___HOUSES
        $agency_houses = [];
        foreach ($agency->_Proprietors as $proprio) {
            if ($proprio->agency == $agencyId) { ##__si le proprio appartient à l'agence
                $proprio_houses = $proprio->Houses;
                foreach ($proprio_houses as $house) {
                    array_push($agency_houses, $house);
                }
            }
        }

        #####____locataires ayant payés après l'arrêt d'etat du dernier state dans toutes les maisons
        $locators_that_paid_after_state_stoped_day_of_all_houses = [];

        #####____location ayant payés après l'arrêt d'etat du dernier state dans toutes les maisons
        $locations_that_paid_after_state_stoped_day_of_all_houses = [];
        $locations_that_do_not__paid_after_state_stoped_day_of_all_houses = [];

        ###____PARCOURONS TOUTES LES MAISONS DE CETTE AGENCE, PUIS FILTRONS LES ETATS
        foreach ($agency_houses as $house) {

            ###___DERNIER ETAT D'ARRET DE CETTE MAISON
            $house_last_state = $house->States->last();
            if ($house_last_state) {
                ###__DATE DU DERNIER ARRET DES ETATS DE CETTE MAISON
                $house_last_state_date = date("Y/m/d", strtotime($house_last_state->stats_stoped_day));

                ###__LES FACTURES DE CET DERNIER ETAT
                $house_last_state_factures = $house_last_state->Factures;

                foreach ($house_last_state_factures as $facture) {
                    ###___Echéance date
                    $location_echeance_date = date("Y/m/d", strtotime($facture->Location->previous_echeance_date));

                    $location_payement_date = date("Y/m/d",  strtotime($facture->echeance_date));

                    ####___determinons le jour de la date d'écheance
                    $day_of_this_date = explode("/", $location_echeance_date)[2];
                    ###____
                    ###___on verifie si la date de paiement se trouve entre *la date d'arrêt* de l'etat et *la date d'échéance*
                    if ($house_last_state_date > $location_payement_date && $location_payement_date <= $location_echeance_date) {
                        ###___on verifie si le jour de la date d'écheance est le 10
                        if ($day_of_this_date == 10) {
                            if ($inner_call) {
                                ###___pour un out_call, 
                                ###____on renvoie les locations en lieu et place des locataires
                                array_push($locations_that_paid_after_state_stoped_day_of_all_houses, $facture->Location);
                            }
                            array_push($locators_that_paid_after_state_stoped_day_of_all_houses, $facture->Location->Locataire);
                        } else {
                            if ($inner_call) {
                                ###___pour un out_call, 
                                ###____on renvoie aussi les locations n'ayant pas payés dans la période
                                array_push($locations_that_do_not__paid_after_state_stoped_day_of_all_houses, $facture->Location);
                            }
                        }
                    }
                };
            }
        };

        if ($inner_call) {
            $data["locations_that_paid"] = $locations_that_paid_after_state_stoped_day_of_all_houses;
            $data["locations_that_do_not_paid"] = $locations_that_do_not__paid_after_state_stoped_day_of_all_houses;
            return $data;
        }

        return self::sendResponse($locators_that_paid_after_state_stoped_day_of_all_houses, "Locataires ayant payés après les arrêts d'etats dans toutes les maisons de cette agence, pour le recouvrement de 10!");
    }

    function _recoveryQualitatif($request, $agencyId, $inner_call = false)
    {
        $agency = Agency::with(["_Proprietors"])->where(["visible" => 1])->find($agencyId);

        ####___HOUSES
        $agency_houses = [];
        foreach ($agency->_Proprietors as $proprio) {
            if ($proprio->agency == $agencyId) { ##__si le proprio appartient à l'agence
                $proprio_houses = $proprio->Houses;
                foreach ($proprio_houses as $house) {
                    array_push($agency_houses, $house);
                }
            }
        }

        #####____locataires ayant payés après l'arrêt d'etat du dernier state dans toutes les maisons
        $locators_that_paid_after_state_stoped_day_of_all_houses = [];

        #####____location ayant payés après l'arrêt d'etat du dernier state dans toutes les maisons
        $locations_that_paid_after_state_stoped_day_of_all_houses = [];
        $locations_that_do_not__paid_after_state_stoped_day_of_all_houses = [];

        ###____PARCOURONS TOUTES LES MAISONS DE CETTE AGENCE, PUIS FILTRONS LES ETATS
        foreach ($agency_houses as $house) {

            ###___DERNIER ETAT D'ARRET DE CETTE MAISON
            $house_last_state = $house->States->last();
            if ($house_last_state) {
                ###__DATE DU DERNIER ARRET DES ETATS DE CETTE MAISON
                $house_last_state_date = date("Y/m/d", strtotime($house_last_state->stats_stoped_day));

                ###__LES FACTURES DE CET DERNIER ETAT
                $house_last_state_factures = $house_last_state->Factures;

                foreach ($house_last_state_factures as $facture) {
                    ###___Echéance date
                    $location_echeance_date = date("Y/m/d", strtotime($facture->Location->previous_echeance_date));

                    $location_payement_date = date("Y/m/d",  strtotime($facture->echeance_date));

                    ####___determinons le jour de la date d'écheance
                    $day_of_this_date = explode("/", $location_echeance_date)[2];
                    ###____
                    ###___on verifie si la date de paiement se trouve entre *la date d'arrêt* de l'etat et *la date d'échéance*
                    if ($house_last_state_date > $location_payement_date && $location_payement_date <= $location_echeance_date) {
                        ###___on verifie si le jour de la date d'écheance est le 05 ou le 10
                        if ($day_of_this_date == 05 || $day_of_this_date == 10) {
                            if ($inner_call) {
                                ###___pour un out_call, 
                                ###____on renvoie les locations en lieu et place des locataires
                                array_push($locations_that_paid_after_state_stoped_day_of_all_houses, $facture->Location);
                            }

                            array_push($locators_that_paid_after_state_stoped_day_of_all_houses, $facture->Location->Locataire);
                        } else {
                            if ($inner_call) {
                                ###___pour un out_call, 
                                ###____on renvoie aussi les locations n'ayant pas payés dans la période
                                array_push($locations_that_do_not__paid_after_state_stoped_day_of_all_houses, $facture->Location);
                            }
                        }
                    }
                };
            }
        };

        if ($inner_call) {
            $data["locations_that_paid"] = $locations_that_paid_after_state_stoped_day_of_all_houses;
            $data["locations_that_do_not_paid"] = $locations_that_do_not__paid_after_state_stoped_day_of_all_houses;
            return $data;
        }

        return self::sendResponse($locators_that_paid_after_state_stoped_day_of_all_houses, "Locataires ayant payés après les arrêts d'etats dans toutes les maisons de cette agence, pour le recouvrement qualitatif");
    }


    // recouvrement 05
    function imprimeAgencyTaux05($request, $agencyId, $action, $supervisor = null, $house = null)
    {
        $agency = Agency::find($agencyId);

        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        if (!$action) {
            $action = "null";
            $supervisor = "null";
            $house = "null";
        }

        ####____
        $start_date = "null";
        $end_date = "null";

        ###___
        $data["taux_html_url"] = env("APP_URL") . "/$agencyId/$action/$supervisor/$house/$start_date/$end_date/show_taux_05_agency";
        ###__
        return self::sendResponse($data, "Etat des locataires imprimés avec succès generées en pdf avec succès!");
    }

    function imprimeAgencyTaux05_supervisor($request, $agencyId, $supervisor)
    {
        $formData = $request->all();

        ###____validation des dates
        $validator = Validator::make(
            $formData,
            [
                "start_date" => ["required", "date"],
                "end_date" => ["required", "date"],
            ],
            [
                "start_date.required" => "La date de début est réquise",
                "end_date.required" => "La date de début est réquise",

                "start_date.date" => "Ce champ doit être une date",
                "end_date.date" => "Ce champ doit être une date",
            ]
        );

        if ($validator->fails()) {
            return self::sendError($validator->errors(), 505);
        }

        ####____
        $start_date = $formData["start_date"];
        $end_date = $formData["end_date"];
        $agency = Agency::find($agencyId);
        ####_____

        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        $action = "supervisor";
        $house = "null";
        ###___
        $data["taux_html_url"] = env("APP_URL") . "/$agencyId/$action/$supervisor/$house/$start_date/$end_date/show_taux_05_agency";
        ###__
        return self::sendResponse($data, "Etat des locataires imprimés avec succès generées en pdf avec succès!");
    }

    function imprimeAgencyTaux05_house($request, $agencyId, $house)
    {
        $formData = $request->all();

        ###____validation des dates
        $validator = Validator::make(
            $formData,
            [
                "start_date" => ["required", "date"],
                "end_date" => ["required", "date"],
            ],
            [
                "start_date.required" => "La date de début est réquise",
                "end_date.required" => "La date de début est réquise",

                "start_date.date" => "Ce champ doit être une date",
                "end_date.date" => "Ce champ doit être une date",
            ]
        );

        if ($validator->fails()) {
            return self::sendError($validator->errors(), 505);
        }

        ####____
        $start_date = $formData["start_date"];
        $end_date = $formData["end_date"];

        $agency = Agency::find($agencyId);
        ####_____

        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        $action = "house";
        $supervisor = "null";

        ###___
        $data["taux_html_url"] = env("APP_URL") . "/$agencyId/$action/$supervisor/$house/$start_date/$end_date/show_taux_05_agency";
        ###__

        return self::sendResponse($data, "Etat des locataires imprimés avec succès generées en pdf avec succès!");
    }

    function _showAgencyTaux05($request, $agencyId, $action, $supervisor, $house, $start_date, $end_date)
    {
        ###__
        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        ###____ça revient aux locataires se trouvant dans le recouvrement 05
        $recovery05_locations = self::_recovery05ToEcheanceDate($request, $agencyId, true);

        $locations_that_paid = $recovery05_locations["locations_that_paid"];
        $locations_that_do_not_paid = $recovery05_locations["locations_that_do_not_paid"];
        $total_of_both_of_them = count($locations_that_paid) + count($locations_that_do_not_paid);

        ###___
        $locations = [];


        if ($action == "supervisor") {
            foreach ($locations_that_paid as $location) {
                if ($location->House->Supervisor->id == $supervisor) {
                    ###__on recupère les locations dont les maisons sont 
                    ###____attachées à ce superviseur
                    array_push($locations, $location);
                }
            }

            $supervisor = User::find($supervisor);
        } elseif ($action == "house") {
            foreach ($locations_that_paid as $location) {
                if ($location->House->id == $house) {
                    ###__on recupère les locations attachées à cette maison 
                    array_push($locations, $location);
                }
            }

            $house = House::find($house);
        } else {
            $supervisor = null;
            $house = null;

            $locations = $locations_that_paid;
        }
        ###__
        return view("recovery05_locators", compact(["locations", "action", "agency", "supervisor", "house", "locations_that_do_not_paid", "total_of_both_of_them"]));
    }

    // recouvrement 10
    function imprimeAgencyTaux10($request, $agencyId, $action, $supervisor = null, $house = null)
    {
        $agency = Agency::find($agencyId);

        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        if (!$action) {
            $action = "null";
            $supervisor = "null";
            $house = "null";
        }

        ####____
        $start_date = "null";
        $end_date = "null";

        ###___
        $data["taux_html_url"] = env("APP_URL") . "/$agencyId/$action/$supervisor/$house/$start_date/$end_date/show_taux_10_agency";
        ###__
        return self::sendResponse($data, "Etat des locataires imprimés avec succès generées en pdf avec succès!");
    }

    function imprimeAgencyTaux10_supervisor($request, $agencyId, $supervisor)
    {
        $formData = $request->all();

        ###____validation des dates
        $validator = Validator::make(
            $formData,
            [
                "start_date" => ["required", "date"],
                "end_date" => ["required", "date"],
            ],
            [
                "start_date.required" => "La date de début est réquise",
                "end_date.required" => "La date de début est réquise",

                "start_date.date" => "Ce champ doit être une date",
                "end_date.date" => "Ce champ doit être une date",
            ]
        );

        if ($validator->fails()) {
            return self::sendError($validator->errors(), 505);
        }

        ####____
        $start_date = $formData["start_date"];
        $end_date = $formData["end_date"];

        $agency = Agency::find($agencyId);

        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        $action = "supervisor";
        $house = "null";
        ###___
        $data["taux_html_url"] = env("APP_URL") . "/$agencyId/$action/$supervisor/$house/$start_date/$end_date/show_taux_10_agency";
        ###__
        return self::sendResponse($data, "Etat des locataires imprimés avec succès generées en pdf avec succès!");
    }

    function imprimeAgencyTaux10_house($request, $agencyId, $house)
    {
        $formData = $request->all();

        ###____validation des dates
        $validator = Validator::make(
            $formData,
            [
                "start_date" => ["required", "date"],
                "end_date" => ["required", "date"],
            ],
            [
                "start_date.required" => "La date de début est réquise",
                "end_date.required" => "La date de début est réquise",

                "start_date.date" => "Ce champ doit être une date",
                "end_date.date" => "Ce champ doit être une date",
            ]
        );

        if ($validator->fails()) {
            return self::sendError($validator->errors(), 505);
        }

        ####____
        $start_date = $formData["start_date"];
        $end_date = $formData["end_date"];


        $agency = Agency::find($agencyId);

        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        $action = "house";
        $supervisor = "null";

        ###___
        $data["taux_html_url"] = env("APP_URL") . "/$agencyId/$action/$supervisor/$house/$start_date/$end_date/show_taux_10_agency";
        ###__
        return self::sendResponse($data, "Etat des locataires imprimés avec succès generées en pdf avec succès!");
    }

    function _showAgencyTaux10($request, $agencyId, $action, $supervisor, $house, $start_date, $end_date)
    {
        ###__
        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        ###____ça revient aux locataires se trouvant dans le recouvrement 10
        $recovery05_locations = self::_recovery10ToEcheanceDate($request, $agencyId, true);

        $locations_that_paid = $recovery05_locations["locations_that_paid"];
        $locations_that_do_not_paid = $recovery05_locations["locations_that_do_not_paid"];
        $total_of_both_of_them = count($locations_that_paid) + count($locations_that_do_not_paid);

        ###___
        $locations = [];

        if ($action == "supervisor") {
            foreach ($locations_that_paid as $location) {
                if ($location->House->Supervisor->id == $supervisor) {
                    ###__on recupère les locations dont les maisons sont 
                    ###____attachées à ce superviseur

                    array_push($locations, $location);
                }
            }

            $supervisor = User::find($supervisor);
        } elseif ($action == "house") {
            foreach ($locations_that_paid as $location) {
                if ($location->House->id == $house) {
                    ###__on recupère les locations attachées à cette maison 
                    array_push($locations, $location);
                }
            }

            $house = House::find($house);
        } else {
            $supervisor = null;
            $house = null;

            $locations = $locations_that_paid;
        }
        ###__
        return view("recovery10_locators", compact(["locations", "action", "agency", "supervisor", "house", "locations_that_do_not_paid", "total_of_both_of_them"]));
    }

    // recouvrement qualitatif
    function imprimeAgencyTauxQualitatif($request, $agencyId, $action, $supervisor = null, $house = null)
    {

        $agency = Agency::find($agencyId);

        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        if (!$action) {
            $action = "null";
            $supervisor = "null";
            $house = "null";
        }

        ####___
        $start_date = "null";
        $end_date = "null";
        ###___

        $data["taux_html_url"] = env("APP_URL") . "/$agencyId/$action/$supervisor/$house/$start_date/$end_date/show_taux_qualitatif_agency";
        ###__
        return self::sendResponse($data, "Etat des locataires imprimés avec succès generées en pdf avec succès!");
    }

    function imprimeAgencyTauxQualitatif_supervisor($request, $agencyId, $supervisor)
    {
        $formData = $request->all();

        ###____validation des dates
        $validator = Validator::make(
            $formData,
            [
                "start_date" => ["required", "date"],
                "end_date" => ["required", "date"],
            ],
            [
                "start_date.required" => "La date de début est réquise",
                "end_date.required" => "La date de début est réquise",

                "start_date.date" => "Ce champ doit être une date",
                "end_date.date" => "Ce champ doit être une date",
            ]
        );

        if ($validator->fails()) {
            return self::sendError($validator->errors(), 505);
        }

        ####____
        $start_date = $formData["start_date"];
        $end_date = $formData["end_date"];

        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        $action = "supervisor";
        $house = "null";
        ###___
        $data["taux_html_url"] = env("APP_URL") . "/$agencyId/$action/$supervisor/$house/$start_date/$end_date/show_taux_qualitatif_agency";
        ###__
        return self::sendResponse($data, "Etat des locataires imprimés avec succès generées en pdf avec succès!");
    }

    function imprimeAgencyTauxQualitatif_house($request, $agencyId, $house)
    {
        $formData = $request->all();

        ###____validation des dates
        $validator = Validator::make(
            $formData,
            [
                "start_date" => ["required", "date"],
                "end_date" => ["required", "date"],
            ],
            [
                "start_date.required" => "La date de début est réquise",
                "end_date.required" => "La date de début est réquise",

                "start_date.date" => "Ce champ doit être une date",
                "end_date.date" => "Ce champ doit être une date",
            ]
        );

        if ($validator->fails()) {
            return self::sendError($validator->errors(), 505);
        }

        ####____
        $start_date = $formData["start_date"];
        $end_date = $formData["end_date"];

        $agency = Agency::find($agencyId);

        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        $action = "house";
        $supervisor = "null";
        ###___
        $data["taux_html_url"] = env("APP_URL") . "/$agencyId/$action/$supervisor/$house/$start_date/$end_date/show_taux_qualitatif_agency";
        ###__
        return self::sendResponse($data, "Etat des locataires imprimés avec succès generées en pdf avec succès!");
    }

    function _showAgencyTauxQualitatif($request, $agencyId, $action, $supervisor, $house, $start_date, $end_date)
    {
        ###__
        $agency = Agency::find($agencyId);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas", 404);
        }

        ###____ça revient aux locataires se trouvant dans le recouvrement qualitatif
        $recovery_qualitatif_locations = self::_recoveryQualitatif($request, $agencyId, true);

        $locations_that_paid = $recovery_qualitatif_locations["locations_that_paid"];
        $locations_that_do_not_paid = $recovery_qualitatif_locations["locations_that_do_not_paid"];
        $total_of_both_of_them = count($locations_that_paid) + count($locations_that_do_not_paid);

        ###___
        $locations = [];

        if ($action == "supervisor") {
            foreach ($locations_that_paid as $location) {
                if ($location->House->Supervisor->id == $supervisor) {
                    ###__on recupère les locations dont les maisons sont 
                    ###____attachées à ce superviseur

                    array_push($locations, $location);
                }
            }

            $supervisor = User::find($supervisor);
        } elseif ($action == "house") {
            foreach ($locations_that_paid as $location) {
                if ($location->House->id == $house) {
                    ###__on recupère les locations attachées à cette maison 
                    array_push($locations, $location);
                }
            }

            $house = House::find($house);
        } else {
            $supervisor = null;
            $house = null;

            $locations = $locations_that_paid;
        }
        ###__
        return view("recovery_qualitatif_locators", compact(["locations", "action", "agency", "supervisor", "house", "locations_that_do_not_paid", "total_of_both_of_them"]));
    }
}
