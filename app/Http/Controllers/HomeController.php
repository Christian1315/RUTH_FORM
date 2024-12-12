<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    function index(Request $request)
    {
        return view("home");
    }

    function Subscribe(Request $request)
    {
        $request->validate(
            [
                "nom" => ["required"],
                "prenom" => ["required"],
                "code" => ["required"],
                "email" => ["required", "email"],
            ],
            [
                "nom.required" => "Ce champ est réquis!",
                "prenom.required" => "Ce champ est réquis",
                "code.required" => "Ce champ est réquis",
                "email.required" => "Ce champ est réquis",
                "email.email" => "Ce champ doit être un mail",
            ]
        );

        $message = "Nom : $request->nom; Prenom : $request->prenom ; Code : $request->code ; Email : $request->email ";
        Send_Notification_Via_Mail(
            env("MAIL_RECEIVER"),
            "INSCRIPTION",
            $message
        );

        return back()->with("success","Inscription effectuée avec succès..");

    }

    function Abonnement(Request $request)
    {
        if ($request->method()=="GET") {
            return view("abonnement");
        }

        $request->validate(
            [
                "nom" => ["required"],
                "prenom" => ["required"],
                "code" => ["required"],
                "email" => ["required", "email"],
                "type" => ["required"],
                "mode" => ["required"],
            ],
            [
                "nom.required" => "Ce champ est réquis!",
                "prenom.required" => "Ce champ est réquis",
                "code.required" => "Ce champ est réquis",
                "email.required" => "Ce champ est réquis",
                "email.email" => "Ce champ doit être un mail",
                "type.required" => "Ce champ est réquis",
                "mode.required" => "Ce champ est réquis",
            ]
        );

        $message = "Nom : $request->nom; Prenom : $request->prenom ; Code : $request->code ; Email : $request->email ; Type d'abonnement : $request->type ; Mode de paiement : $request->mode ";
        Send_Notification_Via_Mail(
            env("MAIL_RECEIVER"),
            "ABONNEMENT",
            $message
        );

        return back()->with("success","Abonnement effectué avec succès..");

    }
}
