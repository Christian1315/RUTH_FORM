<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Agency;
use App\Models\AgencyAccount;
use App\Models\AgencyAccountSold;
use App\Models\HomeStopState;
use App\Models\PaiementInitiation;
use App\Models\Proprietor;
use Illuminate\Support\Facades\Validator;

class PAIEMENT_INITIATION_HELPER extends BASE_HELPER
{
    ##======== PAIEMENT INITIATION VALIDATION =======##
    static function paiement_initiation_rules(): array
    {
        return [
            'agency' => ['required', "integer"],
            'house' => ['required', "integer"],
            'state' => ['required', "integer"],
            'proprietor' => ['required', "integer"],
            'amount' => ['required', "numeric"],
        ];
    }

    static function paiement_initiation_messages(): array
    {
        return [
            'agency.required' => "L'agence est réquise!",
            'agency.integer' => "Ce Champ doit être de type entier!",

            'house.required' => "Veuillez préciser la maison!",
            'house.integer' => "Ce Champ doit être de type entier!",

            'state.required' => "Veuillez préciser l'arrêt d'état pour lequel ce paiement doit être éffectué!",
            'state.integer' => "Ce Champ doit être de type entier!",

            'proprietor.required' => 'Le proprietaire est réquis!',
            'proprietor.integer' => "Ce Champ doit être de type entier!",

            'amount.required' => "Le montant à initier est réquis!",
            'amount.numeric' => "Le montant doit être de type numérique",
        ];
    }

