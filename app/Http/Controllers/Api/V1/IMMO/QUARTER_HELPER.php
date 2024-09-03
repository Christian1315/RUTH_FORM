<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Quarter;
use App\Models\Zone;

class QUARTER_HELPER extends BASE_HELPER
{
    static function getQuarters()
    {
        $quarters =  Quarter::orderBy("id", "desc")->get();
        return self::sendResponse($quarters, 'Tout les quartiers récupérés avec succès!!');
    }

    static function retrieveQuarter($id)
    {
        $quarter = Quarter::find($id);
        return self::sendResponse($quarter, "Quartier récupéré avec succès!!");
    }
}
