<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

class UserController extends USER_HELPER
{
    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access'])->only([
            "UpdatePassword",
            "Logout",
            "AttachRightToUser",
            "DesAttachRightToUser",
            "ArchiveAccount",
            "DuplicatAccount",
            "GetAllSupervisors",
            "AddUser",
            "Users",
            "UpdateCompte",
            "DeleteAccount"
        ]);

        $this->middleware(["Check_If_User_Has_A_Master_Role"])->only([
            "UpdateCompte",
            "AttachRightToUser",
            "DesAttachRightToUser",
            "ArchiveAccount",
            "DuplicatAccount"
        ]);
    }

    #AJOUT D'UN UTILISATEUR
    function AddUser(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };


        #VALIDATION DES DATAs DEPUIS LA CLASS USER_HELPER
        $validator = $this->Register_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS USER_HELPER
            return $this->sendError($validator->errors(), 404);
        }

        #RECUPERATION DE TOUT LES UTILISATEURS AVEC LEURS ROLES & TRANSPORTS
        return $this->createUser($request);
    }

    #GET ALL USERS
    function Users(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION DE TOUT LES UTILISATEURS AVEC LEURS ROLES & TRANSPORTS
        return $this->getUsers();
    }

    #RECUPERER UN USER
    function RetrieveUser(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION D'UN USER VIA SON **id**
        return $this->retrieveUsers($id);
    }

    #CONNEXION DU USER
    function Login(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS USER_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #VALIDATION DES DATAs DEPUIS LA CLASS USER_HELPER
        $validator = $this->Login_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS USER_HELPER
            return $this->sendError($validator->errors(), 404);
        }

        #AUTHENTIFICATION DU USER
        return $this->userAuthentification($request);
    }

    #MODIFIER UN PASSWORD
    function UpdatePassword(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #VALIDATION DES DATAs DEPUIS LA CLASS USER_HELPER
        $validator = $this->NEW_PASSWORD_Validator($request->all());
        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS USER_HELPER
            return $this->sendError($validator->errors(), 404);
        }

        #RECUPERATION D'UN USER VIA SON **id**
        return $this->_updatePassword($request->all(), $id);
    }

    #MODIFIER UN COMPTE
    function UpdateCompte(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_updateCompte($request, $id);
    }

    function Logout(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS USER_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->userLogout($request);
    }

    function AttachRightToUser(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS USER_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #VALIDATION DES DATAs DEPUIS LA CLASS USER_HELPER
        $validator = $this->ATTACH_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS USER_HELPER
            return $this->sendError($validator->errors(), 404);
        }

        return $this->rightAttach($request->all());
    }

    function DesAttachRightToUser(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS USER_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #VALIDATION DES DATAs DEPUIS LA CLASS USER_HELPER
        $validator = $this->ATTACH_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS USER_HELPER
            return $this->sendError($validator->errors(), 404);
        }
        return $this->rightDesAttach($request->all());
    }

    #DEMANDE DE REINITIALISATION D'UN PASSWORD
    function DemandReinitializePassword(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION D'UN USER VIA SON **id**
        return $this->_demandReinitializePassword($request);
    }

    #REINITIALISER UN PASSWORD
    function ReinitializePassword(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION D'UN USER VIA SON **id**
        return $this->_reinitializePassword($request);
    }

    #ARCHIVER UN COMPTE
    function ArchiveAccount(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->archive_an_account($id);
    }

    #DUPLIQUER UN COMPTE
    function DuplicatAccount(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->duplicate_an_account($id);
    }

    ###___RECUPERATION DE TOUT LES SUPERVISEURS
    function GetAllSupervisors(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->_getAllSupervisors($request);
    }

    ###___RECUPERATION DE TOUT LES AGENTS COMPTABLES
    function GetAllAccountAgents(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        ###___
        return $this->_getAllAccountAgents($request);
    }


    ###___AFFECTATION D'UN SUPERVISEUR A UN AGENT COMPTABLE
    function AffectSupervisorToAccountyAgent(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        ###___VALIDATION DES DATAS
        $validator = $this->Affect_Supervisor_Validator($request->all());
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 505);
        }

        ###___
        return $this->_affectSupervisorToAccountyAgent($request);
    }

    ###___DESAFFECTATION D'UN SUPERVISEUR A UN AGENT COMPTABLE
    function DetachSupervisorToAccountyAgent(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        ###___VALIDATION DES DATAS
        $validator = $this->Affect_Supervisor_Validator($request->all());
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 505);
        }

        ###___
        return $this->_desaffectSupervisorToAccountyAgent($request);
    }

    function SearchUser(Request $request, $userId)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->search($request, $userId);
    }

    function DeleteAccount(Request $request,$id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "DELETE") == False) {
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_deleteAccount($id);
    }
}
