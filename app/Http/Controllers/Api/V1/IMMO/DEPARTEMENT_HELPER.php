<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Departement;

class DEPARTEMENT_HELPER extends BASE_HELPER
{
    static function getDepartements()
    {
        $departements =  Departement::orderBy("id", "desc")->get();
        return self::sendResponse($departements, 'Tout les departements récupérés avec succès!!');
    }

    static function retrieveDepartement($id)
    {
        $departement = Departement::find($id);
        return self::sendResponse($departement, "Departement récupéré avec succès!!");
    }
}
