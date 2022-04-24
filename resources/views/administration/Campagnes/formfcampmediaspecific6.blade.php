{{--
<div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
    <label for="affichage_dimension"> Dimension </label>
    <select class="form-control" id="affichage_dimension" name="affichage_dimension">
        <option value="">Choisir une dimension</option>
        @foreach($affichdimension as $row)
            <option value="{{$row->id}}">{{$row->name}}</option>
        @endforeach
    </select>
</div>
--}}
<input id="duree" name="duree"  value="{{$defaultvalue['duree']}}" type="hidden">
<input id="presse_calibre" name="presse_calibre" value="{{ $defaultvalue["presse_calibre"] }}" type="hidden">
<input id="presse_couleur" name="presse_couleur" value="{{ $defaultvalue["presse_couleur"] }}" type="hidden">
<input id="mobilemessage"  name="mobilemessage" value="<?php //echo $defaultvalue["profil_mobile"] ?>" type="hidden">
<input  id="profil_mobile" name="profil_mobile" value="{{ $defaultvalue["profil_mobile"] }}" type="hidden">
<input  id="internet_dimension" name="internet_dimension" value="{{ $defaultvalue["internet_dimension"] }}" type="hidden">
