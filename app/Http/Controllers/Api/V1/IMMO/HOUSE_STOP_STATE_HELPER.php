<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\AgencyAccountSold;
use App\Models\Facture;
use App\Models\HomeStopState;
use App\Models\House;
use Illuminate\Support\Facades\Validator;

class HOUSE_STOP_STATE_HELPER extends BASE_HELPER
{
    ##======== HOUSE STOP STATE VALIDATION =======##

    static function stop_state_rules(): array
    {
        return [
            'house' => ['required', "integer"],
            'recovery_rapport' => ['required'],
        ];
    }

    static function stop_state_messages(): array
    {
        return [
            'house.required' => 'La maison est réquise!',
            'recovery_rapport.required' => "Veuillez préciser un rapport de recouvrement",
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

        $house = House::with(["Locations", "Proprietor"])->where(["visible" => 1])->find($formData["house"]);
        if (!$house) {
            return self::sendError("Cette maison n'existe pas!", 404);
        };

        if (count($house->Locations) == 0) {
            return self::sendError("Cette maison n'appartient à aucune location! Son arrêt d'état ne peut donc être éffectué", 505);
        }

        ###_____VERIFIONS D'ABORD SI CETTE HOUSE DISPOSAIT DEJA D'UN ETAT
        $this_house_state = HomeStopState::orderBy("id", "desc")->where(["house" => $formData["house"]])->get();

        if (count($this_house_state) == 0) { ##Si cette maison ne dispose pas d'arrêt d'etat
            ##__ON CREE SON PREMIER ARRET D'ETAT
            $data["owner"] = $formData["owner"];
            $data["house"] = $formData["house"];
            $data["recovery_rapport"] = $formData["recovery_rapport"];

            $data["stats_stoped_day"] = now();
            $state = HomeStopState::create($data);
        } else { ##S'il dispose d'un arret d'etat déjà
            $this_house_state = $this_house_state[0];
            ##__On verifie si la date d'aujourd'hui atteint ou depasse
            ##__la date de l'arret precedent + 20 jours
            $precedent_arret_date = strtotime($this_house_state->stats_stoped_day);
            $now = strtotime(now());
            $twenty_days = 5 * 24 * 3600;

            // if ($now < ($precedent_arret_date + $twenty_days)) {
            //     return self::sendError("La précedente date d'arrêt des états de cette maison ne depasse pas encore 5 jours! Vous ne pouvez donc pas éffectuer un nouveau arrêt d'etats pour cette maison pour le moment", 505);
            // }

            ###__ON ARRËTE LES ETATS
            $data["owner"] = $formData["owner"];
            $data["house"] = $formData["house"];
            $data["recovery_rapport"] = $formData["recovery_rapport"];
            $data["stats_stoped_day"] = now();
            $state = HomeStopState::create($data);
        }

        ###____ ACTUALISONS LES STATES DES FACTURES
        foreach ($house->Locations as $location) {
            // ACTUALISONS LES STATES DES FACTURES

            foreach ($location->Factures as $facture) {
                $facture = Facture::find($facture->id);
                if (!$facture->state) {
                    $facture->state = $state->id;
                    $facture->save();
                }
            }

            ###___Génerons une dernière facture pour cette maison pour actualiser les infos de la dernière facture à l'arrêt de cet etat
            $stateFactureData = [
                "owner" => $formData["owner"],
                "location" => $location->id,
                "amount" => 0,
                "state_facture" => 1,
                "state" => $state->id,
            ];

            Facture::create($stateFactureData);

            ####________ACTUALISONS LES MOUVEMENTS DES COMPTES DANS CET ETAT
            $house_account_depenses = $house->AllStatesDepenses;
            foreach ($house_account_depenses as $account) {
                if (!$account->state) {
                    $_res = AgencyAccountSold::find($account->id);
                    $_res->state = $state->id;
                    $_res->save();
                }
            }
        }

        ###____envoie de mail au proprietaire

        try {
            Send_Notification_Via_Mail(
                $house->Proprietor->email,
                "Etat de récouvrement",
                "L'état de récouvrement de la maison " . $house->name . " vient d'être arrêté! Voici un rapport de recouvrement qui l'accompagne :" . $formData["recovery_rapport"]
            );
        } catch (\Throwable $th) {
            //throw $th;
        }

        return self::sendResponse($house, 'L\'état de cette maison a été arrêté avec succès!');
    }

    function _retrieveHouseStates($request, $houseId)
    {
        $house = House::where(["visible" => 1])->find($houseId);
        if (!$house) {
            return self::sendError("Cette maison n'existe pas!", 505);
        }

        $states = HomeStopState::with(["Owner", "House"])->where(["house" => $houseId])->get();
        return self::sendResponse($states, "Tout les états de la maison " . $house->name . " récupérés avec succès!");
    }

    function _retrieveState($request, $id)
    {
        $state = HomeStopState::with(["Owner", "House"])->find($id);
        return self::sendResponse($state, "Etat  récupérés avec succès!");
    }

    function _getAllStates($request)
    {
        $states = HomeStopState::orderBy("id", "desc")->with(["Owner", "House"])->get();
        return self::sendResponse($states, "Tout les etats récupérés avec succès!");
    }
}
