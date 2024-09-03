<div>
    <div class="text-center">
        <p class="text-red"> {{$generalError}} </p>
    </div>

    <br><br>
    @if($showCautions)
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="alert bg-dark text-white">
                    Cautions générées avec succès! Cliquez sur le lien ci-dessous pour la télécharger: <br>
                    <a class="text-red" href="{{$cautions_link}}" target="_blank" rel="noopener noreferrer">Télécharger</a>
                </div>
            </div>
        </div>
    </div>
    @endif


    <br><br><br>
    <!-- LOCATAIRES AYANT PAY2 AVANT LA DATE D'ARRET DES ETATS DANS CETTE LOCATION -->
    @if($show_locatorsBefore)
    <button wire:click="ImprimeLocatorsBeforeStateStoped({{$currentHouse['id']}})" class="btn btn-sm btn-light shadow text-uppercase"> <i class="bi bi-file-pdf-fill"></i> Imprimer l'état</button>
    <br>
    <div class="col-md-12 mt-5">
        <h5 class="">Les locataires ayant payés avant l'arrêt des états dans la maison -- <span class="text-red">
                << {{$currentHouse["name"]}}>>
            </span> </h5>
        <br>
        <h6 class=""> Montant total: <em class="text-red"> {{$beforeStopDateTotal_to_paid}} </em> </h6>

        <div class="table-responsive shadow-lg p-3">
            <table class="table table-striped table-sm">
                @if(count($locatorsBefore)>0)
                <thead class="bg_dark">
                    <tr>
                        <th class="text-center">N°</th>
                        <th class="text-center">Nom</th>
                        <th class="text-center">Prénom</th>
                        <th class="text-center">Phone </th>
                        <th class="text-center">Adresse</th>
                        <th class="text-center">commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locatorsBefore as $locator)
                    <tr class="align-items-center">
                        <td class="text-center">{{$loop->index + 1}}</td>
                        <td class="text-center">{{$locator["name"]}}</td>
                        <td class="text-center">{{$locator["prenom"]}}</td>
                        <td class="text-center">{{$locator["phone"]}}</td>
                        <td class="text-center">{{$locator["adresse"]}}</td>
                        <td class="text-center">
                            <textarea name="" class="form-control">{{$locator["comments"]}}</textarea>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @else
                <p class="text-red text-center">Aucun locataires n'a payé avant l'ârrêt de états dans cette location</p>
                @endif
            </table>
        </div>
    </div>
    @endif
    <br><br><br>
    <!-- LOCATAIRES AYANT PAY2 APRES LA DATE D'ARRET DES ETATS DANS CETTE LOCATION -->
    @if($show_locatorsAfter)
    <button wire:click="ImprimeLocatorsAfterStateStoped({{$currentHouse['id']}})" class="btn btn-sm btn-light shadow text-uppercase"> <i class="bi bi-file-pdf-fill"></i> Imprimer l'état</button>
    <br>
    <div class="col-md-12 mt-5">
        <h5 class="">Les locataires ayant payés après l'arrêt des états de la chambre --
            Maison: <span class="text-red">
                << << {{$currentHouse["name"]}}>>>>
            </span> ;

        </h5>
        <br>
        <h6 class=""> Montant total: <em class="text-red"> {{$afterStopDateTotal_to_paid}} </em> </h6>

        <div class="table-responsive shadow-lg p-3">
            <table class="table table-striped table-sm shadow-lg p-3">
                @if(count($locatorsAfter)>0)
                <thead class="bg_dark">
                    <tr>
                        <th class="text-center">N°</th>
                        <th class="text-center">Nom</th>
                        <th class="text-center">Prénom</th>
                        <th class="text-center">Phone </th>
                        <th class="text-center">Adresse</th>
                        <th class="text-center">commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locatorsAfter as $locator)
                    <tr class="align-items-center">
                        <td class="text-center">{{$loop->index + 1}}</td>
                        <td class="text-center">{{$locator["name"]}}</td>
                        <td class="text-center">{{$locator["prenom"]}}</td>
                        <td class="text-center">{{$locator["phone"]}}</td>
                        <td class="text-center">{{$locator["adresse"]}}</td>
                        <td class="text-center">
                            <textarea name="" id="" class="form-control">{{$locator["comments"]}}</textarea>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @else
                <p class="text-red text-center">Aucun locataires n'a payé avant l'ârrêt de états dans cette location</p>
                @endif
            </table>
        </div>
    </div>
    @endif


    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive table-responsive-list shadow-lg p-3">
                <table class="table table-striped table-sm">
                    <h4 class="">Total: <strong class="text-red"> {{$houses_count}} </strong> </h4>
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Latitude</th>
                            <th class="text-center">Longitude</th>
                            <th class="text-center">Type de maison</th>
                            <th class="text-center">Superviseur</th>
                            <th class="text-center">Propriétaire</th>
                            <th class="text-center">Mouvements des locataires</th>
                        </tr>
                    </thead>
                    @if($houses_count>0)
                    <tbody>
                        @foreach($houses as $house)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center"> {{$house["name"]}}</td>
                            <td class="text-center"> @if($house["latitude"]) {{$house["latitude"]}} @else --- @endif</td>
                            <td class="text-center">@if($house["longitude"]) {{$house["longitude"]}} @else --- @endif</td>
                            <td class="text-center">{{$house["type"]["name"]}}</td>
                            <td class="text-center">{{$house["supervisor"]["name"]}}</td>
                            <td class="text-center">{{$house["proprietor"]["lastname"]}} {{$house["proprietor"]["firstname"]}}</td>

                            <td class="text-center">
                                <button wire:click="showLocatorBeforeStates({{$house['id']}})" class="btn btn-sm bg-dark">Avant arrêt d'état</button> &nbsp;
                                <button wire:click="showLocatorAfterStates({{$house['id']}})" class="btn btn-sm bg-red">Après arrêt d'état</button> &nbsp;
                                &nbsp;
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                    @else
                    <p class="text-center text-red">Aucune maison !</p>
                    @endif
                </table>

            </div>
            <!-- pagination -->
            <div class="justify-center my-2">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>
</div>