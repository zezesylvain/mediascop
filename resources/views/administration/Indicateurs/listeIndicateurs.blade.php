<div class="row">
    <div class="col-sm-12">
        <hr class="trait-bleu">
        <h3>{{$getChampTable ($getTableName($dbTable ('DBTBL_PROFILS')),$profilID)}}</h3>
        <hr class="trait-bleu">
    </div>
    @foreach($indicateurs as $r)
        @php($checked = in_array ($r['id'],$mesIndicateursList) ? "checked" : "")
        <div class="col-sm-3">
            <label for="indicateur-{{$r['id']}}">
                <input type="checkbox" name="indicateur-{{$r['id']}}" {{$checked}} onchange="sendData('etat='+this.checked+'&indicateur='+this.value+'&profil={{$profilID}}','{{route ("ajax.attribuerIndicateur")}}','attrIndicateur')" id="indicateur-{{$r['id']}}" value="{{$r['id']}}">
                {{$r['name']}}
            </label>
        </div>
    @endforeach
    <div id="attrIndicateur"></div>
</div>
