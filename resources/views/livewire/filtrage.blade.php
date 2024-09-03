<div>
    <div class="">
        <p class="text-center text-red"> {{$generalError}} </p>
        <p class="text-center text-success"> {{$generaleSuccess}} </p>
    </div>

    <!-- LISTE DES FACTURES -->
    @if($show_factures)
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <h4 class="">Total: <strong class="text-red"> {{count($factures)}} </strong> </h4>
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Faturier</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Chambre</th>
                            <th class="text-center">Locataire</th>
                            <th class="text-center">Facture</th>
                            <th class="text-center">Montant</th>
                            <th class="text-center">Commentaire</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    @if(count($factures)!=0)
                    <tbody>
                        @foreach($factures as $facture)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index+1}}</td>
                            <td class="text-center">{{$facture["owner"]["name"]}}</td>
                            <td class="text-center">{{$facture["location"]["house"]["name"]}}</td>
                            <td class="text-center">{{$facture["location"]["room"]["number"]}} </td>
                            <td class="text-center">{{$facture["location"]["locataire"]["name"]}} {{$facture["location"]["locataire"]["prenom"]}}</td>
                            <td class="text-center"><img src="{{$facture['facture']}}" class="img-fluid" width="50px" srcset="">
                            </td>
                            <td class="text-center">{{$facture['amount']}}</td>
                            <td class="text-center">
                                <textarea name="" class="form-control" id=""> {{$facture['comments']}} </textarea>
                            </td>
                            <td class="text-center">{{$facture['type']['name']}}</td>
                            <td class="text-center"> <button class="bg-success btn btn-sm">{{$facture['status']['name']}}</button> </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <p class="text-center text-red">Aucune facture disponible!</p>
                    @endif
                </table>
            </div>
            <!-- pagination -->
            <div class="justify-center">
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
    @endif

    <!-- LISTE DES LOCATAIRES DEMENAGE -->
    @if($show_moved_locators)
    <table class="table table-striped table-sm">
        <h4 class="">Total: <strong class="text-red"> {{count($moved_locators)}} </strong> </h4>

        @if(count($moved_locators)>0)
        <thead class="bg_dark">
            <tr>
                <th class="text-center">N°</th>
                <th class="text-center">Nom</th>
                <th class="text-center">Prénom</th>
                <th class="text-center">Email</th>
                <th class="text-center">Numéro de pièce</th>
                <th class="text-center">Phone</th>
                <th class="text-center">Adresse</th>
            </tr>
        </thead>
        <tbody>
            @foreach($moved_locators as $locator)
            <tr class="align-items-center">
                <td class="text-center">{{$loop->index + 1}}</td>
                <td class="text-center">{{$locator["name"]}}</td>
                <td class="text-center">{{$locator["prenom"]}}</td>
                <td class="text-center">{{$locator["email"]}}</td>
                <td class="text-center">{{$locator["piece_number"]}}</td>
                <td class="text-center">{{$locator["phone"]}}</td>
                <td class="text-center">{{$locator["adresse"]}}</td>
            </tr>
            @endforeach
        </tbody>
        @else
        <p class="text-center text-red">Aucun locataire n'a été ajouté!</p>
        @endif
    </table>
    @endif

    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <h4 class="">Bilan de cette agence </h4>
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">Nbr de propriétaires</th>
                            <th class="text-center">Nbr de maisons</th>
                            <th class="text-center">Nbr de locataires</th>
                            <th class="text-center">Nbr de locataires demenagé</th>
                            <th class="text-center">Nbr de locations</th>
                            <th class="text-center">Nbr de chambres</th>
                            <th class="text-center">Facture</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="align-items-center">
                            <td class="text-center">{{count($proprietors)}}</td>
                            <td class="text-center">{{count($houses)}}</td>
                            <td class="text-center">{{count($locators)}}</td>
                            <td class="text-center">
                                <button wire:click="ShowMovedLocators" class="btn btn-sm  shadow-lg bg-red"> {{count($moved_locators)}} @if($show_moved_locators)<i class="bi bi-eye-slash"></i> Fermer @else <i class="bi bi-eye-fill"></i> Voir @endif </button>
                            </td>
                            <td class="text-center">{{count($locations)}}</td>
                            <td class="text-center">{{count($rooms)}}</td>
                            <td class="text-center">
                                <button wire:click="ShowFactures" class="btn btn-sm  shadow-lg bg-red"> {{count($factures)}} @if($show_factures)<i class="bi bi-eye-slash"></i> Fermer @else <i class="bi bi-eye-fill"></i> Voir @endif </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bg-warning p-3"> <strong>Montant total en facture: </strong> </td>
                            <td class="p-3 bg-red">= {{array_sum($factures_total_amount)}} fcfa </td>
                        </tr>
                    </tbody>

                </table>
            </div>
            <!-- pagination -->
            <div class="justify-center">
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