<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Area;

class AREA_HELPER extends BASE_HELPER
{
    static function getAreas()
    {
        $areas =  Area::orderBy("id", "desc")->get();
        return self::sendResponse($areas, 'Tout les territoires récupérés avec succès!!');
    }

    static function retrieveArea($id)
    {
        $area = Area::find($id);
        return self::sendResponse($area, "Territoire récupéré avec succès!!");
    }
}
