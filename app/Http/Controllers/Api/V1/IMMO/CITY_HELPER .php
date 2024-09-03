<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\City;

class CITY_HELPER extends BASE_HELPER
{
    static function getCities()
    {
        $cities =  City::with(["country"])->orderBy("id", "desc")->get();
        return self::sendResponse($cities, 'Toutes les villes récupérées avec succès!!');
    }

    static function retrieveCity($id)
    {
        $city = City::with(["country"])->find($id);
        return self::sendResponse($city, "Ville récupérée avec succès!!");
    }
}
