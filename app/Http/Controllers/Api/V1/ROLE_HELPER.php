<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Validator;

class ROLE_HELPER extends BASE_HELPER
{
    ##======== ROLE VALIDATION =======##

    static function role_rules(): array
    {
        return [
            'label' => ['required'],
            'description' => ['required'],
        ];
    }

    static function role_messages(): array
    {
        return [
            'label.unique' => 'Veuillez préciser le label du rôle',
            'description.required' => 'La descrition du rôle est réquise!',
        ];
    }

    static function Role_Validator($formDatas)
    {
        $rules = self::role_rules();
        $messages = self::role_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }


    ##======== ATTACH VALIDATION =======##
    static function ATTACH_rules(): array
    {
        return [
            'user_id' => 'required',
            'role_id' => 'required',
        ];
    }

    static function ATTACH_messages(): array
    {
        return [
            'user_id.required' => 'Veuillez renseigner l\'id de l\'utilisateur',
            'role_id.required' => 'Veuillez renseigner l\'id du role',
        ];
    }

    static function ATTACH_Validator($formDatas)
    {
        $rules = self::ATTACH_rules();
        $messages = self::ATTACH_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }


    ######_____
    static function _createRole($formData)
    {
        #CREATION DU role
        $user = request()->user();
        $formData["owner"] = $user->id;

        $role = Role::create($formData); #ENREGISTREMENT DU ROLE DANS LA DB
        return self::sendResponse($role, 'Rôle crée avec succès!!');
    }

    static function allRoles()
    {
        $roles =  Role::with(['Owner'])->orderBy('id', 'desc')->get();
        return self::sendResponse($roles, 'Tout les rôles récupérés avec succès!!');
    }

    static function _retrieveRole($id)
    {
        $role = Role::with(['Owner'])->find($id);
        if (!$role) {
            return self::sendError("Ce rôle n'existe pas!", 404);
        }
        return self::sendResponse($role, "Rôle récupéré avec succès:!!");
    }


    static function roleAttach($formData)
    {
        $current_user = request()->user();
        if ($current_user->is_admin) {
            $user = User::where(['id' => $formData['user_id']])->get();
        } else {
            $user = User::where(['id' => $formData['user_id'], 'owner' => $current_user->id])->get();
        }
        if (count($user) == 0) {
            return self::sendError("Ce utilisateur n'existe pas!", 404);
        };

        $role = role::where('id', $formData['role_id'])->get();
        if (count($role) == 0) {
            return self::sendError("Ce role n'existe pas!", 404);
        };

        $is_this_attach_existe = UserRole::where(["user_id" => $formData['user_id'], "role_id" => $formData['role_id']])->first();

        if ($is_this_attach_existe) {
            return self::sendError("Ce user dispose déjà de ce role!", 505);
        }
        ##__

        $user_role = new UserRole();
        $user_role->user_id = $formData['user_id'];
        $user_role->role_id = $formData['role_id'];
        $user_role->save();
        return self::sendResponse([], "User attaché au role avec succès!!");
    }

    static function roleDesAttach($formData)
    {
        $current_user = request()->user();

        if ($current_user->is_admin) {
            $user = User::where(['id' => $formData['user_id']])->get();
        } else {
            $user = User::where(['id' => $formData['user_id'], 'owner' => $current_user->id])->get();
        }
        if (count($user) == 0) {
            return self::sendError("Ce utilisateur n'existe pas!", 404);
        };

        $role = Role::where('id', $formData['role_id'])->get();
        if (count($role) == 0) {
            return self::sendError("Ce role n'existe pas!", 404);
        };

        ###___retrait du role qui lui a été affecté par defaut
        $user_role = UserRole::where(["user_id" => $formData['user_id'], "role_id" => $formData['role_id']])->first();
        if (!$user_role) {
            return self::sendError("Ce user ne dispose pas de ce role!", 505);
        }

        $user_role->delete();
        return self::sendResponse([], "User Dettaché du role avec succès!!");
    }
}
