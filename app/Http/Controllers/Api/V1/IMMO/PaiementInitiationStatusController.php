<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use Illuminate\Http\Request;

class PaiementInitiationStatusController extends PAIEMENT_INITIATION_STATUS_HELPER
{
    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access']);
    }

    #GET ALL PAIEMENT INITIATION STATUS HELPER
    function PaiementInitiationStatus(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->getPaiementInitiationStatus();
    }

    #GET A PAIEMENT INITIATION STATUS HELPER
    function _RetrievePaiementInitiationStatus(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION D'UN STATUS D'INITIATION DE PAIEMENT
        return $this->retrievePaiementInitiationStatus($id);
    }
}
