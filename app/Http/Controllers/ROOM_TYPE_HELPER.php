<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\RoomType;
use Illuminate\Support\Facades\Validator;

class ROOM_TYPE_HELPER extends BASE_HELPER
{
    ##======== ROOM TYPE VALIDATION =======##
    static function room_type_rules(): array
    {
        return [
            "name" => ["required"],
            "description" => ["required"],
        ];
    }

    static function room_type_messages(): array
    {
        return [
            "name.required" => "Le nom du type de la chambre est réquis!",
            "description.required" => "La description du type de la chambre est réquise!",
        ];
    }

    static function Room_Type_Validator($formDatas)
    {
        $rules = self::room_type_rules();
        $messages = self::room_type_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ####____

    static function _addType($request)
    {
        $formData = $request->all();
        $type = RoomType::create($formData);

        return self::sendResponse($type, "Type de chambre ajouté avec succès!");
    }

    static function getRoomType()
    {
        $types =  RoomType::orderBy("id", "desc")->get();
        return self::sendResponse($types, 'Tout les types de chambre récupérés avec succès!!');
    }

    static function retrieveRoomType($id)
    {
        $type = RoomType::find($id);
        return self::sendResponse($type, "Type de chambre récupéré avec succès!!");
    }
}
