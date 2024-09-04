<div>
    <div>
        <div class="d-flex header-bar">
            <h2 class="accordion-header">
                <button type="button" class="btn btn-sm bg-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="bi bi-cloud-plus-fill"></i> Ajouter un propriétaire
                </button>
            </h2>
        </div>
    </div>

    <!-- ADD PROPRIETOR -->
    <div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="">Ajout d'un propriétaire</p>
                    <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('proprietor._AddProprietor')}}" method="POST" class="shadow-lg p-3 animate__animated animate__bounce" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="agency" value="{{$current_agency['id']}}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="d-block">Nom</label>
                                    <input type="text" name="firstname" placeholder="Nom" value="{{old('firstname')}}" class="form-control">
                                    @error('firstname')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Prénom</label>
                                    <input type="text" name="lastname" value="{{old('lastname')}}" placeholder="Prénom" class="form-control">
                                    @error('lastname')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Prénom</label>
                                    <input type="phone" name="phone" value="{{old('phone')}}" placeholder="Téléphone" class="form-control">
                                    @error('phone')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Email</label>
                                    <input type="email" value="{{old('email')}}" placeholder="Adresse email" name="email" class="form-control">
                                    @error('email')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Sexe</label>
                                    <select class="form-select form-control" value="{{old('sexe')}}" name="sexe" aria-label="Default select example">
                                        <option value="Maxculin">Maxculin</option>
                                        <option value="Feminin">Feminin</option>
                                    </select>
                                    @error('sexe')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Numéro de pièce d'identité</label>
                                    <input type="text" name="piece_number" value="{{old('piece_number')}}" placeholder="Numéro de pièce d'identité" class="form-control">
                                    @error('piece_number')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="d-block">Télécharger le contrat de mandat</label>
                                    <input type="file" required value="{{old('mandate_contrat')}}" name="mandate_contrat" class="form-control">
                                    @error('mandate_contrat')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>

                                <div class="mb-3">
                                    <label for="" class="d-block">Adresse</label>
                                    <input type="text" value="{{old('adresse')}}" placeholder="Adresse" name="adresse" class="form-control">
                                    @error('adresse')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Pays</label>
                                    <select class="form-select form-control" name="country" aria-label="Default select example">
                                        <option>Pays</option>
                                        @foreach($countries as $countrie)
                                        @if($countrie['id']==4)
                                        <option value="{{$countrie['id']}}">{{$countrie['name']}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('country')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Ville/Commune</label>
                                    <select class="form-select form-control" name="city" aria-label="Default select example">
                                        <option>Ville/Commune</option>
                                        @foreach($cities as $citie)
                                        @if($citie['_country']['id'] == 4)
                                        <option value="{{$citie['id']}}" @if(old('city')==$citie->id) selected @endif>{{$citie['name']}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('city')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Type de carte ID</label>
                                    <select wire:model="card_type" class="form-select form-control" name="card_type" aria-label="Default select example">
                                        @foreach($card_types as $type)
                                        <option value="{{$type['id']}}">{{$type['name']}}</option>
                                        @endforeach
                                    </select>
                                    @error('city')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>

                                <div class="mb-3">
                                    <label for="" class="d-block">Commentaire</label>
                                    <textarea value="{{old('comments')}}" name="comments" class="form-control" placeholder="Laissez un commentaire ici" class="form-control" id=""></textarea>
                                    @error('comments')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn bg-red">Enregistrer</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <br>

    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-md-12">
            <h4 class="">Total: <strong class="text-red"> {{$proprietors_count}} </strong> </h4>
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Prénom</th>
                            <th class="text-center">Téléphone</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">N° Pièce</th>
                            <th class="text-center">Contrat</th>
                            <th class="text-center">Adresse</th>
                            <th class="text-center">Maisons</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proprietors as $proprietor)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index+1}}</td>
                            <td class="text-center">{{$proprietor["lastname"]}}</td>
                            <td class="text-center">{{$proprietor["firstname"]}}</td>
                            <td class="text-center">{{$proprietor["phone"]}}</td>
                            <td class="text-center">{{$proprietor["email"]}}</td>
                            <td class="text-center">{{$proprietor["piece_number"]}}</td>
                            <td class="text-center"><img src="{{$proprietor['mandate_contrat']}}" class="img-fluid" width="50px" height="50px" srcset="">
                            </td>
                            <td class="text-center">{{$proprietor["adresse"]}}</td>
                            <td class="text-center">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#show_houses_{{$proprietor['id']}}" class="btn btn-sm bg-warning">
                                    <i class="bi bi-eye-fill"></i> &nbsp; Voir
                                </button>
                            </td>
                            <td class="text-center d-flex">
                                <button class="btn btn-sm bg-warning" data-bs-toggle="modal" data-bs-target="#updateModal_{{$proprietor['id']}}"><i class="bi bi-person-lines-fill"></i> Modifier</button>
                                @if(session()->get("user"))
                                @if(session()->get("user")["is_master"] || session()->get("user")["is_admin"] || session()->get("user")["user_agency"])
                                <button class="btn btn-sm bg-red" wire:click="delete({{$proprietor['id']}})"><i class="bi bi-archive-fill"></i> &nbsp; Supprimer</button>
                                @else
                                <button disabled class="btn btn-sm bg-red"><i class="bi bi-archive-fill"></i> &nbsp; Supprimer(bloqué)</button>
                                @endif
                                @endif
                            </td>

                        </tr>
                        <!-- ###### MODEL DES MAISONS ###### -->
                        <div class="modal fade" id="show_houses_{{$proprietor['id']}}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title fs-5" id="exampleModalLabel">Propriétaire : <strong> <em class="text-red"> {{$proprietor['firstname']}} {{$proprietor['lastname']}} </em> </strong> </h6>
                                    </div>
                                    <div class="modal-body">
                                        <h6 class="">Total de maison: <em class="text-red"> {{count($proprietor->Houses)}}</em> </h6>

                                        <ul class="list-group">
                                            @foreach($proprietor->Houses as $house)
                                            <li class="list-group-item"><strong>Nom: </strong> {{ $house->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ###### MODEL DE MODIFICATION ###### -->
                        <div class="modal fade" id="updateModal_{{$proprietor['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title fs-5" id="exampleModalLabel">Modifier <strong> <em class="text-red"> {{$proprietor['firstname']}} {{$proprietor['lastname']}} </em> </strong> </h6>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('proprietor.UpdateProprietor',$proprietor['id'])}}" method="post" class="shadow-lg p-3 animate__animated animate__bounce">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <span>Nom</span>
                                                        <input value="{{old('lastname')?old('lastname'):$proprietor['lastname']}}" name="lastname" placeholder="Lastname" class="form-control">
                                                        @error("lastname")
                                                            <span class="text-red"> {{ $message }} </span>
                                                        @enderror
                                                    </div><br>
                                                    <div class="mb-3">
                                                        <span class="">Prénom</span>
                                                        <input value="{{old('firstname')?old('firstname'):$proprietor['firstname']}}" type="text" name="firstname" placeholder="Firstname" class="form-control">
                                                        @error("firstname")
                                                            <span class="text-red"> {{ $message }} </span>
                                                        @enderror
                                                    </div><br>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <span>Téléphone</span>
                                                        <input value="{{old('phone')?old('phone'):$proprietor['phone']}}" type="phone" name="phone" placeholder="Phone" class="form-control">
                                                        @error("firstname")
                                                            <span class="text-red"> {{ $message }} </span>
                                                        @enderror
                                                    </div><br>
                                                    <div class="mb-3">
                                                        <span>Adresse mail</span>
                                                        <input value="{{old('email')?old('email'):$proprietor['email']}}" type="text" placeholder="Email..." name="email" class="form-control">
                                                        @error("email")
                                                            <span class="text-red"> {{ $message }} </span>
                                                        @enderror
                                                    </div><br>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <span>Adresse</span>
                                                        <input value="{{old('adresse')?old('adresse'):$proprietor['adresse']}}" type="text" placeholder="Adresse ..." name="adresse" class="form-control">
                                                        @error("adresse")
                                                            <span class="text-red"> {{ $message }} </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm bg-red" data-bs-dismiss="modal">Fermer</button>
                                                <button type="submit" class="btn btn-sm bg-dark">Modifier</button>
                                            </div>
                                        </form>
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