<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    ###__VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access']);
    }

    ###__GET ALL CITY
    function Cities(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($request->method() != "GET") {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION DE TOUTES LES VILLES

        $cities =  City::with(["country"])->orderBy("id", "desc")->get();

        $data = [
            "status" => true,
            "data" => $cities,
            "message" => "Toutes les villes récupérées avec succès!"
        ];
        return response()->json($data, 200);
    }

    ###__GET A CITY
    function _RetrieveCity(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($request->method() != "GET") {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION D'UNE VILLE
        // return $this->retrieveCity($id);

        $city =  City::with(["country"])->find($id);

        if (!$city) {
            return response()->json([
                "status" => false,
                "message" => "Cette ville n'existe pas"
            ], 404);
        }

        $data = [
            "status" => true,
            "data" => $city,
            "message" => "Villes récupérée avec succès!"
        ];
        return response()->json($data, 200);
    }
}
