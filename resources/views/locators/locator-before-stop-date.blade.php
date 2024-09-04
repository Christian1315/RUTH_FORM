<x-templates.agency :title="'Locataires'" :active="'statistique'" :agency="$house->_Agency">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Locataires <span class="text-red">ayant payés avant</span> arrêt des états</h1>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12">
            <h3 class="">Maison : {{$house['name']}} </h3>
            <br>
            <h6 class=""> Montant total: <em class="text-red"> {{$locationsFiltered["beforeStopDateTotal_to_paid"]}} </em> </h6>

            <div class="table-responsive shadow-lg p-3">
                <table id="myTable" class="table table-striped table-sm shadow-lg p-3">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Prénom</th>
                            <th class="text-center">Phone </th>
                            <th class="text-center">Adresse</th>
                            <th class="text-center">Mois</th>
                            <th class="text-center">Montant payé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locationsFiltered['beforeStopDate'] as $locator)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center">{{$locator["name"]}}</td>
                            <td class="text-center">{{$locator["prenom"]}}</td>
                            <td class="text-center">{{$locator["phone"]}}</td>
                            <td class="text-center">{{$locator["adresse"]}}</td>
                            <td class="text-center"> <button class="btn btn-sm btn-light text-red"><i class="bi bi-calendar2-check"></i> {{$locator["month"]}}</button> </td>
                            <td class="text-center"> <button class="btn btn-sm btn-light text-red">{{$locator["amount_paid"]}}</button> </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-templates.agency>