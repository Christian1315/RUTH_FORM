<div>
    <div class="text-center">
        <p class="text-red"> {{$generalError}} </p>
        <p class="text-success"> {{$generaleSuccess}} </p>
    </div>
    <button wire:click="displayTauxOptions" class="btn btn-sm bg-light text-uppercase"><i class="bi bi-file-earmark-pdf-fill"></i>@if($display_taux_options) Fermer @else Génerer les états des taux @endif</button> &nbsp;

    @if($display_taux_options)
    <button wire:click="showGenerateTaux" class="btn btn-sm bg-light d-block"><i class="bi bi-house-dash"></i> Pour cette agence</button>
    <button wire:click="ShowGenerateTauxBySupervisorForm" class="btn btn-sm bg-light d-block"><i class="bi bi-people"></i> @if($generate_caution_by_supervisor) Fermer @else Par Sperviseur @endif</button>
    <button wire:click="ShowGenerateTauxByHouseForm" class="btn btn-sm bg-light d-block"><i class="bi bi-house-check-fill"></i>@if($generate_taux_by_house) Fermer @else Par maison @endif </button>
    @endif

    @if($generate_taux_by_supervisor)
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-6">
                <div class="shadow p-2">
                    <form wire:submit="GenerateTauxBySupervisor">
                        <div class="row">
                            <div class="col-md-12">
                                <select required wire:model="supervisor" name="supervisor" class="form-control">
                                    <option>Choisissez un superviseur</option>
                                    @foreach($supervisors as $supervisor)
                                    <option value="{{$supervisor['id']}}"> {{$supervisor["name"]}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span>Date de début</span>
                                <input required wire:model="start_date" type="date" name="start_date" class="form-control" id="">
                            </div>
                            <div class="col-md-6">
                                <span>Date de fin</span>
                                <input required wire:model="end_date" type="date" name="end_date" class="form-control" id="">
                            </div>
                        </div>
                        <br>
                        <div class="text-center">
                            <button class="w-100 text-center bg-red btn btn-sm">Génerer</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
    @endif

    @if($generate_taux_by_house)
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-6">
                <div class="shadow p-2">
                    <form wire:submit="GenerateTauxByHouse">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="text-red">{{$house_error}}</span>
                                <select required wire:model="house" name="house" class="form-control">
                                    <option>Choisissez une maison</option>
                                    @foreach($houses as $house)
                                    <option value="{{$house['id']}}"> {{$house["name"]}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span>Date de début</span>
                                <input required wire:model="start_date" type="date" name="start_date" class="form-control" id="">
                            </div>
                            <div class="col-md-6">
                                <span>Date de fin</span>
                                <input required wire:model="end_date" type="date" name="end_date" class="form-control" id="">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="text-center">
                            <button class="w-100 text-center bg-red btn btn-sm">Génerer</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
    @endif

    @if($showTaux)
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="alert bg-dark text-white">
                    Taux générés avec succès! Cliquez sur le lien ci-dessous pour la télécharger: <br>
                    <a class="text-red" href="{{$taux_link}}" target="_blank" rel="noopener noreferrer">Télécharger</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <br><br><br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive table-responsive-list shadow-lg">
                <p class="text">Locataires <strong class="text-red"> ayant payé</strong> après l’arrêt des différents états à la <strong class="text-red"> date d'écheance 05 ou 10 </strong> </p>

                <table class="table table-striped table-sm">
                    <h4 class="">Total: <strong class="text-red"> {{count($locators)}} </strong> </h4>
                    @if(count($locators)>0)
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Prénom</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Adresse</th>
                            <th class="text-center">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locators as $locator)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index+1}}</td>
                            <td class="text-center">{{$locator["name"]}}</td>
                            <td class="text-center">{{$locator["prenom"]}}</td>
                            <td class="text-center">{{$locator["phone"]}}</td>
                            <td class="text-center">{{$locator["adresse"]}}</td>
                            <td class="text-center">{{$locator["email"]}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <p class="text-center text-red">Aucun locataire</p>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>