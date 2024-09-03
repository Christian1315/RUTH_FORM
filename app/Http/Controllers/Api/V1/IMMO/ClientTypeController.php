<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use Illuminate\Http\Request;

class ClientTypeController extends CLIENT_TYPE_HELPER
{
    ###__VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access']);
    }

    ###__GET ALL CLIENT TYPE
    function ClientTypes(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($request->method() != "GET") {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->getClientTypes();
    }

    ###__GET Client TYPE
    function _RetrieveClientType(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($request->method() != "GET") {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Client_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION D'UN TYPE DE CLIENT
        return $this->retrieveClientType($id);
    }
}
