<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\ActivityDomain;
use App\Models\Country;

class ACTIVITY_DOMAIN_HELPER extends BASE_HELPER
{
    static function getActivities()
    {
        $activities =  ActivityDomain::orderBy("id", "desc")->get();
        return self::sendResponse($activities, 'Tout les domaines d\'activités récupérés avec succès!!');
    }

    static function retrieveActivity($id)
    {
        $activitie = ActivityDomain::find($id);
        return self::sendResponse($activitie, "Domaine d'activité récupéré avec succès:!!");
    }
}
