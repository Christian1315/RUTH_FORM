<div>
    <!doctype html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="{{asset('images/edou_logo.png')}}" type="image/x-icon">
        <link href="{{asset('fichiers/bootstrap.css')}}" rel="stylesheet">

        <link rel="stylesheet" href="{{asset('fichiers/icon-font.min.css')}}">
        <link rel="stylesheet" href="{{asset('fichiers/animate.min.css')}}" />


        <title>{{$title}}</title>

        <!-- <link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/dashboard/"> -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> -->
        <!-- Bootstrap core CSS -->

        <!-- Custom styles for this template -->
        <link href="{{asset('fichiers/dashbord.css')}}" rel="stylesheet">
        <link href="{{asset('fichiers/base.css')}}" rel="stylesheet">

        <link rel="stylesheet" href="{{asset('datatables/datatable.css')}}">

        <!-- BOOTSTRAP SELECT -->
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" integrity="sha512-ARJR74swou2y0Q2V9k0GbzQ/5vJ2RBSoCWokg4zkfM29Fb3vZEQyv0iWBMW/yvKgyHSR/7D64pFMmU8nYmbRkg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

        @livewireStyles
    </head>

    <body>
        <nav class="navbar navbar-dark fixed-top bg-red flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0 justify-content-between" href="#">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSideBar" aria-controls="offcanvasWithBothOptions">
                    <span class="navbar-toggler-icon"></span>
                </button>
                &nbsp;
                <span>EDOU SERVICES </span>
            </a>

            <input class="form-control form-control-dark w-100 bg-light search--bar" type="text" placeholder="Recherche" aria-label="searh">
            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap text-center">
                    <a class="btn btn-sm btn-light text-center" href="logout">
                        SE DECONNECTER
                        &nbsp;
                        @if(auth()->user())
                        <span class="text-red">
                            ({{auth()->user()->username}})
                        </span>
                        @endif
                    </a>
                </li>
            </ul>
        </nav>


        <div class="container-fluid">
            <div class="row">
                <div class="offcanvas bg-dark offcanvas-start" style="width: 250px!important;" data-bs-scroll="true" tabindex="-1" id="offcanvasSideBar" aria-labelledby="offcanvasWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">
                            EDOU SERVICES
                        </h5>
                        <button type="button" class="btn-close text-red btn btn-sm btn-light" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    @if($active=="dashbord")
                                    <a class="nav-link active" href="/dashbord">
                                        <i class="bi bi-house-add-fill"></i>
                                        Tableau de board <span class="sr-only">(current)</span>
                                    </a>
                                    @else
                                    <a class="nav-link text-white" href="/dashbord">
                                        <i class="bi bi-house-add-fill"></i>
                                        Tableau de board <span class="sr-only">(current)</span>
                                    </a>
                                    @endif
                                </li>

                                <li class="nav-item">
                                    @if($active=="agency")
                                    <a class="nav-link active" href="/agency">
                                        <i class="bi bi-house-add-fill"></i>
                                        Agences <span class="sr-only">(current)</span>
                                    </a>
                                    @else
                                    <a class="nav-link text-white" href="/agency">
                                        <i class="bi bi-house-add-fill"></i>
                                        Agences <span class="sr-only">(current)</span>
                                    </a>
                                    @endif
                                </li>

                                @if($active=="count")
                                <li class="nav-item">
                                    <a class="nav-link active" href="/count">
                                        <i class="bi bi-person-fill-add"></i>
                                        Comptes & Soldes
                                    </a>
                                </li>
                                @else
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="/count">
                                        <i class="bi bi-person-fill-add"></i>
                                        Comptes & Soldes
                                    </a>
                                </li>
                                @endif

                            </ul>
                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                                <span>Paramètres & Statistiques</span>
                                <a class="d-flex align-items-center text-muted" href="#">
                                    <span data-feather="plus-circle"></span>
                                </a>
                            </h6>
                            @if(auth()->user())
                            @if(IS_USER_HAS_MASTER_ROLE(auth()->user()) || auth()->user()->is_master || auth()->user()->is_admin)
                            <ul class="nav flex-column mb-2">

                                @if($active=="setting")
                                <li class="nav-item">
                                    <a class="nav-link active" href="/setting">
                                        <i class="bi bi-gear-fill"></i>
                                        Utilisateurs
                                    </a>
                                </li>
                                @else
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="/setting">
                                        <i class="bi bi-gear-fill"></i>
                                        Utilisateurs
                                    </a>
                                </li>
                                @endif

                                @if($active=="supervisor")
                                <li class="nav-item">
                                    <a class="nav-link active" href="/supervisor">
                                        <i class="bi bi-people-fill"></i>
                                        Superviseurs
                                    </a>
                                </li>
                                @else
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="/supervisor">
                                        <i class="bi bi-people-fill"></i>
                                        Superviseurs
                                    </a>
                                </li>
                                @endif

                                @if($active=="right")
                                <li class="nav-item">
                                    <a class="nav-link active" href="/right">
                                        <i class="bi bi-person-wheelchair"></i>
                                        Les Droits
                                    </a>
                                </li>
                                @else
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="/right">
                                        <i class="bi bi-person-wheelchair"></i>
                                        Les Droits
                                    </a>
                                </li>
                                @endif

                                @if($active=="statistique")
                                <li class="nav-item">
                                    <a class="nav-link active" href="/statistique">
                                        <i class="bi bi-flag-fill"></i>
                                        Statistiques
                                    </a>
                                </li>
                                @else
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="/statistique">
                                        <i class="bi bi-flag-fill"></i>
                                        Statistiques
                                    </a>
                                </li>
                                @endif
                            </ul>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- =============== LE BODY DU DASHBORD ========= -->

                <main role="main" class="col-md-12 ml-sm-auto col-lg-12 px-4">
                    <!-- MESSAGE FLASH -->
                    <x-alert />

                    {{$slot}}

                    <div class="container-fluid bg-white shadow-lg py-3 bg-white mt-5">
                        <div class="row">
                            <div class="col-md-12 px-0 mx-0">
                                <p class="text-center">© Copyright 2024 - Réalisé par HSMC</p>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{asset('fichiers/jquery.min.js')}}"></script>
    <script src="{{asset('fichiers/popper.min.js')}}"></script>
    <script src="{{asset('fichiers/bootstrap.min.js')}}"></script>
    <!-- <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script> -->
    <script src="{{asset('datatables/datatable.js')}}"></script>

    <!-- BOOTSTRAP SELECT -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

    <!-- #### DATA TABLES -->
    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.select2').select2();
        });


        $(function() {
            $("#myTable").DataTable({
                "responsive": true,
                "lengthChange": true,

                "autoWidth": true,
                "buttons": ["excel", "pdf", "print"],
                "order": [
                    [0, 'desc']
                ],
                "pageLength": 10,

                language: {
                    "emptyTable": "Aucune donnée disponible dans le tableau",
                    "lengthMenu": "Afficher _MENU_ éléments",
                    "loadingRecords": "Chargement...",
                    "processing": "Traitement...",
                    "zeroRecords": "Aucun élément correspondant trouvé",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "previous": "Précédent",
                        "next": "Suiv"
                    },
                    "aria": {
                        "sortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "1": "1 ligne sélectionnée"
                        },
                        "cells": {
                            "1": "1 cellule sélectionnée",
                            "_": "%d cellules sélectionnées"
                        },
                        "columns": {
                            "1": "1 colonne sélectionnée",
                            "_": "%d colonnes sélectionnées"
                        }
                    },
                    "autoFill": {
                        "cancel": "Annuler",
                        "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                        "fillHorizontal": "Remplir les cellules horizontalement",
                        "fillVertical": "Remplir les cellules verticalement"
                    },
                    "searchBuilder": {
                        "conditions": {
                            "date": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "gt": "Supérieur à",
                                "gte": "Supérieur ou égal à",
                                "lt": "Inférieur à",
                                "lte": "Inférieur ou égal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "string": {
                                "contains": "Contient",
                                "empty": "Vide",
                                "endsWith": "Se termine par",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "startsWith": "Commence par"
                            },
                            "array": {
                                "equals": "Egal à",
                                "empty": "Vide",
                                "contains": "Contient",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "without": "Sans"
                            }
                        },
                        "add": "Ajouter une condition",
                        "button": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "clearAll": "Effacer tout",
                        "condition": "Condition",
                        "data": "Donnée",
                        "deleteTitle": "Supprimer la règle de filtrage",
                        "logicAnd": "Et",
                        "logicOr": "Ou",
                        "title": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "value": "Valeur"
                    },
                    "searchPanes": {
                        "clearMessage": "Effacer tout",
                        "count": "{total}",
                        "title": "Filtres actifs - %d",
                        "collapse": {
                            "0": "Volet de recherche",
                            "_": "Volet de recherche (%d)"
                        },
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Pas de volet de recherche",
                        "loadMessage": "Chargement du volet de recherche..."
                    },
                    "buttons": {
                        "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                        "collection": "Collection",
                        "colvis": "Visibilité colonnes",
                        "colvisRestore": "Rétablir visibilité",
                        "copy": "Copier",
                        "copySuccess": {
                            "1": "1 ligne copiée dans le presse-papier",
                            "_": "%ds lignes copiées dans le presse-papier"
                        },
                        "copyTitle": "Copier dans le presse-papier",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Afficher toutes les lignes",
                            "_": "Afficher %d lignes"
                        },
                        "pdf": "PDF",
                        "print": "Imprimer"
                    },
                    "decimal": ",",
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                    "infoThousands": ".",
                    "search": "Rechercher:",
                    "thousands": ".",
                    "infoFiltered": "(filtrés depuis un total de _MAX_ éléments)",
                    "datetime": {
                        "previous": "Précédent",
                        "next": "Suivant",
                        "hours": "Heures",
                        "minutes": "Minutes",
                        "seconds": "Secondes",
                        "unknown": "-",
                        "amPm": [
                            "am",
                            "pm"
                        ],
                        "months": [
                            "Janvier",
                            "Fevrier",
                            "Mars",
                            "Avril",
                            "Mai",
                            "Juin",
                            "Juillet",
                            "Aout",
                            "Septembre",
                            "Octobre",
                            "Novembre",
                            "Decembre"
                        ],
                        "weekdays": [
                            "Dim",
                            "Lun",
                            "Mar",
                            "Mer",
                            "Jeu",
                            "Ven",
                            "Sam"
                        ]
                    },
                    "editor": {
                        "close": "Fermer",
                        "create": {
                            "button": "Nouveaux",
                            "title": "Créer une nouvelle entrée",
                            "submit": "Envoyer"
                        },
                        "edit": {
                            "button": "Editer",
                            "title": "Editer Entrée",
                            "submit": "Modifier"
                        },
                        "remove": {
                            "button": "Supprimer",
                            "title": "Supprimer",
                            "submit": "Supprimer",
                            "confirm": {
                                "1": "etes-vous sure de vouloir supprimer 1 ligne?",
                                "_": "etes-vous sure de vouloir supprimer %d lignes?"
                            }
                        },
                        "error": {
                            "system": "Une erreur système s'est produite"
                        },
                        "multi": {
                            "title": "Valeurs Multiples",
                            "restore": "Rétablir Modification",
                            "noMulti": "Ce champ peut être édité individuellement, mais ne fait pas partie d'un groupe. ",
                            "info": "Les éléments sélectionnés contiennent différentes valeurs pour ce champ. Pour  modifier et "
                        }
                    }
                },
            });
        });

    </script>

    </html>
</div>