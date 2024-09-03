<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Agency;
use App\Models\City;
use App\Models\Country;
use App\Models\Departement;
use App\Models\Facture;
use App\Models\House;
use App\Models\HouseType;
use App\Models\Proprietor;
use App\Models\Quarter;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HOUSE_HELPER extends BASE_HELPER
{
    ##======== HOUSE VALIDATION =======##
    static function house_rules(): array
    {
        return [
            'name' => ['required'],
            'proprio_payement_echeance_date' => ['required', "date"],
            // 'comments' => ['required'],

            'proprietor' => ['required', "integer"],
            'type' => ['required', "integer"],
            'city' => ['required', "integer"],
            'country' => ['required', "integer"],
            'departement' => ['required', "integer"],
            // 'quartier' => ['required', "integer"],
            'zone' => ['required', "integer"],
            'supervisor' => ['required', "integer"],
        ];
    }

    static function house_messages(): array
    {
        return [
            'name.required' => 'Le nom de la maison est réquis!',
            'proprio_payement_echeance_date.required' => "La date d'écheance du payement du propriétaire est réquise!",
            'proprio_payement_echeance_date.date' => "Ce champ doit être de type date",
            'comments.required' => "Le commentaire est réquis!",
            'proprietor.required' => "Le propriétaire est réquis",
            'type.required' => "Le type de la chambre est réquis",
            'city.required' => "La ville est réquise",
            'country.required' => "Le Pays est réquis",
            'departement.required' => "Le departement est réquis",
            'quartier.required' => "Le quartier est réquis",
            'zone.required' => "La zone est réquise",
            'supervisor.required' => "Le superviseur est réquis",

            'proprietor.integer' => 'Ce champ doit être de type entier!',
            'type.integer' => 'Ce champ doit être de type entier!',
            'city.integer' => 'Ce champ doit être de type entier!',
            'country.integer' => 'Ce champ doit être de type entier!',
            'departement.integer' => 'Ce champ doit être de type entier!',
            'quartier.integer' => 'Ce champ doit être de type entier!',
            'zone.integer' => 'Ce champ doit être de type entier!',
            'supervisor.integer' => 'Ce champ doit être de type entier!',
        ];
    }

    static function House_Validator($formDatas)
    {
        $rules = self::house_rules();
        $messages = self::house_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ###___
    static function addHouse($request)
    {
        $formData = $request->all();
        $user = request()->user();

        ###___TRAITEMENT DES DATAS
        $proprietor = Proprietor::where(["visible" => 1])->find($formData["proprietor"]);
        $type = HouseType::find($formData["type"]);
        $city = City::find($formData["city"]);
        $country = Country::find($formData["country"]);
        $departement = Departement::find($formData["departement"]);
        $quartier = Quarter::find($formData["quartier"]);
        $zone = Zone::find($formData["zone"]);
        $user_supervisor = User::find($formData["supervisor"]);

        if (!$proprietor) {
            return self::sendError("Ce Propriétaire n'existe pas!", 404);
        }

        if (!$type) {
            return self::sendError("Ce Type de chambre n'existe pas!", 404);
        }

        if (!$city) {
            return self::sendError("Cette ville n'existe pas!", 404);
        }

        if (!$country) {
            return self::sendError("Ce pays n'existe pas!", 404);
        }

        if (!$departement) {
            return self::sendError("Ce departement n'existe pas!", 404);
        }

        // if (!$quartier) {
        //     return self::sendError("Ce quartier n'existe pas!", 404);
        // }

        if (!$zone) {
            return self::sendError("Cette zone n'existe pas!", 404);
        }

        if (!$user_supervisor) {
            return self::sendError("Ce superviseur n'existe pas!", 404);
        }

        ##__VERIFIONS SI LE UER_SUPERVISOR DISPOSE VRAIMENT DU ROLE D'UN SUPERVISEUR
        $user_roles = $user_supervisor->roles; ##recuperation des roles de ce user_supervisor
        $is_this_user_supervisor_has_supervisor_role = false; ##cette variable permet de verifier si user_supervisor dispose vraiment du rôle d'un superviseur

        foreach ($user_roles as $user_role) {
            if ($user_role->id == 3) {
                $is_this_user_supervisor_has_supervisor_role = true;
            }
        }

        if (!$is_this_user_supervisor_has_supervisor_role) {
            return self::sendError("Ce utilisateur choisi comme superviseur ne dispose vraiment pas le rôle d'un superviseur!", 404);
        }

        #ENREGISTREMENT DE LA CARTE DANS LA DB
        $formData["owner"] = $user->id;
        $house = House::create($formData);

        return self::sendResponse($house, "Maison ajoutée avec succès!!");
    }

    static function getHouses()
    {
        $user = request()->user();
        $houses = House::where(["visible" => 1])->with(["Owner", "Proprietor", "Type", "Supervisor", "City", "Country", "Departement", "Quartier", "Zone", "Rooms", "Locations", "States", "AllStatesDepenses", "PayementInitiations"])->get();

        foreach ($houses as $house) {

            $nbr_month_paid = 0;
            $total_amount_paid = 0;

            $house_factures_nbr_array = [];
            $house_amount_nbr_array = [];

            ####_____DERNIER ETAT DE CETTE MAISON
            $house_last_state = $house->States->last();


            $locations = $house->Locations;

            ###___DERTERMINONS LE NOMBRE DE FACTURE ASSOCIEE A CETTE MAISON
            foreach ($locations as $location) {
                if ($house_last_state) {
                    ###___quand il y a arrêt d'etat
                    ###__on recupere les factures du dernier arrêt des etats de la maison
                    $last_state_date = $house_last_state->created_at;
                    $now = now();

                    $location_factures = Facture::where(["location" => $location->id, "state_facture" => 0])->whereBetween("created_at", [$last_state_date, $now])->get();
                } else {
                    ###___s'il n'y a pas de dernier état, on prends en compte toutes les factures de la maison
                    $location_factures = $location->Factures;
                }

                foreach ($location_factures as $facture) {
                    array_push($house_factures_nbr_array, $facture);
                    array_push($house_amount_nbr_array, $facture->amount);
                }
            }

            // return $location_factures;

            ###__ le nombre de mois payé revient au nombre de factures generées
            $nbr_month_paid = count($house_factures_nbr_array);

            ###__ le montant total payé revient à la somme totale des montants des factures generées
            $total_amount_paid = array_sum($house_amount_nbr_array);

            ####___last depenses
            $last_state_depenses_array = [];
            $last_state_depenses = [];
            if ($house_last_state) {
                $last_state_depenses = $house_last_state->CdrAccountSolds;
            }
            foreach ($last_state_depenses as $depense) {
                array_push($last_state_depenses_array, $depense->sold_retrieved);
            }


            ###___current depenses
            $current_state_depenses_array = [];
            $current_state_depenses = $house->CurrentDepenses;
            foreach ($current_state_depenses as $depense) {
                array_push($current_state_depenses_array, $depense->sold_retrieved);
            }

            ###__
            $house["last_depenses"] = array_sum($last_state_depenses_array);
            $house["actuel_depenses"] = array_sum($current_state_depenses_array);
            $house["total_amount_paid"] = $total_amount_paid;
            $house["nbr_month_paid"] = $nbr_month_paid;
            $house["house_last_state"] = $house_last_state;
            $house["net_to_paid"] = $house["total_amount_paid"] - $house["actuel_depenses"];

            $house["last_payement_initiation"] = $house_last_state ? ($house_last_state->PaiementInitiations ? $house_last_state->PaiementInitiations->last() : []) : [];
        }

        return self::sendResponse($houses, 'Toutes les maisons récupérées avec succès!!');
    }

    static function getAgencyHouses($agencyId)
    {
        $agency_houses_array = [];
        $agency = Agency::where(["visible" => 1])->find($agencyId);
        if (!$agency) {
            return self::sendError("Désolé! Cette agence n'existe pas", 404);
        }

        $houses = House::where(["visible" => 1])->with(["Owner", "Proprietor", "Type", "Supervisor", "City", "Country", "Departement", "Quartier", "Zone", "Rooms", "Locations", "States", "AllStatesDepenses", "PayementInitiations"])->get();

        foreach ($houses as $house) {
            if ($house->Proprietor->Agency->id == $agencyId) {
                $nbr_month_paid = 0;
                $total_amount_paid = 0;

                $house_factures_nbr_array = [];
                $house_amount_nbr_array = [];

                ####_____DERNIER ETAT DE CETTE MAISON
                $house_last_state = $house->States->last();

                $locations = $house->Locations;

                ###___DERTERMINONS LE NOMBRE DE FACTURE ASSOCIEE A CETTE MAISON
                foreach ($locations as $location) {
                    if ($house_last_state) {
                        ###___quand il y a arrêt d'etat
                        ###__on recupere les factures du dernier arrêt des etats de la maison
                        $last_state_date = $house_last_state->created_at;
                        $now = now();

                        $location_factures = Facture::where(["location" => $location->id, "state_facture" => 0])->whereBetween("created_at", [$last_state_date, $now])->get();
                    } else {
                        ###___s'il n'y a pas de dernier état, on prends en compte toutes les factures de la maison
                        $location_factures = $location->Factures;
                    }

                    foreach ($location_factures as $facture) {
                        array_push($house_factures_nbr_array, $facture);
                        array_push($house_amount_nbr_array, $facture->amount);
                    }
                }

                // return $location_factures;

                ###__ le nombre de mois payé revient au nombre de factures generées
                $nbr_month_paid = count($house_factures_nbr_array);

                ###__ le montant total payé revient à la somme totale des montants des factures generées
                $total_amount_paid = array_sum($house_amount_nbr_array);

                ####___last depenses
                $last_state_depenses_array = [];
                $last_state_depenses = [];
                if ($house_last_state) {
                    $last_state_depenses = $house_last_state->CdrAccountSolds;
                }
                foreach ($last_state_depenses as $depense) {
                    array_push($last_state_depenses_array, $depense->sold_retrieved);
                }


                ###___current depenses
                $current_state_depenses_array = [];
                $current_state_depenses = $house->CurrentDepenses;
                foreach ($current_state_depenses as $depense) {
                    array_push($current_state_depenses_array, $depense->sold_retrieved);
                }

                ###__
                $house["last_depenses"] = array_sum($last_state_depenses_array);
                $house["actuel_depenses"] = array_sum($current_state_depenses_array);
                $house["house_last_state"] = $house_last_state;
                $house["total_amount_paid"] = $total_amount_paid;
                $house["nbr_month_paid"] = $nbr_month_paid;
                $house["net_to_paid"] = $house["total_amount_paid"] - $house["actuel_depenses"];

                ###___
                array_push($agency_houses_array, $house);
            }

            $house["last_payement_initiation"] = $house_last_state ? ($house_last_state->PaiementInitiations ? $house_last_state->PaiementInitiations->last() : []) : [];
        }
        return self::sendResponse($agency_houses_array, 'Toutes les maisons de l\'agence récupérées avec succès!!');
    }

    static function getAgencyHousesForLastState($agencyId)
    {
        $agency_houses_array = [];
        $agency = Agency::where(["visible" => 1])->find($agencyId);
        if (!$agency) {
            return self::sendError("Désolé! Cette agence n'existe pas", 404);
        }

        $houses = House::where(["visible" => 1])->with(["Owner", "Proprietor", "Type", "Supervisor", "City", "Country", "Departement", "Quartier", "Zone", "Rooms", "Locations", "States", "AllStatesDepenses", "PayementInitiations"])->get();

        foreach ($houses as $key => $house) {
            ####_____DERNIER ETAT DE CETTE MAISON
            $house_last_state = $house->States->last();
            if ($house_last_state) {
                if ($house->Proprietor->Agency->id == $agencyId) {
                    $nbr_month_paid = 0;
                    $total_amount_paid = 0;

                    $house_factures_nbr_array = [];
                    $house_amount_nbr_array = [];


                    $locations = $house->Locations;

                    ###___DERTERMINONS LE NOMBRE DE FACTURE ASSOCIEE A CETTE MAISON
                    foreach ($locations as $location) {
                        if ($house_last_state) {
                            ###___quand il y a arrêt d'etat
                            ###__on recupere les factures du dernier arrêt des etats de la maison

                            $location_factures = Facture::where(["location" => $location->id, "state" => $house_last_state->id, "state_facture" => 0])->get();
                        } else {
                            ###___s'il n'y a pas de dernier état, on prends en compte toutes les factures de la maison
                            $location_factures = $location->Factures;
                        }

                        foreach ($location_factures as $facture) {
                            array_push($house_factures_nbr_array, $facture);
                            array_push($house_amount_nbr_array, $facture->amount);
                        }
                    }


                    ###__ le nombre de mois payé revient au nombre de factures generées
                    $nbr_month_paid = count($house_factures_nbr_array);

                    ###__ le montant total payé revient à la somme totale des montants des factures generées
                    $total_amount_paid = array_sum($house_amount_nbr_array);

                    ####___last depenses
                    $last_state_depenses_array = [];
                    $last_state_depenses = [];
                    if ($house_last_state) {
                        $last_state_depenses = $house_last_state->CdrAccountSolds;
                    }
                    foreach ($last_state_depenses as $depense) {
                        array_push($last_state_depenses_array, $depense->sold_retrieved);
                    }


                    ###___current depenses
                    $current_state_depenses_array = [];
                    $current_state_depenses = $house->CurrentDepenses;
                    foreach ($current_state_depenses as $depense) {
                        array_push($current_state_depenses_array, $depense->sold_retrieved);
                    }

                    ###__
                    $house["last_depenses"] = array_sum($last_state_depenses_array);
                    $house["actuel_depenses"] = array_sum($current_state_depenses_array);
                    $house["house_last_state"] = $house_last_state;
                    $house["total_amount_paid"] = $total_amount_paid;
                    $house["nbr_month_paid"] = $nbr_month_paid;
                    $house["commission"] = ($house["total_amount_paid"] * $house->commission_percent) / 100;
                    $house["net_to_paid"] = $house["total_amount_paid"] - ($house["last_depenses"] + $house["commission"]);

                    ###___
                    array_push($agency_houses_array, $house);
                }
            }

            $house["last_payement_initiation"] = $house_last_state ? ($house_last_state->PaiementInitiations ? $house_last_state->PaiementInitiations->last() : []) : [];
        }
        return self::sendResponse($agency_houses_array, 'Toutes les maisons de l\'agence récupérées en considerant leurs derniers etats avec succès!!');
    }

    static function _retrieveHouse($id)
    {
        $user = request()->user();
        $house = House::where(["visible" => 1])->with(["Owner", "Proprietor", "Type", "Supervisor", "City", "Country", "Departement", "Quartier", "Zone", "Rooms", "Locations", "States", "AllStatesDepenses", "PayementInitiations"])->find($id);
        if (!$house) {
            return self::sendError("Cette maison n'existe pas!", 404);
        }

        $nbr_month_paid = 0;
        $total_amount_paid = 0;

        $house_factures_nbr_array = [];
        $house_amount_nbr_array = [];

        ####_____DERNIER ETAT DE CETTE MAISON
        $house_last_state = $house->States->last();

        $locations = $house->Locations;

        ###___DERTERMINONS LE NOMBRE DE FACTURE ASSOCIEE A CETTE MAISON
        foreach ($locations as $location) {
            if ($house_last_state) {
                ###___quand il y a arrêt d'etat
                ###__on recupere les factures du dernier arrêt des etats de la maison
                $last_state_date = $house_last_state->created_at;
                $now = now();

                $location_factures = Facture::where(["location" => $location->id, "state_facture" => 0])->whereBetween("created_at", [$last_state_date, $now])->get();
            } else {
                ###___s'il n'y a pas de dernier état, on prends en compte toutes les factures de la maison
                $location_factures = $location->Factures;
            }

            foreach ($location_factures as $facture) {
                array_push($house_factures_nbr_array, $facture);
                array_push($house_amount_nbr_array, $facture->amount);
            }

            ####_____REFORMATION DU LOCATAIRE DE CETTE LOCATION
            ###____
            $houses = $location->House;
            $rooms = $location->Room;

            $nbr_month_paid_array = [];
            $nbr_facture_amount_paid_array = [];
            ####___________

            $location_states = $location->House->States;

            ####==== les factures du dernier etat =======######
            // if (count($location_states) != 0) {
            //     ###___on recupère les factures du dernier état de la maison.
            //     $location_last_state = $location->House->States->last();
            //     $location_last_state_factures = $location_last_state->Factures;

            //     ###___recuperons la dernière facture de cet etat dans toute la table
            //     $last_facture_in_factures_table = $location_last_state->AllFactures->last();

            //     // return $last_facture_in_factures_table;
            //     if (!$last_facture_in_factures_table->state_facture) {
            //         ####___ s'il ne s'agit pas de la dernière facture d'arrêt d'etat
            //         ####_____
            //         foreach ($location_last_state_factures as $facture) {
            //             array_push($nbr_month_paid_array, $facture);
            //             array_push($nbr_facture_amount_paid_array, $facture->amount);
            //         }
            //     }

            //     ###______
            // } else {

            // }

            ########===========     ====================####

            ###__s'il n'y a pas d'état, on tient compte de tout les factures
            ##___liées à cette location
            foreach ($location->Factures as $facture) {
                array_push($nbr_month_paid_array, $facture);
                array_push($nbr_facture_amount_paid_array, $facture->amount);
            }

            ####_____
            $locataire["nbr_month_paid_array"] = count($nbr_month_paid_array);
            $locataire["nbr_facture_amount_paid_array"] = array_sum($nbr_facture_amount_paid_array);
            ####____

            $locataire["houses"] = $houses;
            $locataire["rooms"] = $rooms;
            ####___FIN FORMATION DU LOCATAIRE

            ###
            $location["_locataire"] = $locataire;
        }

        ###__ le nombre de mois payé revient au nombre de factures generées
        $nbr_month_paid = count($house_factures_nbr_array);

        ###__ le montant total payé revient à la somme totale des montants des factures generées
        $total_amount_paid = array_sum($house_amount_nbr_array);

        ####___last depenses
        $last_state_depenses_array = [];
        $last_state_depenses = [];
        if ($house_last_state) {
            $last_state_depenses = $house_last_state->CdrAccountSolds;
        }
        foreach ($last_state_depenses as $depense) {
            array_push($last_state_depenses_array, $depense->sold_retrieved);
        }

        ###___current depenses
        $current_state_depenses_array = [];
        $current_state_depenses = $house->CurrentDepenses;
        foreach ($current_state_depenses as $depense) {
            array_push($current_state_depenses_array, $depense->sold_retrieved);
        }

        ###__
        $house["last_depenses"] = array_sum($last_state_depenses_array);
        $house["actuel_depenses"] = array_sum($current_state_depenses_array);
        $house["total_amount_paid"] = $total_amount_paid;
        $house["house_last_state"] = $house_last_state;
        $house["nbr_month_paid"] = $nbr_month_paid;
        $house["commission"] = ($house["total_amount_paid"] * $house->commission_percent) / 100;
        ####________

        $house["net_to_paid"] = 0;

        if (count($house->States) != 0) {
            $house_last_state = $house->States->last();
            ###_______on recupere la derniere facture de la table, copnsiderant ce state 
            $house_last_state_facture = $house_last_state->AllFactures->last();

            if (!$house_last_state_facture->state_facture) { ###___c'est pas une facture d'arrêt d'état
                $house["net_to_paid"] = $house["total_amount_paid"] - ($house["last_depenses"] + $house["commission"]);
            }
        } else {
            ###_____
            $house["net_to_paid"] = $house["total_amount_paid"] - ($house["last_depenses"] + $house["commission"]);
        }

        ####____RAJOUTONS LES INFOS DE TAUX DE PERFORMANCE DE LA MAISON
        $creation_date = date("Y/m/d", strtotime($house["created_at"]));
        $creation_time = strtotime($creation_date);
        $first_month_period = strtotime("+1 month", strtotime($creation_date));

        $frees_rooms = [];
        $busy_rooms = [];
        $frees_rooms_at_first_month = [];
        $busy_rooms_at_first_month = [];

        foreach ($house->Rooms as $room) {

            $is_this_room_buzy = false; #cette variable determine si cette chambre est occupée ou pas(elle est occupée lorqu'elle se retrouve dans une location de cette maison)
            ##__parcourons les locations pour voir si cette chambre s'y trouve

            foreach ($house->Locations as $location) {
                if ($location->Room->id == $room->id) {
                    $is_this_room_buzy = true;

                    ###___verifions la période d'entrée de cette chambre en location
                    ###__pour determiner les chambres vide dans le premier mois
                    $location_create_date = strtotime(date("Y/m/d", strtotime($location["created_at"])));
                    ##on verifie si la date de creation de la location est comprise entre le *$creation_time* et le *$first_month_period* de la maison 
                    if ($creation_time < $location_create_date && $location_create_date < $first_month_period) {
                        array_push($busy_rooms_at_first_month, $room);
                    } else {
                        array_push($frees_rooms_at_first_month, $room);
                    }
                }
            }


            ###__
            if ($is_this_room_buzy) { ##__quand la chambre est occupée
                array_push($busy_rooms, $room);
            } else {
                array_push($frees_rooms, $room); ##__quand la chambre est libre
            }
        }

        $house["busy_rooms"] = $busy_rooms;
        $house["frees_rooms"] = $frees_rooms;
        $house["busy_rooms_at_first_month"] = $busy_rooms_at_first_month;
        $house["frees_rooms_at_first_month"] = $frees_rooms_at_first_month;

        $house["last_payement_initiation"] = $house_last_state ? ($house_last_state->PaiementInitiations ? $house_last_state->PaiementInitiations->last() : []) : [];
        return self::sendResponse($house, "Maison récupérée avec succès:!!");
    }

    static function _updateHouse($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $house = House::where(["visible" => 1])->find($id);
        if (!$house) {
            return self::sendError("Cette Maison n'existe pas!", 404);
        };

        ####____TRAITEMENT DU PROPRIETAIRE
        if ($request->get("proprietor")) {
            $proprietor = Proprietor::where(["visible" => 1])->find($request->get("proprietor"));

            if (!$proprietor) {
                return self::sendError("Ce Proprietaire n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU TYPE DE PROPRIETAIRE
        if ($request->get("type")) {
            $type = HouseType::find($request->get("type"));

            if (!$type) {
                return self::sendError("Ce type de proprietaire n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU SUPERVISEUR
        if ($request->get("supervisor")) {
            $user_supervisor = User::find($request->get("supervisor"));

            ##__VERIFIONS SI LE UER_SUPERVISOR DISPOSE VRAIMENT DU ROLE D'UN SUPERVISEUR
            $user_roles = $user_supervisor->roles; ##recuperation des roles de ce user_supervisor
            $is_this_user_supervisor_has_supervisor_role = false; ##cette variable permet de verifier si user_supervisor dispose vraiment du rôle d'un superviseur

            foreach ($user_roles as $user_role) {
                if ($user_role->id == 3) {
                    $is_this_user_supervisor_has_supervisor_role = true;
                }
            }

            if (!$is_this_user_supervisor_has_supervisor_role) {
                return self::sendError("Ce utilisateur choisi comme superviseur ne dispose vraiment pas le rôle d'un superviseur!", 404);
            }
        }

        ####____TRAITEMENT DU CITY
        if ($request->get("city")) {
            $city = City::find($request->get("city"));

            if (!$city) {
                return self::sendError("Cette ville n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU COUNTRY
        if ($request->get("country")) {
            $country = Country::find($request->get("country"));

            if (!$country) {
                return self::sendError("Ce pays n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU DEPARTEMENT
        if ($request->get("departement")) {
            $departement = Departement::find($request->get("departement"));

            if (!$departement) {
                return self::sendError("Ce département n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DU QUARTIER
        if ($request->get("quartier")) {
            $quartier = Quarter::find($request->get("quartier"));

            if (!$quartier) {
                return self::sendError("Ce quartier n'existe pas!", 404);
            }
        }

        ####____TRAITEMENT DE LA ZONE
        if ($request->get("zone")) {
            $zone = Zone::find($request->get("zone"));

            if (!$zone) {
                return self::sendError("Cette zone n'existe pas!", 404);
            }
        }

        $house->update($formData);
        return self::sendResponse($house, 'Cette Maison a été modifiée avec succès!');
    }

    static function houseDelete($id)
    {
        $user = request()->user();
        $house = House::where(["visible" => 1])->find($id);
        if (!$house) {
            return self::sendError("Cette maison n'existe pas!", 404);
        };

        $house->visible = 0;
        $house->delete_at = now();
        $house->save();
        return self::sendResponse($house, 'Cette maison a été supprimée avec succès!');
    }

    static function search($request)
    {

        if (!$request->get("search")) {
            return self::sendError("Le champ **search** est réquis!", 505);
        }
        $search = $request->get("search");

        // search via name
        $result = collect(House::where(["visible" => 1])->with(["Owner", "Proprietor", "Type", "Supervisor", "City", "Country", "Departement", "Quartier", "Zone", "Rooms", "Locations"])->get())->filter(function ($house) use ($search) {
            return Str::contains(strtolower($house['name']), strtolower($search));
        })->all();

        if (count($result) == 0) {
            return self::sendError("Aucun résultat trouvé pour cette recherche", 505);
        }

        // ##__
        return self::sendResponse($result, "Résultat de votre recherche");
    }

    static function _housePerformance($request, $agencyId, $supervisorId, $houseId, $action)
    {
        $formData = $request->all();
        $agency = Agency::with(["_Proprietors"])->where(["visible" => 1])->find($agencyId);

        ####___Toutes les maisons de l'agence
        $new_houses_data = [];

        $all_frees_rooms = [];
        $all_busy_rooms = [];
        $all_frees_rooms_at_first_month = [];
        $all_busy_rooms_at_first_month = [];

        ####___HOUSES
        $houses = [];
        $house = null;

        if ($action == "supervisor") {
            ##__
            $supervisor = User::find($supervisorId);
            if (!$supervisor) {
                return self::sendError("Désolé! Ce superviseur n'existe pas!", 404);
            }

            foreach ($supervisor->SupervisorHouses as $supervisor) {
                array_push($houses, $supervisor);
            }
            ##__

        } elseif ($action == "house") {
            $_house = House::find($houseId);
            if (!$_house) {
                return self::sendError("Désolé! Cette maison n'existe pas!", 404);
            }

            $house = $_house;
        } else {
            ###__reformation des houses de l'agence
            foreach ($agency->_Proprietors as $proprio) {
                if ($proprio->agency == $agencyId) { ##__si le proprio appartient à l'agence
                    $proprio_houses = $proprio->Houses;
                    foreach ($proprio_houses as $house) {
                        if ($action == "supervisor") {
                            if ($house->Supervisor->id == $supervisorId) {
                                ###___on recupère seulement les maison affectées à ce superviseur
                                array_push($agency_houses, $house);
                            }
                        } elseif ($action == "agency") {
                            array_push($houses, $house);
                        }
                    }
                }
            }
            ####________
        }

        ####___traitement des houses
        $houses_type = gettype($houses);

        ####____
        if ($houses_type == "array") {
            ###____quand les *houses* sont un array, on les parcours
            foreach ($houses as $house) {
                $creation_date = date("Y/m/d", strtotime($house["created_at"]));
                $creation_time = strtotime($creation_date);
                $first_month_period = strtotime("+1 month", strtotime($creation_date));

                $frees_rooms = [];
                $busy_rooms = [];
                $busy_rooms_at_first_month = [];
                $frees_rooms_at_first_month = [];

                foreach ($house->Rooms as $room) {
                    $is_this_room_buzy = false; #cette variable determine si cette chambre est occupée ou pas(elle est occupée lorqu'elle se retrouve dans une location de cette maison)
                    ##__parcourons les locations pour voir si cette chambre s'y trouve

                    foreach ($house->Locations as $location) {
                        if ($location->Room->id == $room->id) {
                            $is_this_room_buzy = true;

                            ###___verifions la période d'entrée de cette chambre en location
                            ###__pour determiner les chambres vide dans le premier mois
                            $location_create_date = strtotime(date("Y/m/d", strtotime($location["created_at"])));
                            ##on verifie si la date de creation de la location est inférieure à la date du *$first_month_period*

                            if ($location_create_date < $first_month_period) {

                                array_push($busy_rooms_at_first_month, $room);
                                array_push($all_busy_rooms_at_first_month, $room);
                            } else {
                                array_push($frees_rooms_at_first_month, $room);
                                array_push($all_frees_rooms_at_first_month, $room);
                            }
                        }
                    }

                    ###__
                    if ($is_this_room_buzy) { ##__quand la chambre est occupée
                        array_push($busy_rooms, $room);
                        array_push($all_busy_rooms, $room);
                    } else {
                        array_push($frees_rooms, $room); ##__quand la chambre est libre
                        array_push($all_frees_rooms, $room);
                    }
                }

                $house["busy_rooms"] = $busy_rooms;
                $house["frees_rooms"] = $frees_rooms;
                $house["busy_rooms_at_first_month"] = $busy_rooms_at_first_month;
                $house["frees_rooms_at_first_month"] = $frees_rooms_at_first_month;

                ####____
                array_push($new_houses_data, $house);
            }
        } else {
            $house = $house; ##___les *houses* se resument à une seule *house*
            ####________
            $creation_date = date("Y/m/d", strtotime($house["created_at"]));
            $creation_time = strtotime($creation_date);
            $first_month_period = strtotime("+1 month", strtotime($creation_date));

            $frees_rooms = [];
            $busy_rooms = [];
            $busy_rooms_at_first_month = [];
            $frees_rooms_at_first_month = [];

            foreach ($house->Rooms as $room) {
                $is_this_room_buzy = false; #cette variable determine si cette chambre est occupée ou pas(elle est occupée lorqu'elle se retrouve dans une location de cette maison)
                ##__parcourons les locations pour voir si cette chambre s'y trouve

                foreach ($house->Locations as $location) {
                    if ($location->Room->id == $room->id) {
                        $is_this_room_buzy = true;

                        ###___verifions la période d'entrée de cette chambre en location
                        ###__pour determiner les chambres vide dans le premier mois
                        $location_create_date = strtotime(date("Y/m/d", strtotime($location["created_at"])));
                        ##on verifie si la date de creation de la location est inférieure à la date du *$first_month_period*

                        if ($location_create_date < $first_month_period) {
                            array_push($busy_rooms_at_first_month, $room);
                            array_push($all_busy_rooms_at_first_month, $room);
                        } else {
                            array_push($frees_rooms_at_first_month, $room);
                            array_push($all_frees_rooms_at_first_month, $room);
                        }
                    }
                }

                ###__
                if ($is_this_room_buzy) { ##__quand la chambre est occupée
                    array_push($busy_rooms, $room);
                    array_push($all_busy_rooms, $room);
                } else {
                    array_push($frees_rooms, $room); ##__quand la chambre est libre
                    array_push($all_frees_rooms, $room);
                }
            }

            $house["busy_rooms"] = $busy_rooms;
            $house["frees_rooms"] = $frees_rooms;
            $house["busy_rooms_at_first_month"] = $busy_rooms_at_first_month;
            $house["frees_rooms_at_first_month"] = $frees_rooms_at_first_month;

            ####____
            array_push($new_houses_data, $house);
        }

        ###____
        if ($action == "supervisor") {
            return self::sendResponse($new_houses_data, "Taux de performance de l'agence par superviseur récupére avec succès!");
        } else {
            return self::sendResponse($new_houses_data, "Taux de performance de l'agence récupére avec succès!");
        }
    }

    static function _showHouseState($request, $houseId)
    {
        $house = House::find($houseId);
        if (!$house) {
            return self::sendError("Cette maison n'existe pas!", 404);
        }
        $nbr_month_paid = 0;
        $total_amount_paid = 0;

        $house_factures_nbr_array = [];
        $house_amount_nbr_array = [];

        ####_____DERNIER ETAT DE CETTE MAISON
        $house_last_state = $house->States->last();
        if (!$house_last_state) {
            return self::sendError("Cette mlaison ne dispose d'aucun arrêt d'état", 505);
        }

        $locations = $house->Locations;

        ###___DERTERMINONS LE NOMBRE DE FACTURE ASSOCIEE A CETTE MAISON
        foreach ($locations as $key =>  $location) {
            ###___quand il y a arrêt d'etat
            ###__on recupere les factures du dernier arrêt des etats de la maison
            $location_factures = Facture::where(["location" => $location->id, "state" => $house_last_state->id, "state_facture" => 0])->get();

            foreach ($location_factures as $facture) {
                array_push($house_factures_nbr_array, $facture);
                array_push($house_amount_nbr_array, $facture->amount);
            }

            ####_____REFORMATION DU LOCATAIRE DE CETTE LOCATION
            ###____
            $houses = $location->House;
            $rooms = $location->Room;

            $nbr_month_paid_array = [];
            $nbr_facture_amount_paid_array = [];
            ####___________

            foreach ($location_factures as $facture) {
                array_push($nbr_month_paid_array, $facture);
                array_push($nbr_facture_amount_paid_array, $facture->amount);
            }

            ####_____
            $locataire["nbr_month_paid"] = count($nbr_month_paid_array);
            $locataire["nbr_facture_amount_paid"] = array_sum($nbr_facture_amount_paid_array);
            ####____

            $locataire["houses"] = $houses;
            $locataire["rooms"] = $rooms;
            ####___FIN FORMATION DU LOCATAIRE

            ###
            $location["_locataire"] = $locataire;
        }

        ###__ le nombre de mois payé revient au nombre de factures generées
        $nbr_month_paid = count($house_factures_nbr_array);

        ###__ le montant total payé revient à la somme totale des montants des factures generées

        $total_amount_paid = array_sum($house_amount_nbr_array);

        ####___last depenses
        $last_state_depenses_array = [];
        $last_state_depenses = [];
        if ($house_last_state) {
            $last_state_depenses = $house_last_state->CdrAccountSolds;
        }
        foreach ($last_state_depenses as $depense) {
            array_push($last_state_depenses_array, $depense->sold_retrieved);
        }

        ###___current depenses
        $current_state_depenses_array = [];
        $current_state_depenses = $house->CurrentDepenses;
        foreach ($current_state_depenses as $depense) {
            array_push($current_state_depenses_array, $depense->sold_retrieved);
        }

        ###__
        $house["last_depenses"] = array_sum($last_state_depenses_array);
        $house["actuel_depenses"] = array_sum($current_state_depenses_array);
        $house["total_amount_paid"] = $total_amount_paid;
        $house["house_last_state"] = $house_last_state;
        $house["nbr_month_paid"] = $nbr_month_paid;
        $house["commission"] = ($house["total_amount_paid"] * $house->commission_percent) / 100;
        ####________

        $house["net_to_paid"] = $house["total_amount_paid"] - ($house["last_depenses"] + $house["commission"]);

        ####____RAJOUTONS LES INFOS DE TAUX DE PERFORMANCE DE LA MAISON
        $creation_date = date("Y/m/d", strtotime($house["created_at"]));
        $creation_time = strtotime($creation_date);
        $first_month_period = strtotime("+1 month", strtotime($creation_date));

        $frees_rooms = [];
        $busy_rooms = [];
        $frees_rooms_at_first_month = [];
        $busy_rooms_at_first_month = [];

        foreach ($house->Rooms as $room) {

            $is_this_room_buzy = false; #cette variable determine si cette chambre est occupée ou pas(elle est occupée lorqu'elle se retrouve dans une location de cette maison)
            ##__parcourons les locations pour voir si cette chambre s'y trouve

            foreach ($house->Locations as $location) {
                if ($location->Room->id == $room->id) {
                    $is_this_room_buzy = true;

                    ###___verifions la période d'entrée de cette chambre en location
                    ###__pour determiner les chambres vide dans le premier mois
                    $location_create_date = strtotime(date("Y/m/d", strtotime($location["created_at"])));
                    ##on verifie si la date de creation de la location est comprise entre le *$creation_time* et le *$first_month_period* de la maison 
                    if ($creation_time < $location_create_date && $location_create_date < $first_month_period) {
                        array_push($busy_rooms_at_first_month, $room);
                    } else {
                        array_push($frees_rooms_at_first_month, $room);
                    }
                }
            }


            ###__
            if ($is_this_room_buzy) { ##__quand la chambre est occupée
                array_push($busy_rooms, $room);
            } else {
                array_push($frees_rooms, $room); ##__quand la chambre est libre
            }
        }

        $house["busy_rooms"] = $busy_rooms;
        $house["frees_rooms"] = $frees_rooms;
        $house["busy_rooms_at_first_month"] = $busy_rooms_at_first_month;
        $house["frees_rooms_at_first_month"] = $frees_rooms_at_first_month;

        ###___
        // foreach ($house->Locations as $location) {
        //     dd($location->_locataire["nbr_month_paid"]);
        // }
        ###___
        $state = $house_last_state;

        // dd($house);

        return view("house-state", compact(["house", "state"]));
    }

    function _imprimeHouseLastState($request, $houseId)
    {

        $house = House::find($houseId);

        if (!$house) {
            return self::sendError("Cette maison n'existe pas", 404);
        }

        ###___
        $data["house_state_html_url"] = env("APP_URL") . "/$houseId/show_house_state_html";
        ###__
        return self::sendResponse($data, "Etat du dernier state de la maison imprimés avec succès!");
    }
}
