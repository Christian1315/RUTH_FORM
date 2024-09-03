<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    function Home(Request $request)
    {
        if (auth()->user()) {
            return redirect("dashbord");
        } else {
            return view("home");
        }
    }

    function Login(Request $request)
    {
        return view("admin.dashboard");
    }

    function Logout(Request $request)
    {
        $BASE_URL = env("BASE_URL");

        $token = session()->get("token");

        $headers = [
            "Authorization" => "Bearer " . $token,
        ];

        $response = Http::withHeaders($headers)->get($BASE_URL . "user/logout")->json();

        // SUPPRESSION DU TOKEN
        session()->forget("userId");
        session()->forget("token");
        session()->forget("user");

        return redirect("/")->with("success", "Vous êtes déconnecté.e avec succès:");
    }

    function DemandReinitializePassword(Request $request)
    {
        return view("reinitialisation");
    }
}
