<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\PaiementStatus;

class PAIEMENT_STATUS_HELPER extends BASE_HELPER
{
    static function getPaiementStatus()
    {
        $status =  PaiementStatus::orderBy("id", "desc")->get();
        return self::sendResponse($status, 'Tout les status de paiement récupérés avec succès!!');
    }

    static function retrievePaiementStatus($id)
    {
        $status = PaiementStatus::find($id);
        return self::sendResponse($status, "Status de paiement récupéré avec succès!!");
    }
}
