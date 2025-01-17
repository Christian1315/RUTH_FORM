<x-home>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <h5 class="text-center">Inscription</h5>

            <form action="{{route('subscribe')}}" method="POST" class="shadow-lg p-2 rounded" action="subscribe" method="POST">
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
                <button type="submit" class="btn btn-sm btn-dark w-100"><i class="bi bi-check-circle"></i> Envoyer</button>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</x-home>