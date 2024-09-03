<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\AccountSold;
use App\Models\ImmoAccount;
use Illuminate\Support\Facades\Validator;

class MANAGE_ACCOUNT_HELPER extends BASE_HELPER
{
    ##======== MANAGE_ACCOUNT VALIDATION =======##
    static function manage_account_rules(): array
    {
        return [
            'sold' => ['required', "integer"],
            'description' => ['required'],
        ];
    }

    static function manage_account_messages(): array
    {
        return [
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

    ###___
    static function creditateAccount($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $formData["owner"] = $user->id;
        $formData["account"] = $id;
        ##__
        $account = ImmoAccount::find($id);
        if (!$account) {
            return self::sendError("Ce compte n'existe pas! Vous ne pouvez pas le créditer!", 404);
        }

        ###___VERIFIONS LE SOLD ACTUEL DU COMPTE ET VOYONS SI ça DEPPASE OU PAS LE PLAFOND
        $accountSold = AccountSold::where(["account" => $id, "visible" => 1])->first();

        if ($accountSold) { ##__Si ce compte dispose déjà d'un sold

            ##__voyons si le sold atteint déjà le plafond de ce compte
            if ($accountSold->sold >= $account->plafond_max) {
                return self::sendError("Le sold de ce compte (" . $account->name . ") a déjà atteint son plafond! Vous ne pouvez plus le créditer", 505);
            } else {
                # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
                # ça depasserait le plafond maximum du compte
                if (($accountSold->sold + $formData["sold"]) > $account->plafond_max) {
                    return self::sendError("L'ajout de ce montant au sold de ce compte (" . $account->name . ") dépasserait son plafond! Veuillez diminuer le montant", 505);
                }
            }

            ###__creditation proprement dite du compte
            #__Deconsiderons l'ancien sold
            $accountSold->visible = 0;
            $accountSold->delete_at = now();
            $accountSold->save();

            #__Construisons un nouveau sold(en se basant sur les datas de l'ancien sold)
            $formData["account"] = $accountSold->account; ##__ça revient à l'ancien compte
            $formData["sold"] = $accountSold->sold + $formData["sold"];

            $accountSold = AccountSold::create($formData);
        } else {
            # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
            # ça depasserait le plafond maximum du compte
            if ($formData["sold"] > $account->plafond_max) {
                return self::sendError("L'ajout de ce montant au sold de ce compte (" . $account->name . ") dépasserait son plafond! Veuillez diminuer le montant", 505);
            }

            # on le crée
            $accountSold = AccountSold::create($formData);
        }

        return self::sendResponse($accountSold, "Le compte (" . $account->name ." (". $account->description .") " .") a été crédité  avec succès!!");
    }

    static function getAccountSolds($id)
    {
        $user = request()->user();
        $account = ImmoAccount::find($id);
        $accountSolds = AccountSold::with(["Manager", "Account"])->where(["account" => $id])->get();

        return self::sendResponse($accountSolds, "Tout les soldes du compte (" . $account->name . ") récupéres avec succès!");
    }

    static function _retrieveSold($id)
    {
        $accountSold = AccountSold::with(["Manager", "Account"])->find($id);
        return self::sendResponse($accountSold, "Solde récupéré avec succès:!!");
    }

    static function _getAllSols($request)
    {
        $solds = AccountSold::with(["Manager", "Account"])->orderBy("id", "desc")->get();
        return self::sendResponse($solds, "Soldes récupérés avec succès:!!");
    }
}
