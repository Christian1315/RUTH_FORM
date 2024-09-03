<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\House;
use App\Models\LocationWaterFacture;
use App\Models\Room;
use App\Models\StopHouseWaterState;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\NumberFormatter;

class WATER_HOUSE_STOP_STATE_HELPER extends BASE_HELPER
{
    ##======== WATER HOUSE STOP STATE VALIDATION =======##
    static function stop_state_rules(): array
    {
        return [
            'house' => ['required', "integer"],
        ];
    }

    static function stop_state_messages(): array
    {
        return [
            'house.required' => 'La maison est réquise!',
            'house.integer' => "Ce champ doit être de type entier!",
        ];
    }

    static function House_Stop_State_Validator($formDatas)
    {
        $rules = self::stop_state_rules();
        $messages = self::stop_state_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ###___
    static function stopStatsOfHouse($request)
    {
        $user = request()->user();
        $formData = $request->all();

        if ($user) {
            $formData["owner"] = $user->id;
        }

        $house = House::with(["Locations"])->where(["visible" => 1])->find($formData["house"]);
        if (!$house) {
            return self::sendError("Cette maison n'existe pas!", 404);
        };

        if (count($house->Locations) == 0) {
            return self::sendError("Cette maison n'appartient à aucune location! Son arrêt d'état ne peut donc être éffectué", 505);
        }

        ###_____VERIFIONS D'ABORD SI CETTE HOUSE DISPOSAIT DEJA D'UN ETAT
        $this_house_state = StopHouseWaterState::orderBy("id", "desc")->where(["house" => $formData["house"]])->first();

        if (!$this_house_state) { ##Si cette maison ne dispose pas d'arrêt d'etat
            ##__ON CREE SON PREMIER ARRET D'ETAT
            $data["house"] = $formData["house"];
            $data["owner"] = $formData["owner"];
            $data["state_stoped_day"] = now();
            $state = StopHouseWaterState::create($data);
        } else {
            ##S'il dispose d'un arret d'etat déjà
            ##__On verifie si la date d'aujourd'hui atteint ou depasse
            ##__la date de l'arret precedent + 20 jours

            $precedent_arret_date = strtotime($this_house_state->state_stoped_day);
            $now = strtotime(now());
            $twenty_days = 5 * 24 * 3600;

            // if ($now < ($precedent_arret_date + $twenty_days)) {
            //     return self::sendError("La précedente date d'arrêt des états de cette maison ne depasse pas encore 5 jours! Vous ne pouvez donc pas éffectuer un nouveau arrêt d'etats pour cette maison pour le moment", 505);
            // }

            ###__ON ARRËTE LES ETATS
            $data["owner"] = $formData["owner"];
            $data["house"] = $formData["house"];
            $data["state_stoped_day"] = now();
            $state =  StopHouseWaterState::create($data);
        }

        ###____ ACTUALISONS LES STATES DES FACTURES

        foreach ($house->Locations as $location) {
            $location_factures = $location->WaterFactures;
            $location_room = Room::find($location->Room->id);

            // ACTUALISONS LES STATES DES FACTURES
            foreach ($location_factures as $facture) {
                $electricty_facture = LocationWaterFacture::find($facture->id);
                if (!$electricty_facture->state) {
                    $electricty_facture->state = $state->id;
                    $electricty_facture->save();
                }
            }

            // ACTUALISONS LES INDEX DE DEBUT EN ELECTRICITE DE CHAQUE CHAMBRE DE LA MAISON
            if (count($location_factures) != 0) {
                ###__dernière facture de la location à l'arrêt de cet état
                $last_facture = $location_factures[0];

                ###___l'index de fin de la chambre revient désormais à
                ###___ celui de la dernière facture à l'arrêt de cet état
                $location_room->water_counter_start_index = $last_facture->end_index;
                $location_room->save();
            }

            ###___Génerons une dernière facture pour cette maison pour actualiser les infos de la dernière facture à l'arrêt de cet etat
            $stateFactureData = [
                "owner" => $user->id,
                "location" => $location->id,
                "end_index" => $location_room->water_counter_start_index,
                "amount" => 0,
                "state_facture" => 1,
                "state" => $state->id,
            ];

            LocationWaterFacture::create($stateFactureData);
        }

        ####___
        return self::sendResponse($house, 'L\'état en eau de cette maison a été arrêté avec succès!');
    }

    function _retrieveHouseStates($request, $houseId)
    {
        $house = House::where(["visible" => 1])->find($houseId);
        if (!$house) {
            return self::sendError("Cette maison n'existe pas!", 505);
        }

        $states = StopHouseWaterState::with(["Owner", "House", "StatesFactures"])->where(["house" => $houseId])->get();
        return self::sendResponse($states, "Tout les états de la maison " . $house->name . " récupérés avec succès!");
    }

    function _imprimeWaterHouseState($stateId)
    {
        $state = StopHouseWaterState::with(["House", "StatesFactures"])->find($stateId);

        if (!$state) {
            return self::sendError("Cet état n'existe pas", 404);
        }
        ###___
        $data["state_html_url"] = env("APP_URL") . "/$stateId/show_water_state_html";
        ###__
        return self::sendResponse($data, "Etats generées en pdf avec succès!");
    }

    function _showStateHtml($stateId)
    {
        $state = StopHouseWaterState::with(["House", "StatesFactures"])->find($stateId);

        if (!$state) {
            return self::sendError("Cet état n'existe pas", 404);
        }

        #####_______
        $factures_array = [];
        $factures_paid_array = [];
        $factures_umpaid_array = [];

        foreach ($state->StatesFactures as $facture) {
            if (!$facture->state_facture) {
                if ($facture->paid) {
                    array_push($factures_paid_array, $facture->amount);
                }else {
                    array_push($factures_umpaid_array, $facture->amount);
                }

                ####______
                array_push($factures_array, $facture->amount);
            }
        }

        ####___
        $factures_sum = array_sum($factures_array);
        $paid_factures_sum = array_sum($factures_paid_array);
        $umpaid_factures_sum = array_sum($factures_umpaid_array);

        return view("water-state", compact(["state", "factures_sum", "paid_factures_sum","umpaid_factures_sum"]));
    }

    function _retrieveState($request, $id)
    {
        $state = StopHouseWaterState::with(["Owner", "House", "StatesFactures"])->find($id);
        return self::sendResponse($state, "Etat  récupérés avec succès!");
    }

    function _getAllStates($request)
    {
        $states = StopHouseWaterState::orderBy("id", "desc")->with(["Owner", "House", "StatesFactures"])->get();
        return self::sendResponse($states, "Tout les etats récupérés avec succès!");
    }
}
