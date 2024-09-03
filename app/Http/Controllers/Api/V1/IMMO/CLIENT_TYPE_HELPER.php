<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\ClientType;

class CLIENT_TYPE_HELPER extends BASE_HELPER
{
    static function getClientTypes()
    {
        $type =  ClientType::orderBy("id", "desc")->get();
        return self::sendResponse($type, 'Tout les types de clients récuperés avec succès!!');
    }

    static function retrieveClientType($id)
    {
        $type = ClientType::find($id);
        return self::sendResponse($type, "Type de client récupéré avec succès!!");
    }
}
