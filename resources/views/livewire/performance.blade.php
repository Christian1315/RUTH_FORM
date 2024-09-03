<div>
    <button wire:click="displayTauxOptions" class="btn btn-sm bg-light text-uppercase"><i class="bi bi-file-earmark-pdf-fill"></i>@if($display_taux_options) Fermer @else Filtrer les taux de performance @endif</button> &nbsp;

    @if($display_taux_options)
    <button wire:click="ShowGeneratePerformanceBySupervisorForm" class="btn btn-sm bg-light d-block"><i class="bi bi-people"></i> @if($generate_caution_by_supervisor) Fermer @else Par Sperviseur @endif</button>
    <button wire:click="ShowGeneratePerformanceByHouseForm" class="btn btn-sm bg-light d-block"><i class="bi bi-house-check-fill"></i>@if($generate_taux_by_house) Fermer @else Par maison @endif </button>
    @endif

    <br>
    <div class="">
        <p class="text-center text-red"> {{$generalError}} </p>
        <p class="text-center text-success"> {{$generalSuccess}} </p>
    </div>
    <br>
    @if($generate_taux_by_supervisor)
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-6">
                <div class="shadow p-2">
                    <form wire:submit="GeneratePerformanceBySupervisor">
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
                            <div class="col-md-12">
                                <span>Le mois</span>
                                <input required wire:model="month" type="date" name="month" class="form-control" id="">
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
                    <form wire:submit="GeneratePerformanceByHouse">
                        <div class="row">
                            <div class="col-md-12">
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
                            <div class="col-md-12">
                                <span>Mois</span>
                                <input required wire:model="month" type="date" name="month" class="form-control" id="">
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

    <br><br>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive table-responsive-list shadow-lg">
                <table class="table table-striped table-sm">
                    @if(count($houses)>0)
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Nbre total de chambre</th>
                            <th class="text-center">Nbre de chambre vide en debut de mois</th>
                            <th class="text-center">Nbre de chambre vide actuel</th>
                            <th class="text-center">Nbre de chambre occupées</th>
                            <th class="text-center">Nbre de chambre restant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($houses as $house)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center">{{$house["name"]}}</td>
                            <td class="text-center"><strong>{{count($house["rooms"])}} </strong> </td>
                            <td class="text-center">{{count($house["frees_rooms_at_first_month"])}}</td>
                            <td class="text-center"><strong class="text-success"> {{count($house["frees_rooms"])}} </strong> </td>
                            <td class="text-center"> <strong class="text-red"> {{count($house["busy_rooms"])}}</strong></td>
                            <td class="text-center"> <strong class="text-success">{{count($house["frees_rooms"])}} </strong> </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-red text-center"> Aucune maison disponible !</p>
                @endif
            </div>
            <table>
                <tbody>
                    <tr class="text-center" style="margin-top: 20px!important;">
                        <td></td>
                        <td></td>
                        <br>
                        <td colspan="3" class="bg-warning py-5">
                            Total =<em class="">(Nombre de chambres occupées <strong class="text-red"> ({{count($all_busy_rooms)}} )</strong> / Nombre de chambre Vide <strong class="text-red"> ({{count($all_frees_rooms_at_first_month)}} )</strong> )*100 </em>= <strong>{{Calcul_Perfomance(count($all_busy_rooms),count($all_frees_rooms_at_first_month))}}</strong>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>