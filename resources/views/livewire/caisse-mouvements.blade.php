<div>
    <h6 class="">caisse :<em class="text-red"> {{$Account["name"]}} </em> </h6>
    <table id="myTable" class="table table-striped table-sm">
        <thead class="bg_dark">
            <tr>
                <th class="text-center">N°</th>
                <th class="text-center">Ancien solde</th>
                <th class="text-center">Crédité(s)</th>
                <th class="text-center">Débité(s)</th>
                <th class="text-center">Nouveau solde</th>
                <th class="text-center">Description</th>
                <th class="text-center">Status</th>
                <th class="text-center">Fait le:</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agencyAccountsSolds as $sold)
            <tr class="align-items-center">
                <td class="text-center">{{$loop->index+1}}</td>
                <td class="text-center">
                    <span class="shadow p-2 text-red">{{$sold["old_sold"]?$sold["old_sold"]:0}}</span>
                </td>
                <td class="text-center">{{$sold["sold_added"]?$sold["sold_added"]:0}}</td>
                <td class="text-center">{{$sold["sold_retrieved"]?$sold["sold_retrieved"]:0}}</td>
                <td class="text-center">
                    <span class="shadow p-2 @if($sold['visible']) text-success @else text-black @endif ">{{$sold["sold"]}}</span>
                </td>
                <td class="text-center">
                    <textarea name="" rows="1" id="" class="form-control">{{$sold["description"]}}</textarea>
                </td>
                <td class="text-center">
                    @if($sold["visible"])
                    <span class="shadow p-2 text-success">Actuel</span>
                    @else
                    <span class="shadow p-2 text-black">Ancien</span>
                    @endif
                </td>
                <td class="text-center">
                    <strong class="text-red"> {{Change_date_to_text($sold["created_at"])}}</strong>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>