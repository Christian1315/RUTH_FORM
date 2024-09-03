<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Models\Facture;
use Illuminate\Http\Request;

class FactureController extends FACTURE_HELPER
{
    ###__VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access']);
    }

    ###__GET ALL FACTURES
    function Factures(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($request->method() != "GET") {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->getFactures();
    }

    ###__GET FACTURE
    function RetrieveFacture(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($request->method() != "GET") {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION D'UNE DE FACTURE
        return $this->_retrieveFacture($id);
    }

    ###__Update status
    function UpdateStatus(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($request->method() != "POST") {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        if (!$request->get("status")) {
            return $this->sendError("Veuillez préciser le status de la facture", 505);
        }

        $facture = Facture::find($id);

        if (!$facture) {
            return $this->sendError("Désolé! Ctte facture n'existe pas!", 505);
        }

        $facture->status = $request->get("status");
        $facture->save();
        return $this->sendResponse($facture, "Status modifié avec succès");


        #RECUPERATION D'UNE DE FACTURE
        return $this->_retrieveFacture($id);
    }

    function SearchFacture(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->search($request);
    }
}
