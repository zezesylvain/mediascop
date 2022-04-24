<label for="listAnnonceur">Annonceurs</label>
<select class="form-control" multiple  name="listeAnnonceurs[]" id="listAnnonceur" ondblclick="sendData('annonceur='+this.value+'&idCampTitle={{$idcamptitle}}&v=in','{{route ('ajax.ajouterSponsors')}}','sponsorsItem')">
    @foreach($listeDesAnnonceurs as $r)
        <option value="{{$r['id']}}">{{$r['raisonsociale']}}</option>
    @endforeach
</select>
