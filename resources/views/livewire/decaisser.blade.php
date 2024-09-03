<div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">

                <form action="{{route('sold._DeCreditateAccount')}}" method="POST" class="shadow-lg p-3 animate__animated animate__bounce">
                    @csrf
                    <h5 class="">Décaisser une caisse</h5>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label>Choisissez la caisse en occurrence</label>
                                <select name="agency_account" class="form-select form-control" aria-label="Default select example">
                                    @foreach($agencyAccounts as $agency_account)
                                    @if($agency_account["_Account"]['id']!=4 && $agency_account["_Account"]['id']!=9 && $agency_account["_Account"]['id']!=5)

                                    <!-- seul un admin ou un master peut crediter la caisse CDR -->
                                    @if($agency_account['id']==3)
                                    @if(auth())
                                    @if(auth()->user()->is_master || auth()->user()->is_admin)
                                    <option value="{{$agency_account['id']}}"> {{$agency_account["_Account"]["name"]}} --- <em class="text-danger">( solde actuel: @if($agency_account->AgencyCurrentSold){{$agency_account->AgencyCurrentSold->sold}} @else 0 @endif)</em> </option>
                                    @endif
                                    @endif
                                    @else
                                    <option value="{{$agency_account['id']}}">{{$agency_account["_Account"]["name"]}} --- <em class="text-danger">( solde actuel: @if($agency_account->AgencyCurrentSold) {{$agency_account->AgencyCurrentSold->sold}} @else 0 @endif)</em> </option>
                                    @endif
                                    @endif
                                    @endforeach
                                </select>
                                @error("agency_account")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <span class="text-red"> Ce champ est réquis seulement pour la caisse <em class="text-red"> CDR </em> </span>
                                <select name="house" class="form-select form-control" aria-label="Default select example">
                                    <option>**Choisir une maison</option>
                                    @foreach($houses as $house)
                                    <option value="{{$house['id']}}">{{$house["name"]}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <input type="number" name="sold" required value="{{old('sold')}}" placeholder="Précisez le montant ...." class="form-control" id="">
                                @error("old")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <textarea name="description" required value="{{old('description')}}" rows="1" class="form-control" placeholder="La description ...."></textarea>
                                @error("description")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-red btn-sm"><i class="bi bi-currency-exchange"></i> Décaisser</button>
                    </div>
                </form>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
</div>