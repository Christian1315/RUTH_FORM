<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Authorization extends BASE_HELPER
{
    #ACCES BLOQUE QUAND LE USER N'EST PAS AUTHENTIFIE(E)
    function Authorization()
    {
        return $this->sendError("Accès réfusé \n! Veuillez-vous authentifiez!", 201);
    }
}
