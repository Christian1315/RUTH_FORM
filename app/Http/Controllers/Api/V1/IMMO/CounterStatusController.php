<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use Illuminate\Http\Request;

class CounterStatusController extends COUNTER_STATUS_HELPER
{
    ###__VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access']);
    }

    ###__GET ALL COUNTER STATUS
    function CounterStatus(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($request->method() != "GET") {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->getCounterStatus();
    }

    ###__GET A COUNTER STATUS
    function _RetrieveCounterStatus(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($request->method() != "GET") {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION D'UN STATUS DE COUMPTEUR
        return $this->retrieveCounterStatus($id);
    }
}
