<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\CardType;

class CARD_TYPE_HELPER extends BASE_HELPER
{
    static function getCardType()
    {
        $type =  CardType::orderBy("id", "desc")->get();
        return self::sendResponse($type, 'Tout les types de cartes récuperés avec succès!!');
    }

    static function retrieveCardType($id)
    {
        $type = CardType::find($id);
        return self::sendResponse($type, "Type de carte récupéré avec succès!!");
    }
}
