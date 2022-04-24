
{{--<div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
    <label for="presse_calibre"> Calibre </label>
    <select class="form-control" id="presse_calibre" name="presse_calibre">
        <option value="">Choisir un calibre</option>
        @foreach($pressecalibre as $row)
            <option value="{{$row->id}}">{{$row->name}}</option>
        @endforeach
    </select>
</div>
<div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
    <label for="presse_couleur"> Couleur </label>
    <select class="form-control" id="presse_couleur" name="presse_couleur">
        <option value="">Choisir une couleur</option>
        @foreach($pressecouleur as $row)
            <option value="{{$row->id}}">{{$row->name}}</option>
        @endforeach
    </select>
</div>--}}
<input id="duree" name="duree" value="{{ $defaultvalue["duree"] }}" type="hidden">
<input id="mobilemessage" name="mobilemessage" value="" type="hidden">
<input id="internet_dimension" name="internet_dimension" value="{{$defaultvalue["internet_dimension"] }}" type="hidden">
<input id="affichage_dimension" name="affichage_dimension" value="{{$defaultvalue["affichage_dimension"] }}" type="hidden">
<input id="profil_mobile" name="profil_mobile" value="{{$defaultvalue["profil_mobile"] }}" type="hidden">
