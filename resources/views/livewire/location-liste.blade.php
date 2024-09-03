<div>
    <button class="btn btn-md text-uppercase bg-dark m-2 text-left">Arrêter les états</button>

    <p class="">Liste des locations de la maison</p>
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive table-responsive-list shadow-lg">
                <table class="table table-striped table-sm">
                    @if(count($locations)!=0)
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Locataire</th>
                            <th class="text-center">Téléphone</th>
                            <th class="text-center">Chambre</th>
                            <th class="text-center">Loyer Mensuel</th>
                            <th class="text-center">Dernier mois payé</th>
                            <th class="text-center">Date d'Intégration</th>
                            <th class="text-center">Factures</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $location)

                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center">{{$location["locataire"]["name"]}} {{$location["locataire"]["prenom"]}}</td>
                            <td class="text-center">{{$location["locataire"]["phone"]}}</td>
                            <td class="text-center">{{$location["room"]["number"]}}</td>
                            <td class="text-center">{{$location["room"]["total_amount"]}}</td>
                            <td class="text-center">{{$location["latest_loyer_date"]}}</td>
                            <td class="text-center">{{$location["integration_date"]}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm bg-warning" data-bs-toggle="modal" data-bs-target="#locationFactures">
                                    Voir
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <p class="text-center text-red">Aucune location!</p>
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
    <br><br>


    <h4 class="text-red">Liste des états de la maison</h4>
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive shadow-lg p-3">
                <table class="table table-striped table-sm">
                    @if(count($states)!=0)

                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Le responsable de l'arrêt</th>
                            <th class="text-center">Date d'arrêt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($states as $state)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index+1}}</td>
                            <td class="text-center"> {{$state["owner"]["name"]}} </td>
                            <td class="text-center"> <span class="btn btn-sm p-1 bg-red">{{$state["stats_stoped_day"]}}</span> </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <p class="text-center text-red">Aucun arrêt d'état!</p>
                    @endif
                </table>
            </div>
        </div>
    </div>


    <h5 class="">Les Factures de cette location</h5>
    <div class="table-responsive shadow-lg p-3">
        <table class="table table-striped table-sm">
            <thead class="bg_dark">
                <tr>
                    <th class="text-center">N°</th>
                    <th class="text-center">Facture</th>
                    <th class="text-center">Montant Payé</th>
                    <th class="text-center">Status </th>
                    <th class="text-center">Type</th>
                    <th class="text-center">commentaire</th>
                </tr>
            </thead>
            <tbody>
                <tr class="align-items-center">
                    <td class="text-center">1</td>
                    <td class="text-center"><img src="images/edou_logo.png" class="img-fluid" width="50px" srcset="">
                    </td>
                    <td class="text-center">300000</td>
                    <td class="text-center">Status 2</td>
                    <td class="text-center">Type 1</td>
                    <td class="text-center">
                        <textarea name="" id="">
                                                        Premier paiement
                                                    </textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>