    static function Paiement_initiation_Validator($formDatas)
    {
        $rules = self::paiement_initiation_rules();
        $messages = self::paiement_initiation_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ####_______rejet a payement initiation
    static function rejet_paiement_initiation_rules(): array
    {
        return [
            'house' => ['required', "integer"],
            'state' => ['required', "integer"],
            'rejet_comments' => ['required'],
        ];
    }

    static function rejet_paiement_initiation_messages(): array
    {
        return [
            'house.required' => "Veuillez préciser la maison!",
            'house.integer' => "Ce Champ doit être de type entier!",

            'state.required' => "Veuillez préciser l'arrêt d'état pour lequel ce paiement doit être éffectué!",
            'state.integer' => "Ce Champ doit être de type entier!",

            'rejet_comments.required' => "Veuillez préciser la raison du rejet",
        ];
    }

    static function Rejet_Paiement_initiation_Validator($formDatas)
    {
        $rules = self::rejet_paiement_initiation_rules();
        $messages = self::rejet_paiement_initiation_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ###_____
    static function initiatePaiementToProprio($request)
    {
        $formData = $request->all();
        $user = request()->user();

        ###___TRAITEMENT DES DATAS
        $proprietor = Proprietor::with(["Owner", "City", "Country", "TypeCard", "Houses"])->find($formData["proprietor"]);

        if (!$proprietor) {
            return self::sendError("Ce propriétaire n'existe pas!", 404);
        }

        $agency = Agency::find($formData["agency"]);

        if (!$agency) {
            return self::sendError("Cette agence n'existe pas!", 404);
        }

        ###__========== VERIFIONS D'ABORD LA SUFFISANCE DU SOLDE DU COIMPTE LOYER POUR EFFECTUER CETTE INITIATION ===========#

        ###__VOYONS D'ABORD S'IL S'AGIT D'UN CHET COMPTABLE

        ##~~on attaque le compte loyer
        $agencyAccount = AgencyAccount::where(["agency" => $formData["agency"], "account" => 4])->first();

        $accountSold = AgencyAccountSold::where(["agency_account" => $agencyAccount->id, "visible" => 1])->first();
        if (!$accountSold) {
            return self::sendError("Le compte Loyer de cette agence ne dispose pas encore de sold! Veuillez à ce qu'il soit d'abord créditer!", 505);
        }
        if ($accountSold->sold < $formData["amount"]) {
            return self::sendError("Le sold du compte Loyer de cette agence est insuffisant pour faire cette initiation!", 505);
        }

        ###__ENREGISTREMENT DU PAIEMENT DANS LA DB
        $formData["manager"] = $user->id;
        $formData["status"] = 1;
        $formData["comments"] = "Initiation de paiement d'une somme de (" . $formData["amount"] . " ) au proprietaire (" . $proprietor->firstname . " " . $proprietor->lastname . " )";
        $PaiementInitiation = PaiementInitiation::create($formData);

        ###___ACTUALISATION DU STATE DANS L'ARRET DES ETATS
        $state = HomeStopState::where(["house" => $formData["house"]])->find($formData["state"]);
        if ($state) {
            $state->proprietor_paid = 1;
            $state->save();
        }

        ##__
        return self::sendResponse($PaiementInitiation, "Paiement initié au proprietaire (" . $proprietor->firstname . " " . $proprietor->lastname . " ) avec succès!");
    }

    static function getPaiementInitiations()
    {
        $user = request()->user();
        $PaiementInitiations = PaiementInitiation::with(["Manager", "Status", "Proprietor", "House"])->orderBy("id", "desc")->get();

        foreach ($PaiementInitiations as $Paiement) {
            ####_____DERNIER ETAT DE CETTE MAISON
            $house_last_state = $Paiement->House->States->last();

            $Paiement["house_last_state"] = $house_last_state;
        }
        return self::sendResponse($PaiementInitiations, 'Toutes les initiations de paiements récupérés avec succès!!');
    }

    static function _retrievePaiementInitiation($id)
    {
        $user = request()->user();
        $Paiement = PaiementInitiation::with(["Manager", "Status", "Proprietor", "House"])->find($id);

        ####_____DERNIER ETAT DE CETTE MAISON
        $house_last_state = $Paiement->House->States->last();

        $Paiement["house_last_state"] = $house_last_state;
        return self::sendResponse($Paiement, "Initiation de Paiement récupérée avec succès!!");
    }

    static function _validePaiementInitiation($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();

        $PaiementInitiation = PaiementInitiation::find($id);

        ##__Le proprio attaché à cette initiation
        $proprietor = $PaiementInitiation->Proprietor;

        if (!$PaiementInitiation) {
            return self::sendError("Cette initiation de paiement n'existe pas!", 404);
        };

        if ($PaiementInitiation->status == 2) {
            return self::sendError("Cette initiation de paiement a été déjà validée", 505);
        };

        $agencyAccount = AgencyAccount::where(["agency" => $PaiementInitiation->agency, "account" => 4])->first();

        $accountSold = AgencyAccountSold::where(["agency_account" => $agencyAccount->id, "visible" => 1])->first();

        if ($accountSold->sold < $PaiementInitiation->amount) {
            return self::sendError("Le sold du compte Loyer de cette agence est insuffisant pour faire cette initiation!", 505);
        }

        ####____CHANGEMENT DE STATUS SUR 2(validée)
        $PaiementInitiation->status = 2;
        $PaiementInitiation->save();

        ###__DECREDITATION DU SOLD DU COMPTE LOYER
        #~~deconsiderons l'ancien sold
        $accountSold->visible = 0;
        $accountSold->save();

        // #~~Considerons un nouveau sold
        // $data["owner"] = $user->id;
        // $data["account"] = $agencyAccount->account;

        $data["old_sold"] = $accountSold->sold;
        $data["sold_retrieved"] = $PaiementInitiation->amount;

        $data["sold"] = $data["old_sold"] - $data["sold_retrieved"];
        $data["description"] = "Décaissement du sold du compte Loyer pour initiation de paiement au proprietaire (" . $proprietor->firstname . " " . $proprietor->lastname . " )";
        ###___

        $accountSold = AgencyAccountSold::create([
            "agency_account" => $agencyAccount->id,
            "old_sold" => $data["old_sold"],
            "sold_retrieved" =>$data["sold_retrieved"],
            "sold" => $data["sold"],
            "description" => $data["description"],
        ]);

        ##___

        return self::sendResponse($PaiementInitiation, 'Initiation de paiement validée avec succès!');
    }

    function _rejetPayementInitiation($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();

        ####_____
        $PaiementInitiation = PaiementInitiation::find($id);
        if (!$PaiementInitiation) {
            return self::sendError("Cette initiation de paiement n'existe pas!", 404);
        };

        if ($PaiementInitiation->status == 2) {
            return self::sendError("Cette initiation de paiement a été déjà validée, vous ne pouvez la rejeté", 505);
        };

        if ($PaiementInitiation->status == 3) {
            return self::sendError("Cette initiation de paiement a été déjà rejétée, vous ne pouvez la rejeté à nouveau", 505);
        };

        ####____CHANGEMENT DE STATUS SUR 3(rejetée)
        $PaiementInitiation->status = 3;
        $PaiementInitiation->rejet_comments = $formData["rejet_comments"];
        $PaiementInitiation->save();

        ###___ACTUALISATION DU STATE DANS L'ARRET DES ETATS
        $state = HomeStopState::where(["house" => $formData["house"]])->find($formData["state"]);
        if ($state) {
            $state->proprietor_paid = 0;
            $state->save();
        }

        return self::sendResponse($PaiementInitiation, 'Initiation de paiement réjetée avec succès!');
    }
}
