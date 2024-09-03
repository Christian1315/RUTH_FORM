<div>
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive table-responsive-list shadow-lg p-3">
                <table id="myTable" class="table table-striped table-sm">
                    <h4 class="">Total: <strong class="text-red"> {{count($supervisors)}} </strong> </h4>

                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom/Prénom</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Date de création</th>
                            <th class="text-center">Agent Comptable</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supervisors as $supervisor)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center">{{$supervisor["name"]}}</td>
                            <td class="text-center">{{$supervisor["email"]}}</td>
                            <td class="text-center">{{$supervisor["phone"]}}</td>
                            <td class="text-center text-red"> <strong> <i class="bi bi-calendar2-check-fill"></i> {{date("d/m/Y",strtotime($supervisor["created_at"]))}} </strong> </th>
                            <td class="text-center text-red">
                                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal_{{$supervisor->id}}"><i class="bi bi-list-check"></i></button>
                                <!-- {{$supervisor->account_agents->first()?$supervisor->account_agents->first()->name:"---"}} -->
                            </td>
                            <td class="text-center">
                                <a target="__blank" href="{{route('user.AffectSupervisorToAccountyAgent',crypId($supervisor['id']))}}" class="btn text-dark btn-sm bg-light mx-1">Affecter à un agent comptable</button>
                            </td>
                        </tr>


                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal_{{$supervisor->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <p class="fs-5 text-center" id="exampleModalLabel">Superviseur: <strong class="text-red"> {{$supervisor->name}}</strong> </p>
                                    </div>
                                    <div class="modal-body">
                                        @if(count($supervisor->account_agents)>0)
                                        <ul class="list-group text-center">
                                            @foreach($supervisor->account_agents as $agent)
                                            <li class="list-group-item">{{$agent->name}}</li>
                                            @endforeach
                                        </ul>
                                        @else
                                        <p class="text-center">Aucun agent associé</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
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