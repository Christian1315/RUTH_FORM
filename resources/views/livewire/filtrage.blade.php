<div>
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
                                <button data-bs-toggle="modal" data-bs-target="#ShowMovedLocators" class="btn btn-sm  shadow-lg bg-red"> {{count($moved_locators)}} @if($show_moved_locators)<i class="bi bi-eye-slash"></i> Fermer @else <i class="bi bi-eye-fill"></i> Voir @endif </button>
                            </td>
                            <td class="text-center">{{count($locations)}}</td>
                            <td class="text-center">{{count($rooms)}}</td>
                            <td class="text-center">
                                <button data-bs-toggle="modal" data-bs-target="#ShowFactures" class="btn btn-sm  shadow-lg bg-red"> {{count($factures)}} <i class="bi bi-eye-fill"></i> Voir</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bg-warning p-3"> <strong>Montant total en facture: </strong> </td>
                            <td class="p-3 bg-red">= {{array_sum($factures_total_amount)}} fcfa </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- MODAL DES FACTURES -->
    <div class="modal fade" id="ShowFactures" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 class="">Total des factures: <strong class="text-red"> {{count($factures)}} </strong> </h4>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-sm">
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($factures as $facture)
                                <tr class="align-items-center">
                                    <td class="text-center">{{$loop->index+1}}</td>
                                    <td class="text-center">{{$facture["Owner"]["name"]}}</td>
                                    <td class="text-center">{{$facture["Location"]["House"]["name"]}}</td>
                                    <td class="text-center">{{$facture["Location"]["Room"]["number"]}} </td>
                                    <td class="text-center">{{$facture["Location"]["Locataire"]["name"]}} {{$facture["Location"]["Locataire"]["prenom"]}}</td>
                                    <td class="text-center">
                                        <a target="_blank" href="{{$facture['facture']}}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i> </a>
                                    </td>
                                    <td class="text-center">{{$facture['amount']}}</td>
                                    <td class="text-center">
                                        <textarea name="" rows="1" class="form-control" placeholder="{{$facture['comments']}}"></textarea>
                                    </td>
                                    <td class="text-center">{{$facture['Type']['name']}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DES LOCATAIRES DEMENAGES -->
    <div class="modal fade" id="ShowMovedLocators" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <table class="table table-striped table-sm">
                        <h4 class="">Locataires demenagés: <strong class="text-red"> {{count($moved_locators)}} </strong> </h4>

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
                </div>
            </div>
        </div>
    </div>
</div>