<div>
    <x-home>
        <form action="{{route('demandeReinitialisation')}}" method="POST" class="shadow-lg p-3 roundered bg-white animate__animated animate__bounce">
            @csrf
            <h5 class="text-center text-dark">Réinitialisation de compte</h5>
            <p class="">
                Entrer le <strong class="text-red">Code</strong> qui vous a été envoyé via votre adresse mail pour réinitialiser votre mot de passe
            </p>

            <div class="form-group">
                <div class="mb-3">
                    <span class="text-red">{{$pass_code_error}}</span>
                    <input type="text" wire:model="pass_code" name="pass_code" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Votre code  ....">
                </div>

                <div class="mb-3">
                    <span class="text-red">{{$new_password_error}}</span>
                    <input type="password" wire:model="new_password" name="new_password" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Votre nouveau mot de passe  ....">
                </div>
            </div>

            <br>
            <button type="submit" class="btn bg-dark w-100">CONFIRMER</button>
            <div class="my-2">
                <a href="/" class="text-red" style="text-decoration: none;">
                    <i class="bi bi-arrow-left-circle"></i> &nbsp;
                    Retour
                </a>
            </div>
        </form>
    </x-home>
</div>