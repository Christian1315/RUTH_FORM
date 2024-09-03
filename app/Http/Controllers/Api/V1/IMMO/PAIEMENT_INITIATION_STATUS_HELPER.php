<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\PaiementInitiationStatus;

class PAIEMENT_INITIATION_STATUS_HELPER extends BASE_HELPER
{
    static function getPaiementInitiationStatus()
    {
        $status =  PaiementInitiationStatus::orderBy("id", "desc")->get();
        return self::sendResponse($status, 'Tout les status d\'initiation de paiement récupérés avec succès!!');
    }

    static function retrievePaiementInitiationStatus($id)
    {
        $status = PaiementInitiationStatus::find($id);
        return self::sendResponse($status, "Status d'initiation de paiement récupéré avec succès!!");
    }
}
