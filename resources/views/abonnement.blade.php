<x-home>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <h5 class="text-center">Abonnement</h5>
            <form action="{{route('abonnement')}}" method="POST" class="shadow-lg p-2 rounded" action="subscribe" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" value="{{old('nom')}}" name="nom" class="form-control" id="nom" placeholder="GOGO ">
                    @error("email")
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prenom</label>
                    <input type="text" value="{{old('prenom')}}" name="prenom" class="form-control" id="prenom" placeholder="Christ">
                    @error("email")
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="code" class="form-label">Code</label>
                    <input type="text" value="{{old('code')}}" name="code" class="form-control" id="code" placeholder="##4567">
                    @error("email")
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" value="{{old('email')}}" name="email" class="form-control" id="email" placeholder="christ@gmail.com">
                    @error("email")
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="">** Type d'abonnement **</label>
                    <select class="form-select form-control" name="type" aria-label="Default select example">
                        <option value="Canal +">Canal +</option>
                        <option value="Canal Prémium">Canal premium</option>
                    </select>
                    @error("type")
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="">** Mode de paiement **</label>
                    <select class="form-select form-control" name="mode" aria-label="Default select example">
                        <option value="Carte">Carte</option>
                        <option value="PayPal">PayPal</option>
                    </select>
                    @error("mode")
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-sm btn-dark w-100"><i class="bi bi-check-circle"></i> Envoyer</button>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</x-home>