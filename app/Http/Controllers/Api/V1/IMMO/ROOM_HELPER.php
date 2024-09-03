<?php

namespace App\Http\Controllers\Api\V1\IMMO;

use App\Http\Controllers\Api\V1\BASE_HELPER;
use App\Models\House;
use App\Models\Room;
use App\Models\RoomNature;
use App\Models\RoomType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ROOM_HELPER extends BASE_HELPER
{
    ##======== ROOM VALIDATION =======##
    static function room_rules(): array
    {
        return [
            "house" => ["required", "integer"],
            "nature" => ["required", "integer"],
            "type" => ["required", "integer"],
            "loyer" => ["required", "numeric"],
            "number" => ["required"],
            "comments" => ["required"],

            "gardiennage" => ["required", "numeric"],
            "rubbish" => ["required", "numeric"],
            "vidange" => ["required", "numeric"],
            "cleaning" => ["required", "numeric"],

            ##__EAU
            "water" => ["required", "boolean"],
            // "water_discounter" => ["boolean"],
            // "unit_price" => ["numeric"],

            ##__ELECTRICITY
            "electricity" => ["required", "boolean"],
        ];
    }

    static function room_messages(): array
    {
        return [
            "house.required" => "Veuillez préciser la maison!",
            "nature.required" => "Veuillez préciser la nature de la chambre!",
            "type.required" => "Veuillez préciser le type de la chambre!",

            "house.integer" => "Ce champ doit être de type entier!",
            "nature.integer" => "Ce champ doit être de type entier!",
            "type.integer" => "Ce champ doit être de type entier!",

            "loyer.required" => "Ce champs est réquis",
            "loyer.numeric" => "Ce Champ doit être de type numérique!",

            "number.required" => "Ce champ est réquis",
            "comments.required" => "Ce champ est réquis",

            "gardiennage.required" => "Ce Champ est réquis!",

            "rubbish.required" => "Ce Champ est réquis!",
            "vidange.required" => "Ce Champ est réquis!",
            "cleaning.required" => "Ce Champ est réquis!",

            "gardiennage.numeric" => "Ce Champ doit être de type numérique!",

            "rubbish.numeric" => "Ce Champ doit être de type numérique!",
            "vidange.numeric" => "Ce Champ doit être de type numérique!",
            "cleaning.numeric" => "Ce Champ doit être de type numérique!",

            "publish.required" => "Ce Champ est réquis!",
            "home_banner.boolean" => "Ce Champ est un booléen!",

            // "photo.required" => "La photo de la chambre est réquise!",
            // "photo.file" => "La photo doit être un fichier",

            ##__EAU
            "water.required" => "Ce Champ est réquis!",
            "water.boolean" => "Ce Champ est un booléen!",
            "unit_price.boolean" => "Ce Champ doit être de type numérique!",


            ###___ELECTRICITY
            "electricity.required" => "Ce Champ est réquis",
            "electricity.boolean" => "Ce Champ est un booléen",
        ];
    }

    static function Room_Validator($formDatas)
    {
        $rules = self::room_rules();
        $messages = self::room_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }


    ##======== WATER VALIDATION =======##
    static function room_water_rules(): array
    {
        return [
            ##__EAU
            "water" => ["required", "boolean"],
            // "water_card_counter" => ["required", "boolean"],
            "water_counter_number" => ["required", "numeric"],
            "water_discounter" => ["required", "boolean"],
            "forage" => ["required", "boolean"],
            // "water_counter_start_index" => ["required", "numeric"],
        ];
    }

    static function room_water_messages(): array
    {
        return [
            "water_card_counter.required" => "Ce Champ est réquis!",
            "water_conventionnal_counter.required" => "Ce Champ est réquis!",
            "water_discounter.required" => "Ce Champ est réquis!",
            "forage.required" => "Ce Champ est réquis!",

            "water_card_counter.boolean" => "Ce Champ est un booléen!",
            "water_conventionnal_counter.boolean" => "Ce Champ est un booléen!",
            "water_discounter.boolean" => "Ce Champ est un booléen!",
            "forage.boolean" => "Ce Champ est un booléen!",

            "water_counter_start_index.required" => "Ce Champ est un réquis!",
            "water_counter_start_index.numeric" => "Ce Champ doit être de type numérique!",

            "water_counter_number.required" => "Ce Champ est un réquis!",
            "water_counter_number.numeric" => "Ce Champ doit être de type numérique!",
        ];
    }

    static function Room_Water_Validator($formDatas)
    {
        $rules = self::room_water_rules();
        $messages = self::room_water_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    // ###### FORAGE VALIDATION #########
    static function forage_rules(): array
    {
        return [
            "forfait_forage" => ["required", "numeric"],
        ];
    }

    static function forage_messages(): array
    {
        return [
            "forfait_forage.required" => "Ce Champ est réquis!",
            "forfait_forage.numeric" => "Ce Champ doit être de type numérique!",
        ];
    }

    static function Forage_Validator($formDatas)
    {
        $rules = self::forage_rules();
        $messages = self::forage_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== CONVENTIONNEL COUNTER VALIDATION =======##
    static function conven_counter_water_rules(): array
    {
        return [
            "water_counter_number" => ["required"],
            "water_counter_start_index" => ["required", "numeric"],
        ];
    }

    static function conven_counter_water_messages(): array
    {
        return [
            "water_counter_number.required" => "Ce Champ est réquis!",
            "water_counter_start_index.required" => "Ce Champ est réquis!",

            // "water_counter_number.numeric" => "Ce Champ est doit être de type numérique!",
            "water_counter_start_index.numeric" => "Ce Champ est doit être de type numérique!",
        ];
    }

    static function Conven_Counter_Validator($formDatas)
    {
        $rules = self::conven_counter_water_rules();
        $messages = self::conven_counter_water_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }


    ##======== DISCONTER WATER VALIDATION =======##
    static function discounter_water_rules(): array
    {
        return [
            "unit_price" => ["required", "numeric"],
        ];
    }

    static function discounter_water_messages(): array
    {
        return [
            "unit_price.required" => "Ce Champ est réquis!",
            "unit_price.numeric" => "Ce Champ est doit être de type numérique!",
        ];
    }

    static function Discounter_Validator($formDatas)
    {
        $rules = self::discounter_water_rules();
        $messages = self::discounter_water_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== ROOM ELECTRICITY VALIDATION =======##
    static function room_electricity_rules(): array
    {
        return [
            "electricity" => ["required", "boolean"],
            "electricity_card_counter" => ["required", "boolean"],
            "electricity_conventionnal_counter" => ["required", "boolean"],
            "electricity_discounter" => ["required", "boolean"],
            "electricity_counter_start_index" => ["required", "numeric"],
            "electricity_counter_number" => ["required", "numeric"],
        ];
    }

    static function room_electricity_messages(): array
    {
        return [
            "electricity_card_counter.required" => "Ce Champ est réquis",
            "electricity_conventionnal_counter.required" => "Ce Champ est réquis",
            "electricity_discounter.required" => "Ce Champ est réquis",
            "electricity_counter_start_index.required" => "Ce Champ est réquis",
            "electricity_counter_number.required" => "Ce Champ est réquis",

            "electricity_card_counter.boolean" => "Ce Champ est un booléen",
            "electricity_conventionnal_counter.boolean" => "Ce Champ est un booléen!",
            "electricity_discounter.boolean" => "Ce Champ est booléen",
            "electricity_counter_start_index.numeric" => "Ce Champ doit être de type numérique!",
            "electricity_counter_number.numeric" => "Ce Champ doit être de type numérique!",
        ];
    }

    static function Room_Electricity_Validator($formDatas)
    {
        $rules = self::room_electricity_rules();
        $messages = self::room_electricity_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== ELECTRICITY DISCOUNTER VALIDATION =======##
    static function electricity_discounter_rules(): array
    {
        return [
            "electricity" => ["required", "boolean"],
            "electricity_unit_price" => ["required", "numeric"],
            "electricity_counter_number" => ["required"],
            "electricity_counter_start_index" => ["required", "numeric"],
        ];
    }

    static function electricity_discounter_messages(): array
    {
        return [
            "electricity.required" => "Ce Champ est réquis",
            "electricity_unit_price.required" => "Ce Champ est réquis",
            "electricity_counter_number.required" => "Ce Champ est réquis",
            "electricity_counter_start_index.required" => "Ce Champ est réquis",

            "electricity.boolean" => "Ce Champ est un booléen",
            "electricity_unit_price.numeric" => "Ce Champ doit être de type numérique",
            "electricity_counter_start_index.numeric" => "Ce Champ doit être de type numérique!",
        ];
    }

    static function Electricity_discounter_Validator($formDatas)
    {
        $rules = self::electricity_discounter_rules();
        $messages = self::electricity_discounter_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ###___
    static function addRoom($request)
    {
        $formData = $request->all();

        $user = request()->user();

        ###____TRAITEMENT DU HOUSE
        $house = House::where(["visible" => 1])->find($formData["house"]);
        if (!$house) {
            return self::sendError("Cette maison n'existe pas!", 404);
        }

        ###____TRAITEMENT DU HOUSE NATURE
        $nature = RoomNature::find($formData["nature"]);
        if (!$nature) {
            return self::sendError("Cette nature de chambre n'existe pas!", 404);
        }

        ###____TRAITEMENT DU HOUSE TYPE
        $type = RoomType::find($formData["type"]);
        if (!$type) {
            return self::sendError("Ce type de chambre n'existe pas!", 404);
        }

        if (!$formData["water"] && !$formData["electricity"]) {
            return self::sendError("Veuillez choisir soit l'eau, soit l'électricité", 505);
        }
        ###___

        if ($formData["water"]) {

            // // ##__ANNULONS LES ELEMENTS LIES A L'ELECTRICITE
            // $formData["electricity_conventionnal_counter"]  = 0;
            // $formData["electricity_discounter"]  = 0;
            // $formData["electricity_counter_start_index"]  = 0;
            // $formData["electricity_counter_number"]  = 0;

            ###____
            if ($request->get("forage")) {
                if ($request->get("forage") == true) {
                    $validator = self::Forage_Validator($formData);
                    if ($validator->fails()) {
                        return self::sendError($validator->errors(), 505);
                    }
                }
            }

            ###____
            if ($request->get("water_conventionnal_counter")) {
                if ($request->get("water_conventionnal_counter") == true) {
                    $validator = self::Conven_Counter_Validator($formData);
                    if ($validator->fails()) {
                        return self::sendError($validator->errors(), 505);
                    }
                }
            }

            ###____
            if ($request->get("water_discounter")) {
                if ($request->get("water_discounter") == true) {
                    $validator = self::Discounter_Validator($formData);
                    if ($validator->fails()) {
                        return self::sendError($validator->errors(), 505);
                    }
                }
            }
        }

        ###____
        if ($formData["electricity"]) {

            if ($request->get("electricity_discounter")) {
                $validator = self::Electricity_discounter_Validator($formData);
                if ($validator->fails()) {
                    return self::sendError($validator->errors(), 505);
                }
            }

            // // ##__ANNULONS LES ELEMENTS LIES A L'EAU
            // $formData["water_discounter"]  = 0;
            // $formData["unit_price"]  = null;
            // $formData["water_card_counter"]  = 0;
            // $formData["water_conventionnal_counter"]  = 0;
            // $formData["water_counter_number"]  =0;
            // $formData["water_counter_start_index"]  = 0;
            // $formData["forage"]  = 0;
            // $formData["forfait_forage"]  = 0;
        }


        ###____TRAITEMENT DE L'IMAGE
        if ($request->file("photo")) {
            $photo = $request->file("photo");
            $photoName = $photo->getClientOriginalName();
            $photo->move("room_images", $photoName);
            $formData["photo"] = asset("room_images/" . $photoName);
        }

        #ENREGISTREMENT DE LA CARTE DANS LA DB
        if (!$request->get("owner")) {
            $formData["owner"] = $user->id;
        }

        $formData["total_amount"] = $formData["loyer"] + $formData["gardiennage"] + $formData["rubbish"] + $formData["vidange"] + $formData["cleaning"];
        // return $formData["total_amount"];

        // return $formData;
        $room = Room::create($formData);

        // return $room;
        // $room = [];
        return self::sendResponse($room, "Chambre ajoutée avec succès!!");
    }

    static function getRooms()
    {
        $user = request()->user();
        $rooms = Room::with(["Owner", "House", "Nature", "Type", "Locations"])->where(["visible" => 1])->get();

        return self::sendResponse($rooms, 'Toutes les chambres récupérées avec succès!!');
    }

    static function _retrieveRoom($id)
    {
        $user = request()->user();
        $room = Room::where(["visible" => 1])->with(["Owner", "House", "Nature", "Type", "Locations"])->find($id);
        if (!$room) {
            return self::sendError("Cette chambre n'existe pas!", 404);
        }

        $locataires = [];
        $thisRoomLocations =  $room->Locations;

        foreach ($thisRoomLocations as $location) {
            array_push($locataires, $location->Locataire);
        }

        $room["locataires"] = $locataires;
        return self::sendResponse($room, "Chmabre récupérée avec succès:!!");
    }

    static function _updateRoom($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $room = Room::where(["visible" => 1])->find($id);
        if (!$room) {
            return self::sendError("Cette Chambre n'existe pas!", 404);
        };

        if ($room->owner != $user->id) {
            return self::sendError("Cette Chambre ne vous appartient pas!", 404);
        }

        ###____TRAITEMENT DU HOUSE
        if ($request->get("house")) {
            $house = House::where(["visible" => 1])->find($request->get("house"));
            if (!$house) {
                return self::sendError("Cette Chambre n'existe pas!", 404);
            }
        }

        ###____TRAITEMENT DU HOUSE NATURE
        if ($request->get("nature")) {
            $nature = RoomNature::find($request->get("nature"));
            if (!$nature) {
                return self::sendError("Cette nature de chambre n'existe pas!", 404);
            }
        }

        ###____TRAITEMENT DU ROOM TYPE
        if ($request->get("type")) {
            $type = RoomType::find($request->get("type"));
            if (!$type) {
                return self::sendError("Ce type de chambre n'existe pas!", 404);
            }
        }

        ###____TRAITEMENT DE L'IMAGE
        if ($request->file("principal_img")) {
            $img = $request->file("principal_img");
            $imgName = $img->getClientOriginalName();
            $img->move("room_images", $imgName);
            $formData["principal_img"] = asset("room_images/" . $imgName);
        }

        #ENREGISTREMENT DE LA CARTE DANS LA DB
        $room->update($formData);
        return self::sendResponse($room, 'Cette Chambre a été modifiée avec succès!');
    }

    static function roomDelete($id)
    {
        // $user = request()->user();
        $room = Room::where(["visible" => 1])->find($id);
        if (!$room) {
            return self::sendError("Cette Chambre n'existe pas!", 404);
        };

        // if (!Is_User_An_Admin($user->id)) {
        //     if ($room->owner != $user->id) {
        //         return self::sendError("Cette Chambre ne vous appartient pas!", 404);
        //     }
        // }

        $room->visible = 0;
        $room->delete_at = now();
        $room->save();
        return self::sendResponse($room, 'Cette Chambre a été supprimée avec succès!');
    }

    static function search($request)
    {

        if (!$request->get("search")) {
            return self::sendError("Le champ **search** est réquis!", 505);
        }
        $search = $request->get("search");

        // search via name
        $result = collect(Room::where(["visible" => 1])->with(["Owner", "House", "Nature", "Type", "Locations"])->get())->filter(function ($room) use ($search) {
            return Str::contains(strtolower($room['number']), strtolower($search));
        })->all();

        if (count($result) == 0) {
            // search via house name
            $result = collect(Room::where(["visible" => 1])->with(["Owner", "House", "Nature", "Type", "Locations"])->get())->filter(function ($room) use ($search) {
                return Str::contains(strtolower($room['House']['name']), strtolower($search));
            })->all();
        }

        if (count($result) == 0) {
            return self::sendError("Aucun résultat trouvé pour cette recherche", 505);
        }

        // ##__
        return self::sendResponse($result, "Résultat de votre recherche");
    }
}
