<div>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{asset('images/edou_logo.png')}}" type="image/x-icon">
        <link rel="stylesheet" href="{{asset('fichiers/icon-font.min.css')}}">
        <link rel="stylesheet" href="{{asset('fichiers/bootstrap.css')}}">
        <link rel="stylesheet" href="{{asset('fichiers/base.css')}}">
        <link rel="stylesheet" href="{{asset('fichiers/animate.min.css')}}" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- BOOTSTRAP SELECT -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" integrity="sha512-ARJR74swou2y0Q2V9k0GbzQ/5vJ2RBSoCWokg4zkfM29Fb3vZEQyv0iWBMW/yvKgyHSR/7D64pFMmU8nYmbRkg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <title>RUTH'APP</title>
        @livewireStyles
    </head>

    <body>
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 px-0 mx-0 fixed-top">
                        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-lg py-2">
                            <a class="navbar-brand" href="/"><strong class="bg-warning text-dark p-1" style="margin-right:-10px"> RUTH'</strong> <strong class="app bg-danger text-white p-1"> APP</strong></a>

                            <div class="d-flex" id="navbarNav">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/">Inscription</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/abonnement">Abonnement</a>
                                </li>
                            </div>
                        </nav>

                        <style>
                            #navbarNav li {
                                list-style-type: none;
                            }
                            #navbarNav li a{
                                text-decoration: none;
                                color: #000;
                            }
                        </style>
                    </div>
                </div>
            </div>

            <!-- content -->
            <div class="container-fluid bg-light" id="login-page">
                <!-- MESSAGE FLASH -->
                <x-alert />

                <!-- CONTENT -->
                {{ $slot }}
            </div>

            <div class="container-fluid fixed-bottom  shadow-lg py-0 bg-white">
                <div class="row">
                    <div class="col-md-12 px-0 mx-0 py-2">
                        <p class="text-center">© Copyright 2024 | By Code4Christ</p>
                    </div>
                </div>
            </div>
        </div>

        <script src="fichiers/jquery.min.js"></script>
        <script src="fichiers/bootstrap.min.js"></script>
        <script src="fichiers/popper.min.js"></script>
        @livewireScripts
    </body>

    <!-- BOOTSTRAP SELECT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    </html>
</div>