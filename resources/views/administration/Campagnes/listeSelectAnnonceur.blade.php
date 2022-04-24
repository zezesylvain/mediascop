<div class="chosen-select-single mg-b-20 form-group">
    <label  for="annonceurfilteroperation">Annonceur</label>
    <select name="annonceurfilteroperation" id="annonceurfilteroperation" data-placeholder="" class="chosen-select form-control" tabindex="-1" onchange="sendData('annonceur='+this.value,'{{route ('ajax.listSelectOperation')}}','opfilteroperationitem')">
        <option value="">Choisir un annonceur</option>
        @foreach($listeDesAnnonceurs as $r)
            <option value="{{$r['id']}}">{{$r['raisonsociale']}}</option>
        @endforeach
    </select>
</div>
