<div>

    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm shadow-lg">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Arrêt</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Propriétaire</th>
                            <th class="text-center">Montant</th>
                            <th class="text-center">Commentaire</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($initiations as $initiation)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center"> <strong class="text-dark shadow btn">{{$initiation->House->States->last()->stats_stoped_day}} </strong></td>
                            <td class="text-center"> <strong class="text-red shadow btn">{{$initiation->House->name}} </strong></td>
                            <td class="text-center">{{$initiation->House->Proprietor->lastname}} {{$initiation->House->Proprietor->firstname}}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light text-red"><i class="bi bi-currency-exchange"></i> {{$initiation['amount']}}</button>
                            </td>
                            <td class="text-center">
                                <textarea name="" rows="1" class="form-control" id="">{{$initiation['comments']}}</textarea>
                            </td>
                            <td class="text-center">
                                <span class="btn btn-sm @if($initiation['Status']['id']==2) btn-success @else bg-red  @endif" @if($initiation['Status']['id']==3) title="{{$initiation->rejet_comments}}" @elseif($initiation['Status']['id']==2) disabled @endif> @if($initiation->Status->id==3) <i class="bi bi-eye"></i> @endif {{$initiation->Status->name}}</span>
                            </td>
                            <td class="text-center d-flex">
                                @if($initiation['Status']["id"]==2)
                                <span class="text-success">Validé déjà</span>
                                @elseif($initiation['Status']["id"]==3)
                                <span class="text-success">Déjà rejetée</span>
                                @elseif($initiation['Status']["id"]==1)
                                <a href="{{route('payement_initiation.ValidePaiementInitiation',crypId($initiation->id))}}" class="btn btn-sm btn-success" title="Valider"><i class="bi bi-check-circle"></i> </a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#payement_rejet_{{$initiation->id}}" class="btn btn-sm btn-danger" title="Rejeter"><i class="bi bi-x-circle"></i> </a>
                                @endif
                            </td>
                        </tr>

                        <!-- REJET D'UN PAIEMENT -->
                        <div class="modal fade" id="payement_rejet_{{$initiation->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p class="">Rejet de paiement</p>
                                        <form action="{{route('payement_initiation.RejetPayementInitiation',crypId($initiation->id))}}" method="post">
                                            @csrf
                                            <textarea required name="rejet_comments" value="{{old('rejet_comments')}}" class="form-control" placeholder="Laissez un commentaire"></textarea>
                                            <button type="submit" class="btn btn-sm bg-red ùt-1"><i class="bi bi-x-circle"></i> Rejeter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>

</div>