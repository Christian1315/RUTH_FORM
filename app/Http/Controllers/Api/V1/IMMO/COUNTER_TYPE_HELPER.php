<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\CounterType;

class COUNTER_TYPE_HELPER extends BASE_HELPER
{
    static function getCounterTypes()
    {
        $types =  CounterType::orderBy("id", "desc")->get();
        return self::sendResponse($types, 'Tout les types de compteur récupérés avec succès!!');
    }

    static function retrieveCounterType($id)
    {
        $type = CounterType::find($id);
        return self::sendResponse($type, "Type de compteur récupéré avec succès!!");
    }
}
