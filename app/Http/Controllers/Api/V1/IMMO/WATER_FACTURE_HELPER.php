<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Location;
use App\Models\LocationWaterFacture;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WATER_FACTURE_HELPER extends BASE_HELPER
{
    ##======== WATER FACTURE VALIDATION =======##

    static function water_factures_rules(): array
    {
        return [
            'location' => ['required', "integer"],
            // 'start_index' => ['required', "numeric"],
            'end_index' => ['required', "numeric"],
        ];
    }

    static function water_factures_messages(): array
    {
        return [
            'location.required' => "Veillez préciser la location!",
            'location.integer' => "La location doit être un entier",

            // 'start_index.required' => "L'index de début est réquis!",
            // 'start_index.numeric' => "L'index de début doit être de type numérique!",

            'end_index.required' => "L'index de fin est réquis!",
            'end_index.numeric' => "L'index de fin doit être de type numérique!",
        ];
    }

    static function Water_Facture_Validator($formDatas)
    {
        $rules = self::water_factures_rules();
        $messages = self::water_factures_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ###___
    static function generateFacture($request)
    {
        $formData = $request->all();
        $user = request()->user();

        ###___TRAITEMENT DES DATAS
        $location = Location::with(["WaterFactures"])->where(["visible" => 1])->find($formData["location"]);
        if (!$location) {
            return self::sendError("Cette location n'existe pas!", 404);
        }

        ####___VOYONS D'ABORD S'IL Y AVAIT UNE FACTURE PRECEDENTE
        $factures = $location->WaterFactures; ## LocationWaterFacture::all();

        ###__En cas d'existance d efacture précedente, l'index de debut
        ###___ de l'actuelle facture revient à l'index de fin de l'ancienne facture
        if (count($factures) != 0) {
            $last_facture = $factures[0];
            $formData["start_index"] = $last_facture->end_index;
        } else {
            ##__dans le cas contraire
            ###___L'index de debut revient à l'index de debut de la chambre liée à cette location
            $formData["start_index"] = $location->Room->water_counter_start_index;
        }

        $formData["consomation"] = $formData["end_index"] - $formData["start_index"];
        if ($formData["consomation"] < 0) {
            return self::sendError("Désolé! L'index de fin est est inférieur à celui de début", 404);
        }

        // return $location->Room->unit_price;

        $kilowater_unit_price = $location->Room->unit_price;
        $formData["amount"] = $formData["consomation"] * $kilowater_unit_price;

        $formData["comments"] = "Géneration de facture d'eau pour le locataire << " . $location->Locataire->name . " " . $location->Locataire->prenom . ">> de la maison << " . $location->House->name . " >> à la date " . now() . " par << $user->name >>";

        ###___
        if ($user) {
            $formData["owner"] = $user->id;
        }

        // return $formData;
        $facture = LocationWaterFacture::create($formData);

        return self::sendResponse($facture, "Facture d'eau géneréé avec succès!!");
    }

    static function getLocationFactures($locationId)
    {
        $user = request()->user();
        $location = Location::where(["visible" => 1])->find($locationId);

        if (!$location) {
            return self::sendError("Désolé! Cette locationn'existe pas!", 404);
        }

        ##___

        $factures = $location->WaterFactures;
        return self::sendResponse($factures, "Toutes les factures d'eau de location récupérées avec succès!!");
    }

    static function _retrieveFacture($id)
    {
        $facture = LocationWaterFacture::with(["Owner", "Location"])->find($id);
        if (!$facture) {
            return self::sendError("Cette facture n'existe pas!", 404);
        }

        return self::sendResponse($facture, "Facture récupérée avec succès:!!");
    }

    static function deleteFacture($id)
    {
        $facture = LocationWaterFacture::find($id);
        if (!$facture) {
            return self::sendError("Cette facture n'existe pas!", 404);
        }

        $facture->delete();
        return self::sendResponse($facture, "Cette facture a été supprimé avec succès!");
    }

    static function facturePayement($id)
    {
        $user = request()->user();
        $facture = LocationWaterFacture::find($id);
        if (!$facture) {
            return self::sendError("Cette facture n'existe pas!", 404);
        }

        #####____determination de l'agence
        $location = $facture->Location;
        $agency = $location->_Agency;

        ###____MENTIONNONS LA FACTURE COMME payée
        $facture->paid = true;
        $facture->save();

        ###___CREDITATION DE LA CAISSE ELECTRICITE-EAU
        $creditateAccountData = [
            'agency' => $agency->id,
            'water_facture' => $facture->id,
            'location' => $facture->Location->id,
            'agency_account' => 9,
            'sold' => $facture->amount,
            'description' => "Encaissement de la facture d'eau pour le locataire << " . $location->Locataire->name . " " . $location->Locataire->prenom . ">> de la maison << " . $location->House->name . " >> à la date " . now() . " par << $user->name >>",
        ];

        return AGENCY_HELPER::creditateAccount($creditateAccountData, true);
    }
}
