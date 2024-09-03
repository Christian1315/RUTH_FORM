<div>

    <button class="btn btn-sm btn-light text-uppercase" data-bs-toggle="modal" data-bs-target="#generate_facture"><i class="bi bi-file-earmark-pdf-fill"> </i> Génerer une facture d'électricité </button>
    <br>

    <button wire:click="_Show(0)" class="btn btn-sm bg-red text-white text-uppercase"><i class="bi bi-stop-circle"></i> @if($showHouseFom) Fermer @else Arrêter les états @endif</button>
    <br>

    <button wire:click="ShowHouseForStateImprimeForm" class="btn btn-sm btn-light text-uppercase"><i class="bi bi-file-earmark-pdf-fill"> </i> @if($show_house_for_state_imprime_form)Fermer @else Imprimer un état @endif </button>
    <br>

    <!-- GERERATE FACTURE -->
    <div class="modal fade" id="generate_facture" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="">Génerer une facture d'électricité</p>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="text-red">Choisir la location concernée</span>
                            <select required name="location" class="form-control">
                                @foreach($locations as $location)
                                <option value="{{$location['id']}}"> <strong>Maison: </strong> {{$location->House->name}} ; <strong>Index début: </strong> {{$location->Room->electricity_counter_start_index}} ; <strong>Locataire: </strong>{{$location->Locataire->name}} {{$location->Locataire->prenom}}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                    </div>
                    <button type="submit" class="btn btn-sm bg-red"><i class="bi bi-card-list"></i> Génerer</button>
                </div>
            </div>
        </div>
    </div>

    @if($filtre_by_locator)
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-6">
                <div class="shadow p-2">
                    <form wire:submit="FiltreByLocator">
                        <div class="row">
                            <div class="col-md-12">
                                <select required wire:model="locator" name="locator" class="form-control">
                                    <option>Choisissez un locataire</option>
                                    @foreach($locators as $locator)
                                    <option value="{{$locator['id']}}"> {{$locator["name"]}} {{$locator["prenom"]}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                        </div>
                        <br>
                        <div class="text-center">
                            <button class="w-100 text-center bg-red btn btn-sm">Filtrer</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
    @endif

    @if($filtre_by_house)
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-6">
                <div class="shadow p-2">
                    <form wire:submit="FiltreByHouse">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="text-red">Choisir la maison</span>
                                <select required wire:model="house" name="house" class="form-control">
                                    <option>Choisissez une maison</option>
                                    @foreach($houses as $house)
                                    <option value="{{$house['id']}}"> {{$house["name"]}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                        </div>
                        <br>
                        <div class="text-center">
                            <button class="w-100 text-center bg-red btn btn-sm">Filtrer</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
    @endif
    <br>

    @if($show_state_imprime)
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="alert bg-dark text-white">
                    Etat générés avec succès! Cliquez sur le lien ci-dessous pour la télécharger: <br>
                    <a class="text-red" href="{{$state_html_url}}" target="_blank" rel="noopener noreferrer">Télécharger</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($show_house_for_state_imprime_form)
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form wire:submit="SelectHouseForStateImprime" class="shadow-lg p-3 animate__animated animate__bounce">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <span class="">Choisissez la maison </span>
                            <select wire:model="house" name="house" class="form-select form-control" aria-label="Default select example">
                                <option>Choisissez la maison</option>
                                @foreach($houses as $house)
                                <option value="{{$house['id']}}">{{$house["name"]}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn bg-red">Selectionnez</button>
                </div>
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
    @endif

    @if($show_state_imprime_form)
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form wire:submit="ImprimeSelectState" class="shadow-lg p-3 animate__animated animate__bounce">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <span class="">Choisissez l'état </span>
                            <select wire:model="state" name="state" class="form-select form-control" aria-label="Default select example">
                                <option>Choisissez l'état à imprimer</option>
                                @foreach($houseStates as $state)
                                <option value="{{$state['id']}}">Maison: {{$state["house"]["name"]}}-- ({{$state["state_stoped_day"]}}) </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn bg-red"> Imprimer Maintenant</button>
                </div>
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
    @endif

    @if($showHouseFom)
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <form action="{{ route('location._StopStatsOfHouse')}}" class="shadow-lg p-3 animate__animated animate__bounce">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <span class="">Choisissez la maison concernée </span>
                            <select name="house" class="form-select form-control" aria-label="Default select example">
                                @foreach($houses as $house)
                                @if(count($house->Locations) != 0)
                                <option value="{{$house['id']}}">{{$house["name"]}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn bg-red"><i class="bi bi-eye-fill"></i> Arrêter l'état de la maison</button>
                </div>
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
    @endif

    <!-- LISTE DES FACTURES ASSOCIEES A CETTE LOCATION -->
    <div class="row">
        @if($show_factures)
        <div class="col-md-12">
            <div class="table-responsive shadow-lg p-3">
                <table class="table table-striped table-sm p-3">
                    <i wire:click="CloseFcaturesForm" style="font-size:20;cursor:pointer" class="bi bi-file-x float-right text-red"></i>
                    <h6 class="">Total: <strong class="text-red"> {{count($currentLocationFactures)}} </strong> </h6>

                    @if(count($currentLocationFactures)!=0)
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Chambre</th>
                            <th class="text-center">Locataire</th>
                            <th class="text-center">Index début</th>
                            <th class="text-center">Index fin</th>
                            <th class="text-center">Consommation</th>
                            <th class="text-center">Montant</th>
                            <th class="text-center">Commentaire</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($currentLocationFactures as $facture)
                        @if(!$facture["state_facture"])
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index+1}}</td>
                            <td class="text-center">{{$facture["location"]["house"]["name"]}}</td>
                            <td class="text-center">{{$facture["location"]["room"]["number"]}} </td>
                            <td class="text-center">{{$facture["location"]["locataire"]["name"]}} {{$facture["location"]["locataire"]["prenom"]}}</td>
                            <td class="text-center"> {{$facture["start_index"]}} </td>
                            <td class="text-center"> {{$facture["end_index"]}} </td>
                            <td class="text-center"> {{$facture["consomation"]}} </td>
                            <td class="text-center">{{$facture['amount']}}</td>
                            <td class="text-center">
                                <textarea name="" class="form-control" id=""> {{$facture['comments']}} </textarea>
                            </td>
                            <td class="text-center">
                                @if($facture['paid'])
                                <small style="font-size: 10px;" class="text-success shadow roudered p-2">Déjà Payé </small>
                                @else
                                <span class="text-red shadow roudered p-2">Impayé </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($facture['paid'])
                                ---
                                @else
                                <button wire:click="PayFacture({{$facture['id']}})" class="btn btn-sm bg-red"> <i class="bi bi-currency-exchange"></i> Payer maintenant</button>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                    @else
                    <p class="text-center text-red">Aucune facture disponible!</p>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>

    @if($show_form)
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="shadow-lg roundered p-3 mb-3">
                <form wire:submit="GenerateFacture">
                    <div class="mb-3">
                        <span class="d-block">Maison : <small class="text-red">{{$current_house["name"]}} </small> </span>
                        <span class=""> Choisissez la location</span>
                        <span class="text-red"> {{$location_error}} </span>
                        <select name="location" wire:model="location" class="form-select form-control" aria-label="Default select example">
                            <option>Choisissez la location</option>
                            @foreach($locations as $location)
                            <option value="{{$location['id']}}">Maison: ((<span class="text-red"> {{$location["house"]["name"]}}</span>)); Locataire :(( <span class="text-red"> {{$location["locataire"]["name"]}} {{$location["locataire"]["prenom"]}} </span> )) ; Index début: {{$location["room"]["electricity_counter_start_index"]}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <span class="">Index de fin </span>
                        <span class="text-red"> {{$end_index_error}} </span>
                        <input wire:model="end_index" type="number" name="end_index" wire:model="end_index" placeholder="Précisez l'index de fin ...." class="form-control" id="">
                    </div>
                    <div class="">
                        <button type="submit" class="btn btn-sm bg-red">Générer</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
    @endif

    <br><br>
    <div class="row">
        <div class="col-12">
            @if($actualized)
            <h6 class="text-center text-success">Etat actuel de la maison</h6>
            <button wire:click="StopElectricityHouseState({{$houseId}})" class="btn btn-sm bg-red float-right mb-3">Arrêter l'etat maintenant</button>
            @else
            <h5 class="text-center">Liste des locations ayant de l'électricité dans cette agence</h5>
            @endif
            <h4 class="">Total: <strong class="text-red"> {{count($locations)}} </strong> </h4>

            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Locataire</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Télephone</th>
                            <th class="text-center">Index début</th>
                            <th class="text-center">Index fin</th>
                            <th class="text-center">P.U</th>
                            <th class="text-center">Total à payer</th>
                            <th class="text-center">Facture à payer</th>
                            <th class="text-center">Montant payé</th>
                            <th class="text-center">Nbr arrièrées </th>
                            <th class="text-center">Arriérés </th>
                            <th class="text-center">Montant dû</th>
                            <th class="text-center">Payer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $location)
                        <tr class="align-items-center">
                            <td class="text-center">{{$location["id"]}}</td>
                            <td class="text-center">{{$location["Locataire"]["name"]}} {{$location["Locataire"]["prenom"]}}</td>
                            <td class="text-center">{{$location["House"]["name"]}}</td>
                            <td class="text-center">{{$location["Locataire"]["phone"]}}</td>
                            <td class="text-center">{{$location["Room"]["electricity_counter_start_index"]}}</td>
                            <td class="text-center"> <strong class="text-red"> {{$location["end_index"]?$location["end_index"]:0}}</strong> </td>
                            <td class="text-center"> <strong class=""> {{$location["kilowater_price"]}} </strong> </td>
                            <td class="text-center"> <strong class="text-red shadow btn btn-sm"> <i class="bi bi-currency-exchange"></i> {{$location["total_un_paid_facture_amount"]?$location["total_un_paid_facture_amount"]:0}} fcfa </strong> </td>
                            <td class="text-center"> <strong class="text-success shadow btn btn-sm"> <i class="bi bi-currency-exchange"></i> {{$location["current_amount"]?$location["current_amount"]:0}} fcfa </strong> </td>
                            <td class="text-center"> <strong class="text-success shadow btn btn-sm"> <i class="bi bi-currency-exchange"></i> {{$location["paid_facture_amount"]?$location["paid_facture_amount"]:0}} fcfa </strong> </td>
                            <td class="text-center text-red"> {{$location["nbr_un_paid_facture_amount"]?$location["nbr_un_paid_facture_amount"]:0}}</td>
                            <td class="text-center"> <strong class="text-red shadow btn btn-sm"> <i class="bi bi-currency-exchange"></i> {{$location["un_paid_facture_amount"]?$location["un_paid_facture_amount"]:0}} fcfa </strong> </td>
                            <td class="text-center"> <strong class="text-success shadow btn btn-sm"> <i class="bi bi-currency-exchange"></i> {{$location["rest_facture_amount"]?$location["rest_facture_amount"]:0}} fcfa </strong> </td>
                            <td class="text-center">
                                <button wire:click="ShowLocationFactures({{$location['id']}})" class="btn bg-red" type="button">
                                    <i class="bi bi-currency-exchange"></i>&nbsp; Payer
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>