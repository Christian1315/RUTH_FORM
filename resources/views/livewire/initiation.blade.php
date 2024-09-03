<div>
    <br>
    <div class="">
        <p class="text-center text-red"> {{$generalError}} </p>
        <p class="text-center text-success"> {{$generalSuccess}} </p>
    </div>

    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive table-responsive-list shadow-lg">
                <table class="table table-striped table-sm shadow-lg">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Manager</th>
                            <th class="text-center">Propriétaire</th>
                            <th class="text-center">Montant</th>
                            <th class="text-center">commentaire</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    @if($initiations_count>0)
                    <tbody>
                        @foreach($initiations as $initiation)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center">{{$initiation['manager']['name']}}</td>
                            <td class="text-center">{{$initiation['proprietor']['lastname']}} {{$initiation['proprietor']['firstname']}}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-success">{{$initiation['amount']}}</button>
                            </td>
                            <td class="text-center">
                                <textarea name="" class="form-control" id="">{{$initiation['comments']}}</textarea>
                            </td>
                            <td class="text-center">
                                @if($initiation['status']['id']==2)
                                <button class="btn btn-sm btn-success">{{$initiation['status']['name']}}</button>
                                @else
                                <button class="btn btn-sm bg-red">{{$initiation['status']['name']}}</button>
                                @endif
                            </td>
                            <td class="text-center text-red"> <strong>{{ date("d/m/Y",strtotime($initiation['created_at']))}}</strong> </td>

                            <td class="text-center">
                                @if(session()->get("user"))
                                @if(session()->get("user")["is_admin"] || session()->get("user")["is_master"])
                                <button wire:click="validate_Initiation({{$initiation['id']}})" class="btn btn-sm bg-warning">Valider</button>
                                @else
                                <button disabled class="btn btn-sm bg-warning">Valider</button>
                                @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <p class="text-center text-red">Aucune initiation de paiement</p>
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