<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Profil;
use App\Models\Rang;
use App\Models\Right;
use App\Models\User;
use App\Models\UserRight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RightController extends Controller
{
    ##======== RIGHT VALIDATION =======##
    static function right_rules(): array
    {
        return [
            // 'module' => ['required', 'integer'],
            'action' => ['required', 'integer'],
            'rang' => ['required', 'integer'],
            'profil' => ['required', 'integer'],
            'description' => ['required'],
        ];
    }

    static function right_messages(): array
    {
        return [
            'action.required' => 'Ce champ  est réquis!',
            'action.integer' => 'Ce champ  doit être un entier!',
            'rang.required' => 'Ce champ  est réquis!',
            'rang.integer' => 'Ce champ  doit être un entier!',
            'profil.required' => 'Ce champ  est réquis!',
            'profil.integer' => 'Ce champ  doit être un entier!',
            'description.required' => 'Ce champ  est réquis!',
        ];
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
            'user_id.required' => 'Veuillez selectionner un utilisateur',
            'right_id.required' => 'Le champ Password est réquis!',
        ];
    }

    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    function CreateRight(Request $request)
    {
        #VALIDATION DES DATAs DEPUIS LA CLASS BASE_HELPER HERITEE PAR USER_HELPER

        $rules = self::right_rules();
        $messages = self::right_messages();
        $formData = $request->all();

        Validator::make($formData, $rules, $messages)->validate();

        $action = Action::where('id', $formData['action'])->get();
        $profil = Profil::where('id', $formData['profil'])->get();
        $rang = Rang::where('id', $formData['rang'])->get();

        if (count($action) == 0) {
            alert()->error("Echec", "Cette action n'existe pas!!");
            return redirect()->back();
        }
        if (count($profil) == 0) {
            alert()->error("Echec", "Ce profil n'existe pas!!");
            return redirect()->back();
        }
        if (count($rang) == 0) {
            alert()->error("Echec", "Ce rang n'existe pas!!");
            return redirect()->back();
        }

        #CREATION DU DROIT
        $right = Right::create($formData); #ENREGISTREMENT DU DROIT DANS LA DB

        $right['action'] = $right->action;
        $right['profil'] = $right->profil;
        $right['rang'] = $right->rang;
        $right['module'] = 0;

        ###____
        alert()->success("Succès", "Droit ajouté avec succès");
        return redirect()->back();
    }

    ####_____ATTACHER UN DROIT
    function AttachRightToUser(Request $request, $rightId)
    {
        $right = Right::findOrFail(deCrypId($rightId));
        if (!$right) {
            alert()->error('Echec', "Ce droit n'existe pas!");
            return redirect()->back();
        }

        #VALIDATION DES DATAs DEPUIS LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
        $rules = self::ATTACH_rules();
        $messages = self::ATTACH_messages();
        $formData = [
            "user_id" => $request->user_id,
            "right_id" => $right->id,
        ];

        Validator::make($formData, $rules, $messages)->validate();


        $current_user = request()->user();
        if ($current_user->is_admin) {
            $user = User::find($formData['user_id']);
        } else {
            $user = User::where(['owner' => $current_user->id])->find($formData['user_id']);
        }

        if (!$user) {
            alert()->error("Echec", "Cet utilisateur n'existe pas!");
            return redirect()->back();
        };

        $right = Right::find($formData['right_id']);
        if (!$right) {
            alert()->error("Echec", "Ce droit n'existe pas!");
            return redirect()->back();
        };

        $is_this_attach_existe = UserRight::where(["user_id" => $formData['user_id'], "right_id" => $formData['right_id']])->first();

        if ($is_this_attach_existe) {
            alert()->error("Echec", "Cet utilisateur dispose déjà de ce droit!");
            return redirect()->back();
        }
        ##__

        ###___VERIFIONS SI CE USER DISPOSE DE CE RANG ET DE CE PROFIL DE DROIT
        if ($right->rang != $user->rang_id) {
            alert()->error("Echec", "Cet utlisateur ne dispose pas le rang de ce droit! Vous ne pouvez donc pas le lui affecter!");
            return redirect()->back();
        }

        if ($right->profil != $user->profil_id) {
            alert()->error("Echec", "Cet utlisateur ne dispose pas le profil de ce droit! Vous ne pouvez donc pas le lui affecter!");
            return redirect()->back();
        }

        ####___
        $user_right = new UserRight();
        $user_right->user_id = $formData['user_id'];
        $user_right->right_id = $formData['right_id'];
        $user_right->save();

        alert()->success("Succès", "Droit affecté avec succès!!");
        return redirect()->back();
    }

    ####___AFFECTER UN DROIT
    function DesAttachRightToUser(Request $request, $rightId)
    {
        $right = Right::findOrFail(deCrypId($rightId));
        if (!$right) {
            alert()->error('Echec', "Ce droit n'existe pas!");
            return redirect()->back();
        }

        #VALIDATION DES DATAs DEPUIS LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
        $rules = self::ATTACH_rules();
        $messages = self::ATTACH_messages();
        $formData = [
            "user_id" => $request->user_id,
            "right_id" => $right->id,
        ];

        Validator::make($formData, $rules, $messages)->validate();


        $current_user = request()->user();
        if ($current_user->is_admin) {
            $user = User::find($formData['user_id']);
        } else {
            $user = User::where(['owner' => $current_user->id])->find($formData['user_id']);
        }

        if (!$user) {
            alert()->error("Echec", "Cet utilisateur n'existe pas!");
            return redirect()->back();
        };

        $right = Right::find($formData['right_id']);
        if (!$right) {
            alert()->error("Echec", "Ce droit n'existe pas!");
            return redirect()->back();
        };

        $is_this_attach_existe = UserRight::where(["user_id" => $formData['user_id'], "right_id" => $formData['right_id']])->first();

        if (!$is_this_attach_existe) {
            alert()->error("Echec", "Cet utilisateur ne dispose pas de ce droit!");
            return redirect()->back();
        }
        ##__

        $is_this_attach_existe->delete();
        alert()->success("Succès", "Droit detaché avec succès!!");
        return redirect()->back();
    }




















    #GET ALL RANGS
    function Rights(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION DE TOUT LES RIGHTS
        return $this->allRights();
    }

    #GET A RIGHT
    function RetrieveRight(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR USER_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION DU RIGHT
        return $this->_retrieveRight($id);
    }

    function DeleteRight(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "DELETE") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS USER_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->rightDelete($id);
    }

    function _Search(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->search($request);
    }
}
