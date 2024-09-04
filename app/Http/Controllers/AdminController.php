<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    function __construct()
    {
        $this->middleware(['auth']);
    }

    ###____
    static function getAgency($id)
    {
        $BASE_URL = env("BASE_URL");
        $token = session()->get("token");
        $userId = session()->get("userId");

        $headers = [
            "Authorization" => "Bearer " . $token,
        ];

        ###___
        $response = Http::withHeaders($headers)->get($BASE_URL . "immo/agency/$id/retrieve")->json();

        return $response;
    }

    function Admin(Request $request)
    {
        ###___
        $user = auth()->user();

        ###___VERIFIONS SI LE CE COMPTE A ETE ARCHIVE
        if ($user->is_archive) {
            // °°°°°°°°°°° DECONNEXION DU USER
            Auth::logout();

            // °°°°°°°°° SUPPRESION DES SESSIONS
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            alert()->error('Echec', "Ce compte a été archivé!");
            return redirect()->back()->withInput();
        };

        ###___VERIFIONS SI LE CE COMPTE EST ACTIF OU PAS
        if (!$user->visible) {
            // °°°°°°°°°°° DECONNEXION DU USER
            Auth::logout();

            // °°°°°°°°° SUPPRESION DES SESSIONS
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            alert()->error('Echec', "Ce compte a été Supprimé!");
            return redirect()->back()->withInput();
        };

        $current_agency_id = $user->user_agency;
        $current_agency_affected_id = $user->agency;

        $crypted_current_agency_id = Crypt::encrypt($current_agency_id);
        $crypted_current_agency_affected_id = Crypt::encrypt($current_agency_affected_id);

        ###__QUANT IL S'AGIT D'UNE AGENCE
        if ($current_agency_id) {
            return redirect("/$crypted_current_agency_id/manage-agency");
        }

        ###__QUANT IL S'AGIT D'UN USER AFFECTE A UNE AGENCE
        if ($current_agency_affected_id) {
            return redirect("/$crypted_current_agency_affected_id/manage-agency");
        }

        ###___
        return view("admin.dashboard");
    }

    function Agencies(Request $request)
    {
        return view("admin.agency");
    }

    function ManageAgency(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $agency = Agency::where("visible", 1)->find($id);
        ####____

        ###___
        return view("admin.manage-agency", compact("agency"));
    }

    function Proprietor(Request $request, $agencyId)
    {
        $id = Crypt::decrypt($agencyId);
        $agency = Agency::where("visible", 1)->findOrFail($id);
        ####____
        return view("admin.proprietors", compact("agency"));
    }

    function House(Request $request, $agencyId)
    {
        $id = Crypt::decrypt($agencyId);

        $agency = Agency::where("visible", 1)->findOrFail($id);
        ####____
        return view("admin.houses", compact("agency"));
    }

    function Client(Request $request)
    {
        return view("admin.clients");
    }

    function Room(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        }
        ####____

        return view("admin.rooms", compact("agency"));
    }

    function Locator(Request $request, $agencyId)
    {

        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____
        return view("admin.locataires", compact("agency"));
    }

    function PaidLocator(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____
        return view("admin.paid-locators", compact("agency"));
    }

    function UnPaidLocator(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };

        ####____
        return view("admin.unpaid-locators", compact("agency"));
    }

    function Location(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };

        ####____
        return view("admin.locations", compact("agency"));
    }

    function AccountSold(Request $request)
    {
        return view("admin.count_solds");
    }

    function Initiation(Request $request)
    {
        return view("admin.initiations");
    }

    function AgencyInitiation(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____

        return view("admin.agency-initiations", compact("agency"));
    }

    function Paiement(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____
        return view("admin.paiements", compact("agency"));
    }

    function Electricity(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____
        return view("admin.electricity", compact("agency"));
    }

    function AgencyStatistique(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____

        return view("admin.agency-statistique", compact("agency"));
    }


    #####____BILAN
    function Filtrage(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____

        return view("admin.filtrage", compact("agency"));
    }


















    function PaiementAll(Request $request)
    {
        $agency = [];
        return view("admin.paiements_all", compact("agency"));
    }

    function Setting(Request $request)
    {
        return view("admin.settings");
    }

    function Supervisors(Request $request)
    {
        return view("admin.supervisors");
    }

    function Statistique(Request $request)
    {
        return view("admin.statistiques");
    }

    function AgencyRecovery05(Request $request, $agencyId)
    {
        $id = Crypt::decrypt($agencyId);

        ###___
        $response = self::getAgency($id);
        if (!$response) {
            return redirect()->back()->with("error", $response["erros"]);
        }
        if (!$response["status"]) {
            return redirect()->back()->with("error", $response["erros"]);
        }
        $agency = $response["data"];
        ####____

        return view("admin.recovery05", compact("agency"));
    }

    function AgencyRecovery10(Request $request, $agencyId)
    {
        $id = Crypt::decrypt($agencyId);

        ###___
        $response = self::getAgency($id);
        if (!$response) {
            return redirect()->back()->with("error", $response["erros"]);
        }
        if (!$response["status"]) {
            return redirect()->back()->with("error", $response["erros"]);
        }
        $agency = $response["data"];
        ####____

        return view("admin.recovery10", compact("agency"));
    }

    function AgencyRecoveryQualitatif(Request $request, $agencyId)
    {
        $id = Crypt::decrypt($agencyId);

        ###___
        $response = self::getAgency($id);
        if (!$response) {
            return redirect()->back()->with("error", $response["erros"]);
        }
        if (!$response["status"]) {
            return redirect()->back()->with("error", $response["erros"]);
        }
        $agency = $response["data"];
        ####____

        return view("admin.recovery_qualitatif", compact("agency"));
    }

    function AgencyPerformance(Request $request, $agencyId)
    {
        $id = Crypt::decrypt($agencyId);

        ###___
        $response = self::getAgency($id);
        if (!$response) {
            return redirect()->back()->with("error", $response["erros"]);
        }
        if (!$response["status"]) {
            return redirect()->back()->with("error", $response["erros"]);
        }
        $agency = $response["data"];
        ####____

        return view("admin.performance", compact("agency"));
    }

    function RecoveryAtAnyDate(Request $request, $agencyId)
    {
        $id = Crypt::decrypt($agencyId);

        ###___
        $response = self::getAgency($id);
        if (!$response) {
            return redirect()->back()->with("error", $response["erros"]);
        }
        if (!$response["status"]) {
            return redirect()->back()->with("error", $response["erros"]);
        }
        $agency = $response["data"];
        ####____

        return view("admin.recovery_at_any_date", compact("agency"));
    }

    function Rights(Request $request)
    {
        return view("admin.rights");
    }

    function Eau(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ##___

        return view("admin.eau_locations", compact("agency"));
    }

    function Caisses(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };

        return view("admin.caisses", compact("agency"));
    }

    function CaisseMouvements(Request $request, $agencyId, $agency_account)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ##___

        return view("admin.caisse-mouvements", compact(["agency", "agency_account"]));
    }

    function Encaisser(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ##___

        return view("admin.encaisser", compact("agency"));
    }

    function Decaisser(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ##___

        return view("admin.decaisser", compact("agency"));
    }


    function StopState(Request $request, $id)
    {
        $BASE_URL = env("BASE_URL");
        $token = session()->get("token");
        $userId = session()->get("userId");

        $headers = [
            "Authorization" => "Bearer " . $token,
        ];

        $data = [
            "house" => $id,
        ];

        // les locations de cette maison
        $response = Http::withHeaders($headers)->post($BASE_URL . "immo/house_state/stop", $data)->json();
        if (!$response["status"]) {
            return redirect()->back()->with("error", $response["erros"]);
        } else {
            return redirect()->back()->with("success", $response["message"]);
        }
    }

    function LocationFactures(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        return view("admin.factures", compact(["agency"]));
    }
}
