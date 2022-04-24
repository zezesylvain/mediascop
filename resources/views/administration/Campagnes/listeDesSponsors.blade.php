<div class="wizard-big form-group-inner" id="">
    <label for="listSponsors">Sponsors</label>
    <select class="form-control" multiple  name="listeSponsors[]" id="listSponsors" ondblclick="sendData('annonceur='+this.value+'&idCampTitle={{$idcamptitle}}&v=out','{{route ('ajax.ajouterSponsors')}}','sponsorsItem')">
        @foreach($listeDesSponsors as $r)
            <option value="{{$r['id']}}">{{$r['raisonsociale']}}</option>
        @endforeach
    </select>
</div>
