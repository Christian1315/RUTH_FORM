<div>
    <!-- TABLEAU DE LISTE -->
    <div class="row mb-5">
        <div class="col-md-3"></div>
        <div class="col-6">
            <div class="text-center">
                <p class="text-red"> {{$generalError}} </p>
                <p class="text-success"> {{$generalSuccess}} </p>
            </div>
            <p class="text-center">Selectionnez une date pour filtrer les locataires <strong class="text-red">ayant payés</strong> </p>
            <form wire:submit="filtreByDate" action="">
                <input type="date" required name="date" wire:model="date" class="form-control">
                <button class="btn btn sm bg-red w-100">Chercher</button>
            </form>
        </div>
        <div class="col-md-3"></div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive table-responsive-list shadow-lg">
                <p class="text">Liste des locataires ayant payé à la date </p>
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