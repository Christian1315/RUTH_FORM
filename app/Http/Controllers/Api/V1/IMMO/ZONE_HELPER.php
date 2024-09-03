<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\HouseType;
use App\Models\Zone;

class ZONE_HELPER extends BASE_HELPER
{
    static function getZones()
    {
        $zones =  Zone::with(["City"])->orderBy("id", "desc")->get();
        return self::sendResponse($zones, 'Toutes les zones récupérées avec succès!!');
    }

    static function retrieveZone($id)
    {
        $zone = Zone::with(["City"])->find($id);
        return self::sendResponse($zone, "Zone récupérée avec succès!!");
    }
}
