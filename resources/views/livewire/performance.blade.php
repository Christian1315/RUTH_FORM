<div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Nbre total de chambre</th>
                            <th class="text-center">Superviseur</th>
                            <th class="text-center">Nbre de chambre vide en debut de mois</th>
                            <th class="text-center">Nbre de chambre vide actuel</th>
                            <th class="text-center">Nbre de chambre occupées</th>
                            <th class="text-center">Nbre de chambre restant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($houses as $house)
                        <tr class="align-items-center">
                            <td class="text-center"><button class="btn btn-sm btn-light">{{$house["name"]}} </button> </td>
                            <td class="text-center"><strong>{{count($house["rooms"])}} </strong> </td>
                            <td class="text-center"><button class="btn btn-sm text-red btn-light">{{$house->Supervisor->name}} </button> </td>
                            <td class="text-center">{{count($house["frees_rooms_at_first_month"])}}</td>
                            <td class="text-center"><strong class="text-success"> {{count($house["frees_rooms"])}} </strong> </td>
                            <td class="text-center"> <strong class="text-red"> {{count($house["busy_rooms"])}}</strong></td>
                            <td class="text-center"> <strong class="text-success">{{count($house["frees_rooms"])}} </strong> </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <table>
                <tbody>
                    <tr class="text-center" style="margin-top: 20px!important;">
                        <td></td>
                        <td></td>
                        <br>
                        <td colspan="3" class="bg-warning py-5">
                            Performance =<em class="">(Nombre de chambres occupées <strong class="text-red"> ({{count($all_busy_rooms)}} )</strong> / Nombre de chambre Vide <strong class="text-red"> ({{count($all_frees_rooms_at_first_month)}} )</strong> )*100 </em>= <strong>{{Calcul_Perfomance(count($all_busy_rooms),count($all_frees_rooms_at_first_month))}}</strong>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>