<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\CounterStatus;

class COUNTER_STATUS_HELPER extends BASE_HELPER
{
    static function getCounterStatus()
    {
        $status =  CounterStatus::orderBy("id", "desc")->get();
        return self::sendResponse($status, 'Tout les status de compteur récupérés avec succès!!');
    }

    static function retrieveCounterStatus($id)
    {
        $status = CounterStatus::find($id);
        return self::sendResponse($status, "Status de compteur récupéré avec succès!!");
    }
}
