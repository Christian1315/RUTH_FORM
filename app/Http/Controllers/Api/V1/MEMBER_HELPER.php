<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Member;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class MEMBER_HELPER extends BASE_HELPER
{
    ##======== MEMBER VALIDATION =======##
    static function member_rules(): array
    {
        return [
            'name' => ['required', Rule::unique('members')],
            'phone' => ['required', Rule::unique('users')],
            'img' => ['required'],
        ];
    }

    static function member_messages(): array
    {
        return [
            'name.required' => 'Le name est réquis!',
            'phone.required' => 'Le phone est réquis!',
            'img.required' => 'L\'image est réquis!',
        ];
    }

    static function Member_Validator($formDatas)
    {
        $rules = self::member_rules();
        $messages = self::member_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    static function createMember($request)
    {
        $formData = $request->all();

        #SON ENREGISTREMENT EN TANT QU'UN USER

        $user = request()->user();
        $type = "MEM";

        $username =  Get_Username($user, $type); ##Get_Username est un helper qui genère le **number** 

        ##VERIFIONS SI LE USER EXISTAIT DEJA
        $user = User::where("username", $username)->get();
        if (count($user) != 0) {
            return self::sendError("Ce compte existe déjà!", 404);
        }
        $user = User::where("phone", $formData['phone'])->get();
        if (count($user) != 0) {
            return self::sendError("Ce compte existe déjà!", 404);
        }
        $user = User::where("email", $formData['email'])->get();
        if (count($user) != 0) {
            return self::sendError("Ce compte existe déjà!!", 404);
        }

        $user = request()->user();

        #Detection de l'organisation
        if ($user->is_super_admin) { #S'il sagit d'un super_admin
            $organisation = null;
            $organisationId = null;
        } else { #S'il sagit d'un simple admin
            $organisation = $user->belong_to_organisation; #RECUPEARATION DE L'ORGANISATION A LAQUELLE LE USER(admin ou super_admin) APPARTIENT
            $organisationId = $organisation->id;
        }

        $userData = [
            "name" => $formData['name'],
            "username" => $username,
            "phone" => $formData['phone'],
            "email" => $formData['email'],
            "password" => $username,
            "organisation" => $organisationId,
        ];

        $formData["username"] = $username;
        $user = User::create($userData);

        ##GESTION DES FICHIERS
        $img = $request->file('img');
        $img_name = $img->getClientOriginalName();
        $request->file('img')->move("members", $img_name);

        //REFORMATION DU $formData AVANT SON ENREGISTREMENT DANS LA TABLE **ORGANISATIONS**
        $formData["img"] = asset("members/" . $img_name);

        $this_admin = request()->user()->as_admin; #Recuperation de l'admin associé à ce user

        $member = Member::create($request->all()); #ENREGISTREMENT DE L'ORGANISATION DANS LA DB
        $member->organisation = $organisationId;
        // return $this_admin ? $this_admin->id : null;
        $member->as_user = $user->id;
        $member->owner = request()->user()->id;
        $member->admin = $this_admin ? $this_admin->id : null; #Recuperation de l'ID de l'admin
        $member->img = $formData["img"];
        $member->save();

        #=====ENVOIE D'SMS =======~####
        $message = "Vous avez été ajouté.e à " . $organisation . " entant que membre sur PERFECT_ERP. Voici ci-dessous vos identifiants de connexion: Username:: " . $username . "; Password:: " . $username;

        try {
            Send_SMS(
                $formData['phone'],
                $message,
            );

            ###___
            Send_Notification(
                $user,
                "AJOUTER EN TANT QUE MEMBRE SUR ERP FINANFA",
                $message
            );
        } catch (\Throwable $th) {
            //throw $th;
        }

        return self::sendResponse($member, 'Member crée avec succès!!');
    }

    static function getMembers()
    {
        $members =  Member::with(["belong_to_admin", "belong_to_organisation", "teams"])->where(["owner" => request()->user()->id])->orderBy("id", "desc")->get();
        return self::sendResponse($members, 'Tout les membres récupérés avec succès!!');
    }

    static function retrieveMembers($id)
    {
        $member = Member::with(["belong_to_admin", "belong_to_organisation", "teams"])->where(['id' => $id, "owner" => request()->user()->id])->get();
        if ($member->count() == 0) {
            return self::sendError("Ce membre n'existe pas!", 404);
        }
        return self::sendResponse($member, "Member récupéré(e) avec succès:!!");
    }

    static function updateMembers($request, $id)
    {
        $formData = $request->all();
        $member = Member::where(['id' => $id, 'owner' => request()->user()->id])->get();
        if ($member->count() == 0) {
            return self::sendError("Ce member n'existe pas!", 404);
        }

        #FILTRAGE POUR EVITER LES DOUBLONS
        if ($request->get("name")) {
            $name = Member::where(['name' => $formData['name'], 'owner' => request()->user()->id])->get();

            if (!count($name) == 0) {
                return self::sendError("Ce name existe déjà!!", 404);
            }
        }

        ##GESTION DES FICHIERS
        if ($request->file("img")) {
            $img = $request->file('img');
            $img_name = $img->getClientOriginalName();
            $request->file('img')->move("members", $img_name);
            //REFORMATION DU $formData AVANT SON ENREGISTREMENT DANS LA TABLE 
            $formData["img"] = asset("members/" . $img_name);
        }
        $member = $member[0];
        $member->update($formData);
        return self::sendResponse($member, "Membre récupéré(e) avec succès:!!");
    }

    static function memberDelete($id)
    {
        $member = Member::where(['id' => $id, 'owner' => request()->user()->id])->get();
        if (count($member) == 0) {
            return self::sendError("Ce membre n'existe pas!", 404);
        };

        #DELETE DU MEMBER
        $member = $member[0];
        $member->delete();

        #DELETE DU USER CORRESPONDANT
        $userId = $member->as_user;
        $user = User::find($userId);
        $user->delete();
        return self::sendResponse($member, 'Ce membre a été supprimé avec succès!');
    }
}
