<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Currency;

class CURRENCY_HELPER extends BASE_HELPER
{
    static function getCurrency()
    {
        $types =  Currency::orderBy("id", "desc")->get();
        return self::sendResponse($types, 'Tout les currency récupérées avec succès!!');
    }

    static function retrieveCurrency($id)
    {
        $type = Currency::find($id);
        return self::sendResponse($type, "Currency récupéré avec succès!!");
    }
}
