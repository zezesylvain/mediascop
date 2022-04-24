
<div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
{{--
    <label for="internet_dimension"> Dimension </label>
    <select class="form-control" id="internet_dimension" name="internet_dimension">
        <option value="">Choisir une dimension</option>
        @foreach($interdimension as $row)
            <option value="{{$row->id}}">{{$row->name}}</option>
        @endforeach
    </select>
--}}

    <input id="duree" name="duree"  value="{{ $defaultvalue['duree'] }}"  hidden="true">
    <input id="presse_calibre" name="presse_calibre" value="{{$defaultvalue["presse_calibre"] }}" hidden="true">
    <input id="presse_couleur" name="presse_couleur" value="{{$defaultvalue["presse_couleur"] }}" hidden="true">
    <input id="mobilemessage"  name="mobilemessage" value="<?php //echo $defaultvalue["profil_mobile"] ?>" hidden="true">
    <input  id="profil_mobile" name="profil_mobile" value="{{$defaultvalue["profil_mobile"] }}" hidden="true">
    <input  id="affichage_dimension" name="affichage_dimension" value="{{$defaultvalue["affichage_dimension"] }}" hidden="true">
</div>
