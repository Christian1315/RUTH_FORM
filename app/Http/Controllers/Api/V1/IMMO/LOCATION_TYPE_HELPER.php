<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\LocationType;
use Illuminate\Support\Facades\Validator;

class LOCATION_TYPE_HELPER extends BASE_HELPER
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
            "name.required" => "Le nom du type de la location est réquis!",
            "description.required" => "La description du type de la location est réquise!",
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
        $type = LocationType::create($formData);

        return self::sendResponse($type, "Type de location ajouté avec succès!");
    }


    static function getLocationTypes()
    {
        $types =  LocationType::orderBy("id", "desc")->get();
        return self::sendResponse($types, 'Tout les types de location récupérés avec succès!!');
    }

    static function retrieveLocationType($id)
    {
        $type = LocationType::find($id);
        return self::sendResponse($type, "Type de location récupéré avec succès!!");
    }
}
