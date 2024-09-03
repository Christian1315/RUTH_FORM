<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\HouseType;
use Illuminate\Support\Facades\Validator;

class HOUSE_TYPE_HELPER extends BASE_HELPER
{
    ##======== HOUSE TYPE VALIDATION =======##
    static function house_type_rules(): array
    {
        return [
            "name" => ["required"],
            "description" => ["required"],
        ];
    }

    static function house_type_messages(): array
    {
        return [
            "name.required" => "Le nom du type de la chambre est réquis!",
            "description.required" => "La description du type de la chambre est réquise!",
        ];
    }

    static function House_Type_Validator($formDatas)
    {
        $rules = self::house_type_rules();
        $messages = self::house_type_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ####____

    static function _addType($request)
    {
        $formData = $request->all();
        $type = HouseType::create($formData);

        return self::sendResponse($type, "Type de maison ajouté avec succès!");
    }

    static function getHouseType()
    {
        $types =  HouseType::orderBy("id", "desc")->get();
        return self::sendResponse($types, 'Tout les types de maison récupérées avec succès!!');
    }

    static function retrieveHouseType($id)
    {
        $type = HouseType::find($id);
        return self::sendResponse($type, "Type de maison récupéré avec succès!!");
    }
}
