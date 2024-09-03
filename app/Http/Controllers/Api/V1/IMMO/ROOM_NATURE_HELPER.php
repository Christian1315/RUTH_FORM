<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\RoomNature;
use Illuminate\Support\Facades\Validator;

class ROOM_NATURE_HELPER extends BASE_HELPER
{
    ##======== ROOM NATURE VALIDATION =======##
    static function room_nature_rules(): array
    {
        return [
            "name" => ["required"],
            "description" => ["required"],
        ];
    }

    static function room_nature_messages(): array
    {
        return [
            "name.required" => "Le nom de la nature de la chambre est réquis!",
            "description.required" => "La description de la nature de la chambre est réquise!",
        ];
    }

    static function Room_Nature_Validator($formDatas)
    {
        $rules = self::room_nature_rules();
        $messages = self::room_nature_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ####____

    static function _addNature($request)
    {
        $formData = $request->all();
        $nature = RoomNature::create($formData);

        return self::sendResponse($nature, "Nature de chambre ajoutée avec succès!");
    }

    #####___

    static function getRoomNature()
    {
        $natures =  RoomNature::orderBy("id", "desc")->get();
        return self::sendResponse($natures, 'Toutes les natures de chambre récupérés avec succès!!');
    }

    static function retrieveRoomNature($id)
    {
        $nature = RoomNature::find($id);
        return self::sendResponse($nature, "Nature de chambre récupérée avec succès!!");
    }
}
