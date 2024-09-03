<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\FactureType;

class FACTURE_TYPE_HELPER extends BASE_HELPER
{
    static function getFactureTypes()
    {
        $factures =  FactureType::orderBy("id", "desc")->get();
        return self::sendResponse($factures, 'Tout les types de facture récupérées avec succès!!');
    }

    static function retrieveFactureType($id)
    {
        $facture = FactureType::find($id);
        return self::sendResponse($facture, "Type de facture récupéré avec succès!!");
    }
}
