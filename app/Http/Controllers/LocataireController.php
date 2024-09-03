<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\CardType;
use App\Models\Country;
use App\Models\Departement;
use App\Models\Locataire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocataireController extends Controller
{
    ##======== LOCATAIRE VALIDATION =======##
    static function locataire_rules(): array
    {
        return [
            'agency' => ['required', "integer"],
            'name' => ['required'],
            'prenom' => ['required'],
            // 'email' => ['required', "email"],
            'sexe' => ['required'],
            'phone' => ['required', "numeric"],
            // 'piece_number' => ['required'],
            // 'mandate_contrat' => ['required', "file"],
            // 'comments' => ['required'],
            'adresse' => ['required'],
            'card_id' => ['required'],
            'card_type' => ['required', "integer"],
            'departement' => ['required', "integer"],
            'country' => ['required', "integer"],
            // 'prorata' => ['required', "boolean"],
            // 'discounter' => ['required', "boolean"],
        ];
    }

    ###________
    static function locataire_messages(): array
    {
        return [
            'agency.required' => "Veillez préciser l'agence!",
            'agency.integer' => "L'agence doit être un entier",

            'name.required' => 'Le nom du locataire est réquis!',
            'prenom.required' => "Le prénom est réquis!",
            'email.required' => "Le mail est réquis!",
            'email.email' => "Ce champ doit être de format mail",
            'sexe.required' => "Le sexe est réquis",
            'phone.required' => "Le phone est réquis",
            'phone.numeric' => "Le phone doit être de type numéric",
            'piece_number.required' => "Le numéro de la pièce est réquise",
            // 'mandate_contrat.required' => "Le contrat du mandat est réquis",
            // 'mandate_contrat.file' => "Le contrat du mandat doit être un fichier",
            'comments.required' => "Le commentaire est réquis",
            'adresse.required' => "L'adresse est réquis!",
            'card_id.required' => "L'ID de la carte est réquis",
            'card_type.required' => "Le type de la carte est réquis",
            'card_type.integer' => 'Le type de la carte doit être de type entier!',

            'departement.required' => "Le departement est réquis",
            'departement.integer' => "Ce champ doit être de type entier",
            'country.required' => "Le pays est réquis",
            'country.integer' => "Ce champ doit être de type entier",

            // 'prorata.required' => "Veuillez préciser s'il s'agit d'un prorata ou pas!",
            // 'prorata.boolean' => "Ce champ doit être de type booléen",

            // 'discounter.required' => "Veuillez préciser s'il y a un décompteur ou pas!",
            // 'discounter.boolean' => "Ce champ doit être de type booléen",
        ];
    }

    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:'])->except(["ShowAgencyTaux05", "ShowAgencyTaux10", "ShowAgencyTauxQualitatif"]);
    }

    function _AddLocataire(Request $request)
    {
        #VALIDATION DES DATAs DEPUIS LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
        $formData = $request->all();
        $rules = self::locataire_rules();
        $messages = self::locataire_messages();

        Validator::make($formData, $rules, $messages)->validate();
        $formData = $request->all();
        $user = request()->user();

        ###___TRAITEMENT DES DATAS
        $cardType = CardType::find($formData["card_type"]);
        $departement = Departement::find($formData["departement"]);
        $country = Country::find($formData["country"]);
        $agency = Agency::find($formData["agency"]);


        ####___VERIFIONS S'IL S'AGIT D'UN PRORANA OU PAS
        if ($request->get("prorata")) {
            Validator::make(
                $formData,
                [
                    "prorata_date" => ["required", "date"],
                ],
                [
                    "prorata_date.required" => "Veuillez préciser la date du prorata!",
                    "prorata_date.date" => "Ce champ est de type date",
                ]
            )->validate();
        }


        if (!$cardType) {
            alert()->error("Echec", "Ce Type de carte n'existe pas!");
            return back()->withInput();
        }

        if (!$departement) {
            alert()->error("Echec", "Ce département n'existe pas!");
            return back()->withInput();
        }

        if (!$country) {
            alert()->error("Echec", "Ce pays n'existe pas!");
            return back()->withInput();
        }

        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
            return back()->withInput();
        }

        ##___TRAITEMENT DE L'IMAGE
        if ($request->file("mandate_contrat")) {
            $img = $request->file("mandate_contrat");
            $imgName = $img->getClientOriginalName();
            $img->move("mandate_contrats", $imgName);

            #ENREGISTREMENT DU LOCATAIRE DANS LA DB
            if ($user) {
                $formData["owner"] = $user->id;
            }
            $formData["mandate_contrat"] = asset("mandate_contrats/" . $imgName);
        }

        $formData["prorata"] = $request->prorata ? 1 : 0;

        ###___
        Locataire::create($formData);

        alert()->success("Succès", "Locataire ajouté avec succès!");
        return back()->withInput();
    }


    function UpdateLocataire(Request $request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $locataire = Locataire::where(["visible" => 1])->find(deCrypId($id));
        if (!$locataire) {
            alert()->error("Echec", "Ce locataire n'existe pas!");
            return back()->withInput();
        };

        if ($locataire->owner != $user->id) {
            alert()->error("Echec", "Ce locataire ne vous appartient pas!");
            return back()->withInput();
        }

        ####____TRAITEMENT DU TYPE DE CARTE
        if ($request->get("card_type")) {
            $type = CardType::find($request->get("card_type"));

            if (!$type) {
                alert()->error("Echec", "Ce type de carte n'existe pas!");
                return back()->withInput();
            }
        }

        ####____TRAITEMENT DU DEPARTEMENT
        if ($request->get("departement")) {
            $departement = Departement::find($request->get("departement"));

            if (!$departement) {
                alert()->error("Echec", "Ce departement de carte n'existe pas!");
                return back()->withInput();
            }
        }

        ####____TRAITEMENT DU COUNTRY
        if ($request->get("country")) {
            $country = Country::find($request->get("country"));
            if (!$country) {
                alert()->error("Echec", "Ce pays n'existe pas!");
                return back()->withInput();
            }
        }

        $locataire->update($formData);

        alert()->success("Succès", "Locataire modifié avec avec succès!");
        return back()->withInput();
    }

    function DeleteLocataire(Request $request, $id)
    {
        $user = request()->user();
        $locataire = Locataire::where(["visible" => 1])->find(deCrypId($id));
        if (!$locataire) {
            alert()->error("Echec", "Ce locataire n'existe pas!");
            return back()->withInput();
        };

        $locataire->visible = 0;
        $locataire->delete_at = now();
        $locataire->save();

        alert()->success("Succès", "Locataire supprimé avec avec succès!");
        return back()->withInput();
    }

    #####___FILTRE PAR SUPERVISEUR
    function FiltreBySupervisor(Request $request, Agency $agency)
    {
        if (!$agency) {
            alert()->error("Echèc", "Cette agence n'existe pas!");
            return back()->withInput();
        }

        if (!User::find($request->supervisor)) {
            alert()->error("Echèc", "Ce superviseur n'existe pas!");
            return back()->withInput();
        }

        $locators_filtred = [];

        ###___LOCATORS
        $locators = $agency->_Locataires;

        foreach ($locators as $locator) {
            foreach ($locator->Locations as $location) {
                if ($location->House->Supervisor->id == $request->supervisor) {
                    array_push($locators_filtred, $locator);
                }
            }
        }

        if (count($locators_filtred) == 0) {
            alert()->error("Echèc", "Aucun résultat trouvé");
            return back()->withInput();
        }
        ####____
        alert()->success("Succès", "Locataires filtrés avec succès!");
        return back()->withInput()->with(["locators_filtred" => $locators_filtred]);
    }

    #####___FILTRE PAR MAISON
    function FiltreByHouse(Request $request, Agency $agency)
    {
        if (!$agency) {
            alert()->error("Echèc", "Cette agence n'existe pas!");
            return back()->withInput();
        }

        if (!User::find($request->house)) {
            alert()->error("Echèc", "Cette maison n'existe pas!");
            return back()->withInput();
        }

        $locators_filtred = [];

        ###___LOCATORS
        $locators = $agency->_Locataires;

        foreach ($locators as $locator) {
            foreach ($locator->Locations as $location) {
                if ($location->House->id == $request->house) {
                    array_push($locators_filtred, $locator);
                }
            }
        }

        if (count($locators_filtred) == 0) {
            alert()->error("Echèc", "Aucun résultat trouvé");
            return back()->withInput();
        }
        ####____
        alert()->success("Succès", "Locataires filtrés avec succès!");
        return back()->withInput()->with(["locators_filtred" => $locators_filtred]);
    }


    #LOCATAIRES A JOUR PAR SUPERVISEUR
    function PaidFiltreBySupervisor(Request $request, $agency)
    {
        $user = request()->user();
        $agency = Agency::find($agency);
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
            return back()->withInput();
        }

        ####____
        $supervisor = User::find($request->supervisor);
        if (!$supervisor) {
            alert()->error("Echec", "Cette agence n'existe pas!");
            return back()->withInput();
        }

        $locataires = [];
        ###____

        $locations = $agency->_Locations;

        $now = strtotime(date("Y/m/d", strtotime(now())));

        foreach ($locations as $location) {
            ###__la location
            $location_previous_echeance_date = strtotime(date("Y/m/d", strtotime($location->previous_echeance_date)));
            ###__derniere facture de la location
            $last_facture = $location->Factures->last();
            if ($last_facture) {
                $last_facture_created_date = strtotime(date("Y/m/d", strtotime($last_facture->created_at)));
                $last_facture_echeance_date = strtotime(date("Y/m/d", strtotime($last_facture->echeance_date)));

                // return $location_previous_echeance_date;##1722211200
                // return $now;##1714435200
                ###__si la date de payement de la dernière facture de la location
                ####___est égale à la date d'écheance de la location,
                ###___alors ce locataire est à jour

                $is_location_paid_before_or_after_echeance_date = $last_facture_created_date == $last_facture_echeance_date; ###__quand le paiement a été effectué avant ou après la date d'écheance 
                $is_location_paid_at_echeance_date = $last_facture_created_date == $location_previous_echeance_date; ###__quand le paiement a été effectué exactement à la date d'écheance

                // return $is_location_paid_at_echeance_date;
                if ($is_location_paid_at_echeance_date) {
                    if ($location->House->Supervisor->id == $supervisor->id) {
                        array_push($locataires, $location);
                    }
                }
            }
        }

        if (count($locataires) == 0) {
            alert()->error("Echèc", "Aucun résultat trouvé");
            return back()->withInput();
        }

        alert()->success("Succès", "Locataire filtré par superviseur avec succès!");
        return back()->withInput()->with(["filteredLocators", $locataires]);
    }

    #LOCATAIRES A JOUR PAR MAISON
    function PaidFiltreByHouse(Request $request, $agency)
    {
        $user = request()->user();
        $agency = Agency::find($agency);
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
            return back()->withInput();
        }

        ####____
        $house = User::find($request->house);
        if (!$house) {
            alert()->error("Echec", "Cette maison n'existe pas!");
            return back()->withInput();
        }

        $locataires = [];
        ###____

        $locations = $agency->_Locations;

        $now = strtotime(date("Y/m/d", strtotime(now())));

        foreach ($locations as $location) {
            ###__la location
            $location_previous_echeance_date = strtotime(date("Y/m/d", strtotime($location->previous_echeance_date)));
            ###__derniere facture de la location
            $last_facture = $location->Factures->last();
            if ($last_facture) {
                $last_facture_created_date = strtotime(date("Y/m/d", strtotime($last_facture->created_at)));
                $last_facture_echeance_date = strtotime(date("Y/m/d", strtotime($last_facture->echeance_date)));

                // return $location_previous_echeance_date;##1722211200
                // return $now;##1714435200
                ###__si la date de payement de la dernière facture de la location
                ####___est égale à la date d'écheance de la location,
                ###___alors ce locataire est à jour

                $is_location_paid_before_or_after_echeance_date = $last_facture_created_date == $last_facture_echeance_date; ###__quand le paiement a été effectué avant ou après la date d'écheance 
                $is_location_paid_at_echeance_date = $last_facture_created_date == $location_previous_echeance_date; ###__quand le paiement a été effectué exactement à la date d'écheance

                // return $is_location_paid_at_echeance_date;
                if ($is_location_paid_at_echeance_date) {
                    if ($location->House->id == $house->id) {
                        array_push($locataires, $location);
                    }
                }
            }
        }

        if (count($locataires) == 0) {
            alert()->error("Echèc", "Aucun résultat trouvé");
            return back()->withInput();
        }

        alert()->success("Succès", "Locataire filtré par maison avec succès!");
        return back()->withInput()->with(["filteredLocators", $locataires]);
    }

    #LOCATAIRES NON A JOUR PAR SUPERVISEUR
    function UnPaidFiltreBySupervisor(Request $request, $agency)
    {
        $user = request()->user();
        $agency = Agency::find($agency);

        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
            return back()->withInput();
        }

        ####____
        $supervisor = User::find($request->supervisor);
        if (!$supervisor) {
            alert()->error("Echec", "Cette agence n'existe pas!");
            return back()->withInput();
        }

        $locataires = [];
        ###____

        $locations = $agency->_Locations;

        $now = strtotime(date("Y/m/d", strtotime(now())));

        foreach ($locations as $location) {
            ###__la location
            $location_previous_echeance_date = strtotime(date("Y/m/d", strtotime($location->previous_echeance_date)));
            
            ###__derniere facture de la location
            $last_facture = $location->Factures->last();
            if ($last_facture) {
                $last_facture_created_date = strtotime(date("Y/m/d", strtotime($last_facture->created_at)));
                $last_facture_echeance_date = strtotime(date("Y/m/d", strtotime($last_facture->echeance_date)));

                // return $location_previous_echeance_date;##1722211200
                // return $now;##1714435200
                ###__si la date de payement de la dernière facture de la location
                ####___est égale à la date d'écheance de la location,
                ###___alors ce locataire est à jour

                $is_location_paid_before_or_after_echeance_date = $last_facture_created_date == $last_facture_echeance_date; ###__quand le paiement a été effectué avant ou après la date d'écheance 
                $is_location_paid_at_echeance_date = $last_facture_created_date == $location_previous_echeance_date; ###__quand le paiement a été effectué exactement à la date d'écheance

                // return $is_location_paid_at_echeance_date;
                if (!$is_location_paid_at_echeance_date) {
                    if ($location->House->Supervisor->id == $supervisor->id) {
                        array_push($locataires, $location);
                    }
                }
            }
        }

        if (count($locataires) == 0) {
            alert()->error("Echèc", "Aucun résultat trouvé");
            return back()->withInput();
        }

        alert()->success("Succès", "Locataire impayés filtré par superviseur avec succès!");
        return back()->withInput()->with(["filteredLocators", $locataires]);
    }

    #LOCATAIRES NON A JOUR PAR MAISON
    function UnPaidFiltreByHouse(Request $request, $agency)
    {
        $user = request()->user();
        $agency = Agency::find($agency);
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
            return back()->withInput();
        }

        ####____
        $house = User::find($request->house);
        if (!$house) {
            alert()->error("Echec", "Cette maison n'existe pas!");
            return back()->withInput();
        }

        $locataires = [];
        ###____

        $locations = $agency->_Locations;

        $now = strtotime(date("Y/m/d", strtotime(now())));

        foreach ($locations as $location) {
            ###__la location
            $location_previous_echeance_date = strtotime(date("Y/m/d", strtotime($location->previous_echeance_date)));
            ###__derniere facture de la location
            
            $last_facture = $location->Factures->last();
            if ($last_facture) {
                $last_facture_created_date = strtotime(date("Y/m/d", strtotime($last_facture->created_at)));
                $last_facture_echeance_date = strtotime(date("Y/m/d", strtotime($last_facture->echeance_date)));

                // return $location_previous_echeance_date;##1722211200
                // return $now;##1714435200
                ###__si la date de payement de la dernière facture de la location
                ####___est égale à la date d'écheance de la location,
                ###___alors ce locataire est à jour

                $is_location_paid_before_or_after_echeance_date = $last_facture_created_date == $last_facture_echeance_date; ###__quand le paiement a été effectué avant ou après la date d'écheance 
                $is_location_paid_at_echeance_date = $last_facture_created_date == $location_previous_echeance_date; ###__quand le paiement a été effectué exactement à la date d'écheance

                // return $is_location_paid_at_echeance_date;
                if (!$is_location_paid_at_echeance_date) {
                    if ($location->House->id == $house->id) {
                        array_push($locataires, $location);
                    }
                }
            }
        }

        if (count($locataires) == 0) {
            alert()->error("Echèc", "Aucun résultat trouvé");
            return back()->withInput();
        }

        alert()->success("Succès", "Locataire impayés filtré par maison avec succès!");
        return back()->withInput()->with(["filteredLocators", $locataires]);
    }

    function AgencyLocataires(Request $request, $agencyId)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->getAgencyLocataires($agencyId);
    }

    function Recovery05ToEcheanceDate(Request $request, $agencyId)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_recovery05ToEcheanceDate($request, $agencyId);
    }

    function Recovery10ToEcheanceDate(Request $request, $agencyId)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_recovery10ToEcheanceDate($request, $agencyId);
    }

    function RecoveryQualitatif(Request $request, $agencyId)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_recoveryQualitatif($request, $agencyId);
    }

    // impression des recouvrement 05
    function _ImprimeAgencyTaux05(Request $request, $agencyId, $action, $supervisor = null, $house = null)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->imprimeAgencyTaux05($request, $agencyId, $action, $supervisor = null, $house = null);
    }

    function _ImprimeAgencyTaux05_Supervisor(Request $request, $agencyId, $supervisor)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->imprimeAgencyTaux05_supervisor($request, $agencyId, $supervisor);
    }

    function _ImprimeAgencyTaux05_House(Request $request, $agencyId, $house)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->imprimeAgencyTaux05_house($request, $agencyId, $house);
    }

    function ShowAgencyTaux05(Request $request, $agencyId, $action, $supervisor, $house, $start_date, $end_date)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_showAgencyTaux05($request, $agencyId, $action, $supervisor, $house, $start_date, $end_date);
    }

    // impression des recouvrements 10
    function _ImprimeAgencyTaux10(Request $request, $agencyId, $action, $supervisor = null, $house = null)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->imprimeAgencyTaux10($request, $agencyId, $action, $supervisor = null, $house = null);
    }

    function _ImprimeAgencyTaux10_Supervisor(Request $request, $agencyId, $supervisor)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->imprimeAgencyTaux10_supervisor($request, $agencyId, $supervisor);
    }

    function _ImprimeAgencyTaux10_House(Request $request, $agencyId, $house)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->imprimeAgencyTaux10_house($request, $agencyId, $house);
    }

    function ShowAgencyTaux10(Request $request, $agencyId, $action, $supervisor, $house, $start_date, $end_date)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_showAgencyTaux10($request, $agencyId, $action, $supervisor, $house, $start_date, $end_date);
    }

    // impression des recouvrements qualitatif
    function _ImprimeAgencyTauxQualitatif(Request $request, $agencyId, $action, $supervisor = null, $house = null)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->imprimeAgencyTauxQualitatif($request, $agencyId, $action, $supervisor = null, $house = null);
    }

    function _ImprimeAgencyTauxQualitatif_Supervisor(Request $request, $agencyId, $supervisor)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->imprimeAgencyTauxQualitatif_supervisor($request, $agencyId, $supervisor);
    }

    function _ImprimeAgencyTauxQualitatif_House(Request $request, $agencyId, $house)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->imprimeAgencyTauxQualitatif_house($request, $agencyId, $house);
    }

    function ShowAgencyTauxQualitatif(Request $request, $agencyId, $action, $supervisor, $house, $start_date, $end_date)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        return $this->_showAgencyTauxQualitatif($request, $agencyId, $action, $supervisor, $house, $start_date, $end_date);
    }


    #GET ALL LOCATAIRES
    function Locataires(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION DE TOUT LES LOCATAIRES
        return $this->getLocataires();
    }


    #LOCATAIRES EN IMPAYES
    function UnPaidLocataires(Request $request, $agency)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION DE TOUT LES LOCATAIRES EN IMPAYES
        return $this->getUnPaidLocataires($agency);
    }


    #GET AN LOCATAIRE
    function RetrieveLocataire(Request $request, $id)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "GET") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR Card_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };

        #RECUPERATION DE LA LOCATAIRE
        return $this->_retrieveLocataire($id);
    }

    function SearchLocataire(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS Card_HELPER
            return $this->sendError("La méthode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        return $this->search($request);
    }
}
