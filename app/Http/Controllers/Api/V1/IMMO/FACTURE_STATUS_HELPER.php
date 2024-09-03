<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\FactureStatus;

class FACTURE_STATUS_HELPER extends BASE_HELPER
{
    static function getFactureStatus()
    {
        $status =  FactureStatus::orderBy("id", "desc")->get();
        return self::sendResponse($status, 'Tout les status de facture récupérées avec succès!!');
    }

    static function retrieveFactureStatu($id)
    {
        $status = FactureStatus::find($id);
        return self::sendResponse($status, "Status de facture récupéré avec succès!!");
    }
}
