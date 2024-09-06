<div>
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <h4 class="">Total: <strong class="text-red"> {{count($factures)}} </strong> </h4>
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">Superviseur</th>
                            <th class="text-center">Faturier</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Chambre</th>
                            <th class="text-center">Locataire</th>
                            <th class="text-center">Facture</th>
                            <th class="text-center">Montant</th>
                            <th class="text-center">Echéance</th>
                            <th class="text-center">Commentaire</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($factures as $facture)
                        <tr class="align-items-center">
                            <td class="text-center text-red"><button class="btn btn-sm btn-light"> {{$facture["Location"]["House"]["Supervisor"]["name"]}}</button></td>
                            <td class="text-center">{{$facture["Owner"]["name"]}}</td>
                            <td class="text-center"> <button class="btn btn-sm btn-light">{{$facture["Location"]["House"]["name"]}} </button> </td>
                            <td class="text-center">{{$facture["Location"]["Room"]["number"]}} </td>
                            <td class="text-center"><button class="btn btn-sm btn-light">{{$facture["Location"]["Locataire"]["name"]}} {{$facture["Location"]["Locataire"]["prenom"]}} </button> </td>
                            <td class="text-center"> <a target="__blank" href="{{$facture['facture']}}" class="btn btn-sm btn-light shadow-sm"><i class="bi bi-eye"></i></a>
                            </td>
                            <td class="text-center">{{$facture['amount']}}</td>
                            <td class="text-center text-red"><button class="btn btn-sm btn-light text-red"> <b>{{$facture['echeance_date']}} </b></button> </td>
                            <td class="text-center">
                                <textarea name="" rows="1" class="form-control" id="" placeholder="{{$facture->comments}}"></textarea>
                            </td>
                            <td class="text-center">{{$facture['Type']['name']}}</td>
                            <td class="text-center"><span class="@if($facture->status==2) bg-success @elseif($facture->status==3 || $facture->status==4)  bg-danger @else bg-warning @endif">{{$facture->Status->name}} </span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>