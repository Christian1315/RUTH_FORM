<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\PaiementModule;

class PAIEMENT_MODULE_HELPER extends BASE_HELPER
{
    static function getPaiementModules()
    {
        $Module =  PaiementModule::orderBy("id", "desc")->get();
        return self::sendResponse($Module, 'Tout les Modules de paiement récupérés avec succès!!');
    }

    static function retrievePaiementModule($id)
    {
        $Module = PaiementModule::find($id);
        return self::sendResponse($Module, "Module de paiement récupéré avec succès!!");
    }
}
