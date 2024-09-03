<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

class RoleController extends ROLE_HELPER
{
    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access']);

        $this->middleware(["ChechSuperAdminOrSimpleAdmin"])->only([
            "UpdateCompte",
            "AttachRightToUser",
            "DesAttachRightToUser",
            "ArchiveAccount",
            "DuplicatAccount",

            "DesAttachRoleToUser",
            "AttachRoleToUser"
        ]);
    }

    function CreateRole(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #VALIDATION DES DATAs DEPUIS LA CLASS BASE_HELPER HERITEE PAR 
        $validator = $this->Role_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR 
            return $this->sendError($validator->errors(), 404);
        }

        #ENREGISTREMENT DANS LA DB VIA **_createRole** DE LA CLASS BASE_HELPER HERITEE PAR ROLE_HELPER
        return $this->_createRole($request->all());
    }

    #GET ALL ROLES
    function Roles(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION DE TOUT LES ROLES
        return $this->allRoles();
    }

    #GET A ROLE
    function RetrieveRole(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION DU ROLE
        return $this->_retrieveRole($id);
    }

    function AttachRoleToUser(Request $request)
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

        return $this->roleAttach($request->all());
    }

    function DesAttachRoleToUser(Request $request)
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
        return $this->roleDesAttach($request->all());
    }
}
