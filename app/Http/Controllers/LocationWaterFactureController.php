<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AgencyAccount;
use App\Models\AgencyAccountSold;
use App\Models\House;
use App\Models\Location;
use App\Models\LocationElectrictyFacture;
use App\Models\LocationWaterFacture;
use App\Models\Room;
use App\Models\StopHouseElectricityState;
use App\Models\StopHouseWaterState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationWaterFactureController extends Controller
{
    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    function _GenerateWateracture(Request $request)
    {
        $formData = $request->all();
        $user = request()->user();

        ###___TRAITEMENT DES DATAS
        $location = Location::where(["visible" => 1])->find($formData["location"]);
        if (!$location) {
            alert()->error("Echec", "Cette location n'existe pas!");
            return back()->withInput();
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
            alert()->error("Echec", "Désolé! L'index de fin est est inférieur à celui de début");
            return back()->withInput();
        }

        // return $location->Room->unit_price;

        $kilowater_unit_price = $location->Room->unit_price;
        $formData["amount"] = $formData["consomation"] * $kilowater_unit_price;

        $formData["comments"] = "Géneration de facture d'eau pour le locataire << " . $location->Locataire->name . " " . $location->Locataire->prenom . ">> de la maison << " . $location->House->name . " >> à la date " . now() . " par << $user->name >>";

        ###___
        if ($user) {
            $formData["owner"] = $user->id;
        }

        LocationWaterFacture::create($formData);

        alert()->success("Succès", "Facture d'eau géneréé avec succès!!");
        return back()->withInput();
    }

    function _FactureWaterPayement(Request $request, $id)
    {
        $user = request()->user();

        $facture = LocationWaterFacture::where("visible", 1)->find(deCrypId($id));
        if (!$facture) {
            alert()->error("Echec", "Cette facture n'existe pas!");
            return back()->withInput();
        }

        #####____determination de l'agence
        $location = $facture->Location;
        $agency = $location->_Agency;

        ###____MENTIONNONS LA FACTURE COMME payée
        $facture->paid = true;

        ##__
        $agency_account = AgencyAccount::where(["agency" => $agency->id])->find(env("ELECTRICITY_WATER_ACCOUNT_ID"));
        if (!$agency_account) {
            alert()->error("Echec", "Ce compte d'agence n'existe pas! Vous ne pouvez pas le créditer!");
            return back()->withInput();
        }


        $account = $agency_account->_Account;

        $formData["sold_added"] = $facture->amount;

        ###___VERIFIONS LE SOLD ACTUEL DU COMPTE ET VOYONS SI ça DEPPASE OU PAS LE PLAFOND
        $agencyAccountSold = AgencyAccountSold::where(["agency_account" => $agency_account->id, "visible" => 1])->first();

        if ($agencyAccountSold) { ##__Si ce compte dispose déjà d'un sold
            $formData["old_sold"] = $agencyAccountSold->sold;

            ##__voyons si le sold atteint déjà le plafond de ce compte
            if ($agencyAccountSold->sold >= $account->plafond_max) {
                alert()->error("Echec", "Le sold de ce compte (" . $account->name . ") a déjà atteint son plafond! Vous ne pouvez plus le créditer");
                return back()->withInput();
            } else {
                # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
                # ça depasserait le plafond maximum du compte

                if (($agencyAccountSold->sold + $facture->amount) > $account->plafond_max) {
                    alert()->error("Echec", "L'ajout de ce montant au sold de ce compte (" . $account->name . ") dépasserait son plafond! Veuillez diminuer le montant");
                    return back()->withInput();
                }
            }

            ###__creditation proprement dite du compte
            #__Deconsiderons l'ancien sold
            $agencyAccountSold->visible = 0;
            $agencyAccountSold->delete_at = now();
            ####__
            $agencyAccountSold->save();

            #__Construisons un nouveau sold(en se basant sur les datas de l'ancien sold)
            $formData["agency_account"] = $agencyAccountSold->agency_account; ##__ça revient à l'ancien compte
            $formData["sold"] = $agencyAccountSold->sold + $facture->amount;
            $formData["description"] = "Paiement de la facture d'eau de montant (" . $facture->amount . " ) pour la maison " . $location->House->name . " !!";

            AgencyAccountSold::create($formData);
        } else {
            # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
            # ça depasserait le plafond maximum du compte
            $formData["old_sold"] = 0;
            $formData["agency_account"] = $agency_account->id; ##__ça revient à l'ancien compte
            $formData["sold"] = $facture->amount;
            $formData["description"] = "Paiement de la facture d'eau de montant (" . $facture->amount . " ) pour la maison " . $location->House->name . "!!";


            if ($facture->amount > $account->plafond_max) {
                alert()->error("Echec", "L'ajout de ce montant au sold de ce compte (" . $account->name . ") dépasserait son plafond! Veuillez diminuer le montant");
                return back()->withInput();
            }

            # on le crée
            AgencyAccountSold::create($formData);
        }

        ###___MARQUONS QUE LA FACTURE EST PAYEE
        $facture->save();

        #####_______
        alert()->success("Succès", "La facture d'eau de montant (" . $facture->amount . " ) a été payée avec succès!!");
        return back()->withInput();
    }

    ####____ ARRET D'ETAT
    function _StopWaterStatsOfHouse(Request $request)
    {
        #####____
        $user = request()->user();
        $formData = $request->all();

        #####____VALIDATION DES DATAS
        Validator::make($formData, [
            'house' => ['required', "integer"],
        ], [
            'house.required' => 'La maison est réquise!',
            'house.integer' => "Ce champ doit être de type entier!",
        ])->validate();


        if ($user) {
            $formData["owner"] = $user->id;
        }

        $house = House::where(["visible" => 1])->find($formData["house"]);
        if (!$house) {
            alert()->error("Echec", "Cette maison n'existe pas!");
            return back()->withInput();
        };

        if (count($house->Locations) == 0) {
            alert()->error("Echec", "Cette maison n'appartient à aucune location! Son arrêt d'état ne peut donc être éffectué");
            return back()->withInput();
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
        alert()->success("Succès", "L'état en eau de cette maison a été arrêté avec succès!");
        return back()->withInput();
    }
}
