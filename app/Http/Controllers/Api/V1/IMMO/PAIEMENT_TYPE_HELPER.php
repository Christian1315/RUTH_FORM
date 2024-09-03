<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\PaiementType;

class PAIEMENT_TYPE_HELPER extends BASE_HELPER
{
    static function getPaiementTypes()
    {
        $types =  PaiementType::orderBy("id", "desc")->get();
        return self::sendResponse($types, 'Tout les types de paiement récupérés avec succès!!');
    }

    static function retrievePaiementType($id)
    {
        $type = PaiementType::find($id);
        return self::sendResponse($type, "Type de paiement récupéré avec succès!!");
    }
}
