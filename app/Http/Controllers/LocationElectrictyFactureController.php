<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\LocationElectrictyFacture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationElectrictyFactureController extends Controller
{
    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    ###########============= VALIDATION DES DATAS =========================#########
    ##======== ELECTRICITY FACTURE VALIDATION =======##

    static function electricity_factures_rules(): array
    {
        return [
            'location' => ['required', "integer"],
            // 'start_index' => ['required', "numeric"],
            'end_index' => ['required', "numeric"],
        ];
    }

    static function electricity_factures_messages(): array
    {
        return [
            'location.required' => "Veillez préciser la location!",
            'location.integer' => "La location doit être un entier",

            // 'start_index.required' => "L'index de début est réquis!",
            // 'start_index.numeric' => "L'index de début doit être de type numérique!",

            'end_index.required' => "L'index de fin est réquis!",
            'end_index.numeric' => "L'index de fin doit être de type numérique!",
        ];
    }

    function _GenerateFacture(Request $request)
    {
        $formData = $request->all();

        $rules = self::electricity_factures_rules();
        $messages = self::electricity_factures_messages();

        Validator::make($formData, $rules, $messages)->validate();
        
        $user = request()->user();

        ###___TRAITEMENT DES DATAS
        $location = Location::where(["visible" => 1])->find($formData["location"]);
        if (!$location) {
            alert()->error("Echec", "Cette location n'existe pas!");
            return back()->withInput();
        }

        ####___VOYONS D'ABORD S'IL Y AVAIT UNE FACTURE PRECEDENTE
        $factures = $location->ElectricityFactures; ## LocationElectrictyFacture::all();

        ###__En cas d'existance d efacture précedente, l'index de debut
        ###___ de l'actuelle facture revient à l'index de fin de l'ancienne facture
        if (count($factures) != 0) {
            $last_facture = $factures[0];
            $formData["start_index"] = $last_facture->end_index;
        } else {
            ##__dans le cas contraire
            ###___L'index de debut revient à l'index de debut de la chambre liée à cette location
            $formData["start_index"] = $location->Room->electricity_counter_start_index;
        }


        $formData["consomation"] = $formData["end_index"] - $formData["start_index"];

        // dd($formData["consomation"]);
        if ($formData["consomation"] <= 0) {
            alert()->error("Echec", "Désolé! L'index de fin doit être superieur à celui de début");
            return back()->withInput();
        }

        // ######_________
        $kilowater_unit_price = $location->Room->electricity_unit_price;
        $formData["amount"] = $formData["consomation"] * $kilowater_unit_price;

        // dd($formData["amount"]);
        $formData["comments"] = "Géneration de facture d'électricité pour le locataire << " . $location->Locataire->name . " " . $location->Locataire->prenom . ">> de la maison << " . $location->House->name . " >> à la date " . now() . " par << $user->name >>";

        ###___
        if ($user) {
            $formData["owner"] = $user->id;
        }

        ###____
        LocationElectrictyFacture::create($formData);

        ####____
        alert()->success("Succès", "Facture d'électricité géneréé avec succès!!");
        return back()->withInput();
    }
























    function RetrieveLocationFactures(Request $request, $locationId)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE 
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->getLocationFactures($locationId);
    }

    function RetrieveFacture(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE 
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_retrieveFacture($id);
    }

    function _DeleteFacture(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "DELETE") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->deleteFacture($id);
    }

    function _FacturePayement(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->facturePayement($id);
    }
}
