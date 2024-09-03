<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Agency;
use App\Models\AgentAccountSupervisor;
use App\Models\Right;
use App\Models\User;
use App\Models\UserRight;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class USER_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function register_rules(): array
    {
        return [
            'name' => 'required',
            'username' => 'required',
            'phone' => ['required', 'numeric'],
            'email' => ['required', 'email', Rule::unique('users')],
            'profil' => ['required', 'integer'],
            'rang' => ['required', 'integer'],
            'agency' => ['required', 'integer'],
        ];
    }

    static function register_messages(): array
    {
        return [
            'name.required' => 'Le nom entier de l\'utilisateur est réquis!',
            'username.required' => 'L\'identifiant(uersname) est réquis!',
            'email.required' => 'Le champ Email est réquis!',
            'email.email' => 'Ce champ est un mail!',
            'email.unique' => 'Un compte existe déjà au nom de ce mail!',
            // 'phone.unique' => 'Un compte existe déjà au nom de ce phone',
            'phone.required' => 'Le phone est réquis!',
            'phone.numeric' => 'Le phone doit être de type numéric!',

            'agency.required' => "Veillez préciser l'agence",
            'agency.integer' => "L'agence doit être de type entier!",

            'profil.required' => "Veillez préciser le profil",
            'profil.integer' => "Le profil doit être de type entier!",

            'rang.required' => "Veillez préciser le rang",
            'rang.integer' => "Le rang doit être de type entier!",
        ];
    }

    static function Register_Validator($formDatas)
    {
        $rules = self::register_rules();
        $messages = self::register_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== ATTACH VALIDATION =======##
    static function ATTACH_rules(): array
    {
        return [
            'user_id' => 'required',
            'right_id' => 'required',
        ];
    }

    static function ATTACH_messages(): array
    {
        return [
            'user_id.required' => 'Veuillez renseigner l\'id de l\'utilisateur',
            'password.required' => 'Le champ Password est réquis!',
        ];
    }

    static function ATTACH_Validator($formDatas)
    {
        $rules = self::ATTACH_rules();
        $messages = self::ATTACH_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }


    ##======== LOGIN VALIDATION =======##
    static function login_rules(): array
    {
        return [
            'account' => 'required',
            'password' => 'required',
        ];
    }

    static function login_messages(): array
    {
        return [
            'account.required' => 'Le champ Username est réquis!',
            'password.required' => 'Le champ Password est réquis!',
        ];
    }

    static function Login_Validator($formDatas)
    {
        $rules = self::login_rules();
        $messages = self::login_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== NEW PASSWORD VALIDATION =======##
    static function NEW_PASSWORD_rules(): array
    {
        return [
            'new_password' => 'required',
        ];
    }

    static function NEW_PASSWORD_messages(): array
    {
        return [];
    }

    static function NEW_PASSWORD_Validator($formDatas)
    {
        $rules = self::NEW_PASSWORD_rules();
        $messages = self::NEW_PASSWORD_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== AFFECT SUPERVISOR VALIDATION =======##
    static function affect_supervisor_rules(): array
    {
        return [
            'supervisor' => ['required', 'integer'],
            'agent_account' => ['required', 'integer'],
        ];
    }

    static function affect_supervisor_messages(): array
    {
        return [
            "supervisor.required" => "Veuillez bien préciser le superviseur!",
            "supervisor.integer" => "Ce champ doit être de type entier!",

            "agent_account.required" => "Veuillez bien préciser l'agent comptable!",
            "agent_account.integer" => "Ce champ doit être de type entier!"
        ];
    }

    static function Affect_Supervisor_Validator($formDatas)
    {
        $rules = self::affect_supervisor_rules();
        $messages = self::affect_supervisor_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    #######################################################

    static function createUser($request)
    {
        $formData = $request->all();

        ####____TRAITEMENT DE L'AGENCE
        $agency = Agency::where(["visible" => 1])->find($formData["agency"]);
        if (!$agency) {
            return self::sendError("Cette agence n'existe pas!", 404);
        }

        $user = request()->user();
        $formData['pass_default'] = Custom_Timestamp();
        $formData['password'] = $formData['username'];
        $formData['owner'] = $user->id;
        $formData['rang_id'] = $formData['rang'];
        $formData['profil_id'] = $formData['profil'];

        #ENREGISTREMENT
        $create_user = User::create($formData);

        try {
            Send_Notification(
                $create_user,
                "Création de compte sur Perfect ERP",
                "Votre compte à été crée avec succès sur Perfect ERP. Veuillez utiliser cet identifiant pour vous connecter : " . $formData['username'],
            );
        } catch (\Throwable $th) {
            //throw $th;
        }

        return self::sendResponse($create_user, 'Utilisateur ajouté avec succès!!');
    }

    static function userAuthentification($request)
    {
        if (is_numeric($request->get('account'))) {
            $credentials  =  ['phone' => $request->get('account'), 'password' => $request->get('password')];
        } elseif (filter_var($request->get('account'), FILTER_VALIDATE_EMAIL)) {
            $credentials  =  ['email' => $request->get('account'), 'password' => $request->get('password')];
        } else {
            $credentials  =  ['username' => $request->get('account'), 'password' => $request->get('password')];
        }

        if (Auth::attempt($credentials)) { #SI LE USER EST AUTHENTIFIE
            $user = Auth::user();

            ###___VERIFIONS SI LE CE COMPTE A ETE ARCHIVE
            if ($user->is_archive) {
                return self::sendError("Ce compte a été archivé!", 505);
            };

            ###__
            $token = $user->createToken('MyToken', ['api-access'])->accessToken;
            $user['roles'] = GET_USER_ROLES($user->id);
            $user['token'] = $token;
            $user['rang'] = $user->rang;
            $user['profil'] = $user->profil;
            $user['is_master'] = Is_User_Has_A_Master_Role($user->id);
            $user['is_chief_account'] = Is_User_Has_A_Chief_Accountant_Role($user->id);
            $user['is_agent_account'] = Is_User_Has_An_Agent_Accountant_Role($user->id);
            $user['is_supervisor'] = Is_User_Has_A_Supervisor_Role($user->id);

            if (Is_User_Has_A_Supervisor_Role($user->id)) {
                $user['account_agents'] = $user->account_agents;
            }

            if (Is_User_Has_An_Agent_Accountant_Role($user->id)) {
                $user['supervisor'] = $user->supervisors;
            }

            #renvoie des droits du user 
            $attached_rights = $user->affected_rights; #affected_rights represente les droits associés au user par relation #Les droits attachés

            if ($attached_rights->count() == 0) { #si aucun droit ne lui est attaché
                if (Is_User_AN_ADMIN($user->id)) { #s'il est un admin
                    $user['rights'] = All_Rights();
                } else {
                    $user['rights'] = User_Rights($user->rang['id'], $user->profil['id']);
                }
            } else {
                $user['rights'] = $attached_rights; #Il prend uniquement les droits qui lui sont attachés
            }


            #####______
            return redirect("/dashbord")->with("success", "Vous etes connecté(e) avec succès!!");
        }

        #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER
        return self::sendError('Connexion échouée! Vérifiez vos données puis réessayez à nouveau!', 500);
    }

    static function getUsers()
    {
        $user = request()->user();
        $users =  User::where(["visible" => 1])->with(["_Agency", "as_admin", "my_admins", "belong_to_organisation", "roles", "profil", "rang"])->where(["owner" => $user->id])->orderBy("id", "desc")->get();
        return self::sendResponse($users, 'Touts les utilisatreurs récupérés avec succès!!');
    }

    static function retrieveUsers($id)
    {
        $user = User::where(["visible" => 1])->with(["_Agency", "as_admin", "my_admins", "belong_to_organisation", "roles", "profil", "rang"])->find($id);
        if (!$user) {
            return self::sendError("Ce utilisateur n'existe pas!", 404);
        }
        return self::sendResponse($user, "Utilisateur récupéré(e) avec succès:!!");
    }

    static function _updatePassword($formData, $id)
    {
        $user = User::where(['id' => $id])->get();
        if (count($user) == 0) {
            return self::sendError("Ce utilisateur n'existe pas!", 404);
        };
        $user = User::find($id);
        $user->update(["password" => $formData["new_password"]]);
        return self::sendResponse($user, 'Mot de passe modifié avec succès!');
    }

    static function _updateCompte($request, $id)
    {
        $user = User::find($id);
        if ($user->is_archive) {
            return self::sendError("Ce compte a été archivé!", 404);
        };

        ##__
        if ($request->get("password")) {
            return self::sendResponse($user, 'Vous ne pouvez pas modifier le mot de passe de cette façon!');
        }
        if ($request->get("is_admin")) {
            return self::sendResponse($user, 'Vous ne pouvez pas modifier ce champ (is_admin) de cette façon!');
        }
        if ($request->get("is_super_admin")) {
            return self::sendResponse($user, 'Vous ne pouvez pas modifier ce champ (is_super_admin) de cette façon!');
        }
        if ($request->get("organisation")) {
            return self::sendResponse($user, 'Vous ne pouvez pas modifier ce champ (organisation) de cette façon!');
        }
        if ($request->get("active_compte_code")) {
            return self::sendResponse($user, 'Vous ne pouvez pas modifier ce champ (active_compte_code) de cette façon!');
        }
        if ($request->get("compte_actif")) {
            return self::sendResponse($user, 'Vous ne pouvez pas modifier ce champ (compte_actif) de cette façon!');
        }
        if ($request->get("pass_code")) {
            return self::sendResponse($user, 'Vous ne pouvez pas modifier ce champ (pass_code) de cette façon!');
        }
        if ($request->get("pass_code_active")) {
            return self::sendResponse($user, 'Vous ne pouvez pas modifier ce champ (pass_code_active) de cette façon!');
        }
        // if ($request->get("rang_id ")) {
        //     return self::sendResponse($user, 'Vous ne pouvez pas modifier ce champ (rang_id ) de cette façon!');
        // }
        // if ($request->get("profil_id")) {
        //     return self::sendResponse($user, 'Vous ne pouvez pas modifier ce champ (profil_id) de cette façon!');
        // }
        if ($request->get("is_archive")) {
            return self::sendResponse($user, 'Vous ne pouvez pas modifier ce champ (is_archive) de cette façon!');
        }

        ###___MODIFICATION
        $user->update($request->all());
        ###___
        return self::sendResponse($user, 'Compte modifié avec succès!');
    }

    static function userLogout($request)
    {
        $request->user()->token()->revoke();
        // DELETING ALL TOKENS REMOVED
        // Artisan::call('passport:purge');
        return self::sendResponse([], 'Vous etes déconnecté(e) avec succès!');
    }

    static function rightAttach($formData)
    {
        $current_user = request()->user();
        if ($current_user->is_admin) {
            $user = User::find($formData['user_id']);
        } else {
            $user = User::where(['owner' => $current_user->id])->find($formData['user_id']);
        }
        if (!$user) {
            return self::sendError("Cet utilisateur n'existe pas!", 404);
        };

        $right = Right::find($formData['right_id']);
        if (!$right) {
            return self::sendError("Ce droit n'existe pas!", 404);
        };

        $is_this_attach_existe = UserRight::where(["user_id" => $formData['user_id'], "right_id" => $formData['right_id']])->first();

        if ($is_this_attach_existe) {
            return self::sendError("Cet utilisateur dispose déjà de ce droit!", 505);
        }
        ##__

        ###___VERIFIONS SI CE USER DISPOSE DE CE RANG ET DE CE PROFIL DE DROIT
        if ($right->rang != $user->rang_id) {
            return self::sendError("Cet utlisateur ne dispose pas le rang de ce droit! Vous ne pouvez donc pas le lui affecter!", 505);
        }
        if ($right->profil != $user->profil_id) {
            return self::sendError("Cet utlisateur ne dispose pas le profil de ce droit! Vous ne pouvez donc pas le lui affecter!", 505);
        }

        ####___
        $user_right = new UserRight();
        $user_right->user_id = $formData['user_id'];
        $user_right->right_id = $formData['right_id'];
        $user_right->save();
        return self::sendResponse([], "User attaché au right avec succès!!");
    }

    static function rightDesAttach($formData)
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

        $right = Right::where('id', $formData['right_id'])->get();
        if (count($right) == 0) {
            return self::sendError("Ce right n'existe pas!", 404);
        };

        ###___retrait du droit qui lui a été affecté par defaut
        $user_right = UserRight::where(["user_id" => $formData['user_id'], "right_id" => $formData['user_id']])->first();
        if (!$user_right) {
            return self::sendError("Ce user ne dispose pas de ce droit!", 505);
        }

        $user_right->delete();
        return self::sendResponse([], "User Dettaché du right avec succès!!");
    }

    static function activateAccount($request)
    {
        if (!$request->get("active_compte_code")) {
            return self::sendError("Le Champ **active_compte_code** est réquis", 505);
        }
        $user =  User::where(["active_compte_code" => $request->active_compte_code])->get();
        if ($user->count() == 0) {
            return self::sendError("Ce Code ne corresponds à aucun compte! Veuillez saisir le vrai code", 505);
        }

        $user = $user[0];
        ###VERIFIONS SI LE COMPTE EST ACTIF DEJA
        if ($user->compte_actif) {
            return self::sendError("Ce compte est déjà actif!", 505);
        }

        $user->compte_actif = 1;
        $user->save();
        return self::sendResponse($user, 'Votre compte à été activé avec succès!!');
    }

    static function _demandReinitializePassword($request)
    {
        if (!$request->get("email")) {
            return self::sendError("Le Champ email est réquis!", 404);
        }
        $email = $request->get("email");
        $user = User::where(['email' => $email])->get();

        if (count($user) == 0) {
            return self::sendError("Ce compte n'existe pas!", 404);
        };

        #
        $user = $user[0];
        $pass_code = Get_passCode($user, "PASS");
        $user->pass_code = $pass_code;
        $user->pass_code_active = 1;
        $user->save();

        $message = "Demande de réinitialisation éffectuée avec succès! sur PERFECT ERP! Voici vos informations de réinitialisation de password ::" . $pass_code;

        #=====ENVOIE D'EMAIL =======~####
        try {
            Send_Notification(
                $user,
                "DEMANDE DE REEINITIALISATION DE MOT DE PASSE EFFECTUEE SUR PERFECT ERP",
                $message,
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
        return self::sendResponse($user, "Demande de réinitialisation éffectuée avec succès! Veuillez vous connecter avec le code qui vous a été envoyé par mail");
    }

    static function _reinitializePassword($request)
    {
        $pass_code = $request->get("pass_code");

        if (!$pass_code) {
            return self::sendError("Ce Champ pass_code est réquis!", 404);
        }

        $new_password = $request->get("new_password");

        if (!$new_password) {
            return self::sendError("Ce Champ new_password est réquis!", 404);
        }

        $user = User::where(['pass_code' => $pass_code])->first();
        if (!$user) {
            return self::sendError("Ce code n'est pas correct!", 404);
        };

        #Voyons si le passs_code envoyé par le user est actif

        if ($user->pass_code_active == 0) {
            return self::sendError("Ce Code a déjà été utilisé une fois! Veuillez faire une autre demande de réinitialisation", 404);
        }

        #UPDATE DU PASSWORD
        $user->update(['password' => $new_password]);

        #SIGNALONS QUE CE pass_code EST DEJA UTILISE
        $user->pass_code_active = 0;
        $user->save();


        $message = "Réinitialisation de password éffectuée avec succès sur PERFECT ERP!";

        #=====ENVOIE D'EMAIL =======~####
        try {
            Send_Notification(
                $user,
                "REEINITIALISATION EFFECTUEE SUR PERFECT ERP",
                $message,
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
        return self::sendResponse($user, "Réinitialisation éffectuée avec succès!");
    }

    static function archive_an_account($id)
    {
        $account = User::find($id);
        if (!$account) {
            return self::sendError("Desolé! Ce compte n'existe pas!", 505);
        }


        if ($account->is_archive) {
            return self::sendError("Desolé! Ce compte est déjà archivé!", 505);
        }
        $account->is_archive = true;
        $account->save();

        return self::sendResponse($account, "Ce compte a été archivé avec succès!");
    }

    static function duplicate_an_account($id)
    {
        $account = User::find($id);


        $datas = [
            "owner" => request()->user() ? request()->user()->id : null,
            "name" => $account["name"],
            'username' => $account["username"],
            'email' => $account["email"],
            'password' => $account["password"],
            'organisation' => $account["organisation"],
            "phone" => $account["phone"],
            "is_archive" => 0,
            "profil_id" => $account["profil_id"],
            "rang_id" => $account["rang_id"],
            "pass_code_active" => $account["pass_code_active"],
            "pass_code" => $account["pass_code"],
            "compte_actif" => $account["compte_actif"],
            "active_compte_code" => $account["active_compte_code"],
            "organisation" => $account["organisation"],
            "is_super_admin" => $account["is_super_admin"],
            "is_admin" => $account["is_admin"]
        ];
        $account_duplicated = User::create($datas);

        ###___REAFFECTATION DES DROITS DU COMPTE ARCHIVE AU COMPTE DUPLIQUE
        $user_rights = UserRight::where(["user_id" => $id])->get();


        foreach ($user_rights as $user_right) {
            $user_right->user_id = $account_duplicated->id;
            $user_right->save();
        }

        ###__ARCHIVONS ENSUITE LE COMPTE
        $account->is_archive = true;
        $account->save();
        ###__
        return self::sendResponse($account_duplicated, "Compte dupliqué avec succès!");
    }

    static function _getAllAccountAgents($request)
    {
        $users = User::with(["supervisors"])->get();
        $account_agents = [];

        foreach ($users as $user) {
            ##recuperation des roles de ce user
            $user_roles = $user->roles;

            foreach ($user_roles as $user_role) {
                if ($user_role->id == 4) {
                    array_push($account_agents, $user);
                }
            }
        }

        return self::sendResponse($account_agents, "Tous les agents comptables récupérés avec succès!");
    }

    static function _getAllSupervisors($request)
    {
        $users = User::with(["account_agents"])->get();
        $supervisors = [];

        foreach ($users as $user) {
            $user_roles = $user->roles; ##recuperation des roles de ce user

            foreach ($user_roles as $user_role) {
                if ($user_role->id == 3) {
                    array_push($supervisors, $user);
                }
            }
        }

        return self::sendResponse($supervisors, "Tous les superviseurs récupérés avec succès!");
    }


    static function _affectSupervisorToAccountyAgent($request)
    {
        $formData = $request->all();

        $agent_account = User::find($formData["agent_account"]);
        $supervisor = User::find($formData["supervisor"]);

        // return $agent_account;
        if (!$agent_account) {
            return self::sendError("Cet agent comptable n'existe pas!", 404);
        }
        if (!$supervisor) {
            return self::sendError("Ce superviseur n'existe pas!", 404);
        }

        ####____VERIFICATION DE L'EXISTENCE DE L'AFFECTATION
        $is_this_affectation_existe = AgentAccountSupervisor::where([
            "supervisor" => $formData["supervisor"],
            "agent_account" => $formData["agent_account"],
        ])->first();

        if ($is_this_affectation_existe) {
            return self::sendError("Cette affectation existe déjà!", 505);
        }

        ###__VERIFICATION DU VRAI ROLE D'UN AGENT COMPTABLE
        $agent_account_roles = $agent_account->roles; ##recuperation des roles de ce user

        ###__CETTE VARIABLE DEFINI SI CET UTILISATEUR DISPOSE VRAIMENT DU ROLE D'UN AGENT COMPTABLE 
        $is_this_user_really_agent_account =  false;

        foreach ($agent_account_roles as $user_role) {
            if ($user_role->id == 4) {
                $is_this_user_really_agent_account = true;
            }
        }

        if (!$is_this_user_really_agent_account) {
            return self::sendError("Désolé! L'utilisateur ( " .  $agent_account['name'] . " ) ne dispose vraiment pas du rôle d'un agent comptable", 404);
        }

        ###__VERIFICATION DU VRAI ROLE DU SUPERVISEUR
        $supervisor_roles = $supervisor->roles; ##recuperation des roles de ce user

        ###__CETTE VARIABLE DEFINI SI CET UTILISATEUR DISPOSE VRAIMENT DU ROLE D'UN AGENT COMPTABLE 
        $is_this_user_really_supervisor =  false;

        foreach ($supervisor_roles as $user_role) {
            if ($user_role->id == 3) {
                $is_this_user_really_supervisor = true;
            }
        }

        if (!$is_this_user_really_supervisor) {
            return self::sendError("Désolé! L'utilisateur ( " .  $supervisor['name'] . " ) ne dispose vraiment pas du rôle d'un superviseur!", 404);
        }

        ##########___ AFFECTATION PROPREMENT DITE #############
        $affectation = AgentAccountSupervisor::create($formData);

        return self::sendResponse($affectation, "Affectation éffectuée  avec succès!");
    }

    static function _desaffectSupervisorToAccountyAgent($request)
    {
        $formData = $request->all();

        $agent_account = User::find($formData["agent_account"]);
        $supervisor = User::find($formData["supervisor"]);

        if (!$agent_account) {
            return self::sendError("Cet agent comptable n'existe pas!", 404);
        }
        if (!$supervisor) {
            return self::sendError("Ce superviseur n'existe pas!", 404);
        }

        ####____VERIFICATION DE L'EXISTENCE DE L'AFFECTATION
        $is_this_affectation_existe = AgentAccountSupervisor::where([
            "supervisor" => $formData["supervisor"],
            "agent_account" => $formData["agent_account"],
        ])->first();

        if (!$is_this_affectation_existe) {
            return self::sendError("Cette affectation n'existe pas!", 505);
        }

        ##########___ AFFECTATION PROPREMENT DITE #############
        $is_this_affectation_existe->delete();

        return self::sendResponse($is_this_affectation_existe, "Affectation rétirée  avec succès!");
    }

    static function search($request, $userId)
    {

        if (!$request->get("search")) {
            return self::sendError("Le champ **search** est réquis!", 505);
        }
        $search = $request->get("search");
        $users = User::with(["_Agency", "as_admin", "my_admins", "belong_to_organisation", "roles"])->where(["owner" => $userId])->orderBy("id", "desc")->get();

        // search via name
        $result = collect($users)->filter(function ($user) use ($search) {
            return Str::contains(strtolower($user['name']), strtolower($search));
        })->all();

        if (count($result) == 0) {
            // search via email
            $result = collect($users)->filter(function ($user) use ($search) {
                return Str::contains(strtolower($user['email']), strtolower($search));
            })->all();

            if (count($result) == 0) {
                // search via phone
                $result = collect($users)->filter(function ($user) use ($search) {
                    return Str::contains(strtolower($user['phone']), strtolower($search));
                })->all();

                if (count($result) == 0) {
                    // search via agence
                    $result = collect($users)->filter(function ($user) use ($search) {
                        if (!$user->agency) {
                            return [];
                        } else {
                            return Str::contains(strtolower($user['_Agency']["name"]), strtolower($search));
                        }
                    })->all();
                }
            }
        }

        if (count($result) == 0) {
            return self::sendError("Aucun résultat trouvé pour cette recherche", 505);
        }

        // ##__
        return self::sendResponse($result, "Résultat de votre recherche");
    }

    static function _deleteAccount($id)
    {
        if ($id == 1 || $id == 2 || $id == 3) {
            return self::sendError("Désolé! Vous ne pouvez pas supprimer ce compte!", 505);
        }

        #####______
        $account = User::find($id);
        if (!$account) {
            return self::sendError("Ce compte n'existe pas!", 404);
        }

        ###____
        $account->visible = 0;
        $account->save();

        return self::sendResponse($account, "Compte supprimé avec succès!");
    }
}
