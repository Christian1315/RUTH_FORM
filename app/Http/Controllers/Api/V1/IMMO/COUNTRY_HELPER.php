<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Country;

class COUNTRY_HELPER extends BASE_HELPER
{
    static function getCountries()
    {
        $countries =  Country::with(["cities"])->orderBy("id", "desc")->get();
        return self::sendResponse($countries, 'Tout les pays récupérés avec succès!!');
    }

    static function retrieveCountry($id)
    {
        $country = Country::with(["cities"])->find($id);
        return self::sendResponse($country, "Pays récupéré avec succès:!!");
    }
}
