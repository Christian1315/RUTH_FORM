<div>

    <div class="d-flex header-bar">
        <button class="btn btn-sm bg-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <i class="bi bi-cloud-plus-fill"></i> Ajouter un droit
        </button>
    </div>

    <br>
    @if($retrieveRightForm)
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <form wire:submit="RetrieveRight" class="shadow-lg p-3 animate__animated animate__bounce">
                    <h4>- Rétrait de droit << {{$currentActiveRigtId}}>> au user</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="text-red"> {{$user_error}} </span>
                            <select required wire:model="user" required name="user" class="form-select mb-3 form-control">
                                <option>Choisir un utilisateur</option>
                                @foreach($users as $user)
                                <option value="{{$user['id']}}">{{$user['name']}} -- ({{$user['username']}}) </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-center">
                        <button type="submit" class="btn bg-red">Affecter</button>
                    </div>
                </form>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
    @endif

    <!-- ADD RIGHT -->
    <div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="">Ajout d'un droit</p>
                    <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('user.CreateRight')}}" method="POST" class="shadow-lg p-3 animate__animated animate__bounce" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <select name="action" class="form-select mb-3 form-control">
                                    <option>Choisir une action</option>
                                    @foreach($actions as $action)
                                    <option value="{{$action['id']}}" @if(old('action')==$action['id']) selected @endif>{{$action['name']}} -- (<span class="text-red">{{$action['description']}}</span>) </option>
                                    @endforeach
                                </select>
                                @error("action")
                                <span class="text-red"> {{$message}} </span>
                                @enderror
                                <select name="rang" class="form-select mb-3 form-control">
                                    <option>Choisir un rang</option>
                                    @foreach($rangs as $rang)
                                    <option value="{{$rang['id']}}" @if(old('action')==$rang['id']) selected @endif>{{$rang['name']}} -- (<span class="text-red">{{$rang['description']}}</span>) </option>
                                    @endforeach
                                </select>
                                @error("rang")
                                <span class="text-red"> {{$message}} </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <select name="profil" class="form-select mb-3 form-control">
                                    <option>Choisir un profil</option>
                                    @foreach($profils as $profil)
                                    <option value="{{$profil['id']}}" @if(old('profil')==$profil['id']) selected @endif> {{$profil['name']}} -- (<span class="text-red">{{$profil['description']}}</span>) </option>
                                    @endforeach
                                </select>
                                @error("profil")
                                <span class="text-red"> {{$message}} </span>
                                @enderror

                                <br>
                                <textarea name="description" value="{{old('description')}}" placeholder="Description ...." class="form-control"></textarea>
                                @error("description")
                                <span class="text-red"> {{$message}} </span>
                                @enderror
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
        <div class="col-12">
            <h4 class="">Total: <strong class="text-red"> {{count($rights)}} </strong> </h4>
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Profil</th>
                            <th class="text-center">Rang</th>
                            <th class="text-center">Action</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Affectation</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rights as $right)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index +1}}</td>
                            <td class="text-center">{{$right->_profil->name}}</td>
                            <td class="text-center">{{$right["_rang"]["name"]}}</td>
                            <td class="text-center">{{$right["_action"]["name"]}}</td>
                            <td class="text-center text-red">
                                <textarea name="" rows="1" class="form-control" placeholder="{{$right['description']}}"></textarea>
                            </td>
                            <td class="text-center">
                                <button class="btn bg-red btn-sm" data-bs-toggle="modal" data-bs-target="#affectModal_{{$right->id}}"><i class="bi bi-link-45deg"></i> Affecter</button>
                                <button class="btn bg-light btn-sm" data-bs-toggle="modal" data-bs-target="#detachModal_{{$right->id}}"><i class="bi bi-link-45deg"></i> - Rétirer </button>
                            </td>
                        </tr>

                        <!-- AFFECT RIGHT -->
                        <div class="modal fade" id="affectModal_{{$right->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <p class="modal-title fs-5" id="exampleModalLabel">Droit: <strong class="text-danger">{{$right->description}} </strong> </p>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('user.AttachRightToUser',crypId($right->id))}}" method="post">
                                            @csrf
                                            <select required name="user_id" class="form-select mb-3 form-control">
                                                <option>Choisir un utilisateur</option>
                                                @foreach($users as $user)
                                                <option value="{{$user['id']}}" @if(old("user")==$user['id']) selected @endif>{{$user['name']}} -- ({{$user['username']}}) </option>
                                                @endforeach
                                            </select>
    
                                            @error("user")
                                            <span class="text-red"> {{$message}} </span>
                                            @enderror
                                            <hr>
                                            <button type="submit" class="btn btn-sm bg-red"><i class="bi bi-check-circle"></i> Affecter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DETTACH RIGHT -->
                        <div class="modal fade" id="detachModal_{{$right->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <p class="modal-title fs-5" id="exampleModalLabel">Droit: <strong class="text-danger">{{$right->description}} </strong> </p>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('user.DesAttachRightToUser',crypId($right->id))}}" method="post">
                                            @csrf
                                            <select required name="user_id" class="form-select mb-3 form-control">
                                                <option>Choisir un utilisateur</option>
                                                @foreach($users as $user)
                                                <option value="{{$user['id']}}" @if(old("user")==$user['id']) selected @endif>{{$user['name']}} -- ({{$user['username']}}) </option>
                                                @endforeach
                                            </select>
    
                                            @error("user")
                                            <span class="text-red"> {{$message}} </span>
                                            @enderror
                                            <hr>
                                            <button type="submit" class="btn btn-sm bg-red"><i class="bi bi-check-circle"></i> Retirer</button>
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