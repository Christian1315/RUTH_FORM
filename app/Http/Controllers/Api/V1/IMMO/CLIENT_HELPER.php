<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\Client;
use Illuminate\Support\Str;

class CLIENT_HELPER extends BASE_HELPER
{
    static function getClients()
    {
        $user = request()->user();
        $clients = Client::with(["Type", "City"])->where(["visible" => 1])->with(["Type"])->get();
        return self::sendResponse($clients, 'Tout les clients récupérés avec succès!!');
    }

    static function _retrieveClient($id)
    {
        $user = request()->user();
        $client = Client::with(["Type", "City"])->where(["visible" => 1])->with(["Type"])->find($id);

        if (!$client) {
            return self::sendError("Ce client n'existe pas!", 404);
        }
        return self::sendResponse($client, "Client récupéré avec succès:!!");
    }

    static function clientDelete($id)
    {
        $user = request()->user();
        $client = Client::where(["visible" => 1])->find($id);
        if (!$client) {
            return self::sendError("Ce client n'existe pas!", 404);
        };

        $client->visible = 0;
        $client->delete_at = now();
        $client->save();
        return self::sendResponse($client, 'Ce client a été supprimé avec succès!');
    }

    static function search($request)
    {

        if (!$request->get("search")) {
            return self::sendError("Le champ **search** est réquis!", 505);
        }
        $search = $request->get("search");

        $result = collect(Client::with(["Type", "City"])->get())->filter(function($client) use ($search)
        {
            return Str::contains(strtolower($client['name']),strtolower($search));
        })->all();

        if (count($result) == 0) {
            return self::sendError("Aucun résultat trouvé pour cette recherche", 505);
        }

        return self::sendResponse($result, "Résultat de votre recherche");
    }
}
