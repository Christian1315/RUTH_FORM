<div>
    <p class="">Liste des locations de la maison</p>
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive table-responsive-list shadow-lg p-3">
                <table class="table table-striped table-sm">
                <h4 class="">Total: <strong class="text-red"> {{count($locations)}} </strong> </h4>

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
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($locations as $location)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center"> <button class="btn btn-sm btn-light"> <strong> {{$location["locataire"]["name"]}} {{$location["locataire"]["prenom"]}}</strong> </button> </td>
                            <td class="text-center">{{$location["locataire"]["phone"]}}</td>
                            <td class="text-center">{{$location["room"]["number"]}}</td>
                            <td class="text-center">{{$location["room"]["total_amount"]}}</td>
                            <td class="text-center"> <button class="btn btn-sm btn-light"> {{$location["latest_loyer_date"]}} </button></td>
                            <td class="text-center"><i class="bi bi-calendar2-check-fill"></i> {{$location["integration_date"]}}</td>
                            <td class="text-center">
                                <a href="/{{$location['id']}}/factures" type="button" class="btn btn-sm bg-warning text-dark" data-bs-toggle="modal" data-bs-target="#locationFactures">
                                    Voir
                                </a>
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
                            <td class="text-center"> <span class="btn btn-sm p-1 bg-red"><i class="bi bi-calendar2-check-fill"></i> {{$state["stats_stoped_day"]}}</span> </td>
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
</div>