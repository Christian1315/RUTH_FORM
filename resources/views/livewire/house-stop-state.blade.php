<div>
    <br>
    <div class="d-flex header-bar justify-content-between">
        <h4> <strong>Superviseur: <span class="text-red">{{$house->Supervisor?$house->Supervisor->name:"---"}}</span> </strong></h4>
        &nbsp;&nbsp;

        <button class="btn btn-md bg-red" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="bi bi-sign-stop"></i> Arrêter les états de cette maison
        </button>
    </div>
    <br>

    <!-- ### MODAL POUR RENSEIGNER LE RAPPORT DE RECOUVREMENT -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('house.PostStopHouseState',crypId($house->id))}}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Veuillez d'abord rediger un rapport de récouvrement</h6>
                    </div>
                    <div class="modal-body">
                        <textarea required name="recovery_rapport" name="recovery_rapport" class="form-control"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-md bg-red"><i class="bi bi-check-circle"></i> Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row px-0 mx-0">
        <div class="col-6">
            <table class="shadow-lg table table-striped table-sm">
                <tbody>
                    <tr>
                        <td class="" style="border: solid 2px #000;">
                            Nbre de mois récouvré:
                        </td>
                        <td class="bg-warning" style="border: solid 2px #000;">
                            <strong>= {{$house["nbr_month_paid"]}} </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="" style="border: solid 2px #000;">
                            Montant total récouvré:
                        </td>
                        <td class="bg-warning" style="border: solid 2px #000;">
                            <strong>= {{$house["total_amount_paid"]}} fcfa </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="" style="border: solid 2px #000;">
                            Commission:
                        </td>
                        <td class="bg-warning" style="border: solid 2px #000;">
                            <strong>= {{$house["commission"]}} fcfa </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="" style="border: solid 2px #000;">
                            Dépense totale:
                        </td>
                        <td class="bg-warning" style="border: solid 2px #000;">
                            <strong>= {{$house["actuel_depenses"]}} fcfa </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="" style="border: solid 2px #000;">
                            Net à payer au propriétaire:
                        </td>
                        <td class="bg-warning" style="border: solid 2px #000;">
                            <strong>= {{$house["net_to_paid"]}} fcfa </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-6"></div>
    </div><br>
    <p class="">Liste des locations de la maison</p>
    
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive shadow-lg p-3">
                <table id="myTable" class="table table-striped table-sm">
                    <h4 class="">Total: <strong class="text-red"> {{count($house['Locations'])}} </strong> </h4>

                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Locataire</th>
                            <th class="text-center">Téléphone</th>
                            <th class="text-center">Chambre</th>
                            <th class="text-center">Loyer Mensuel</th>
                            <th class="text-center">Mmois payé(s)</th>
                            <th class="text-center">Montant payé</th>
                            <th class="text-center">Dernier loyé</th>
                            <th class="text-center">Mois d'effet</th>
                            <th class="text-center">Date d'Intégration</th>
                            <th class="text-center text-red">Prorata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($house['Locations'] as $location)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center"> <button class="btn btn-sm btn-light"> <strong> {{$location["Locataire"]["name"]}} {{$location["Locataire"]["prenom"]}}</strong> </button> </td>
                            <td class="text-center">{{$location["Locataire"]["phone"]}}</td>
                            <td class="text-center">{{$location["Room"]["number"]}}</td>
                            <td class="text-center">{{$location["Room"]["total_amount"]}}</td>
                            <td class="text-center">{{$location["_locataire"]["nbr_month_paid_array"]}}</td>
                            <td class="text-center">{{$location["_locataire"]["nbr_facture_amount_paid_array"]}}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light shadow-lg"> <i class="bi bi-calendar-check-fill"></i> <strong> {{$location["latest_loyer_date"]}} </strong> </button>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light shadow-lg"> <i class="bi bi-calendar-check-fill"></i> <strong> {{$location["effet_date"]}} </strong> </button>
                            </td>
                            <td class="text-center"> <button class="btn btn-sm btn-light"> <i class="bi bi-calendar-check-fill"></i> <strong> {{$location["integration_date"]}} </strong> </button></td>
                            <td class="text-center"> <button class="btn btn-sm btn-light text-red"></i> <strong> {{$location->Locataire->prorata?$location->Locataire->prorata_date:"---"}} </strong> </button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br><br>

    <h4 class="text-red">Liste des états de la maison</h4>
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <h4 class="">Total: <strong class="text-red"> {{count($house["states"])}} </strong> </h4>

                <table id="myTable" class="table table-striped table-sm">
                    @if(count($house["states"])!=0)

                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Le responsable de l'arrêt</th>
                            <th class="text-center">Date d'arrêt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($house["states"] as $state)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index+1}}</td>
                            <td class="text-center"> {{$state["Owner"]["name"]}} </td>
                            <td class="text-center"> <span class="btn btn-sm p-1 bg-red">{{date("d/m/Y",strtotime($state["stats_stoped_day"]))}}</span> </td>
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