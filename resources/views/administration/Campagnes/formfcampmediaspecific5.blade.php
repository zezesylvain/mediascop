
<div class="col-xs-6 col-sm-3 col-lg-4 form-group-inner">
    <label for="mobilemessage"> Message </label>
    <textarea  id="mobilemessage" name="mobilemessage" class="form-control" cols="20"></textarea>
    <input id="duree" name="duree"  value="{{$defaultvalue['duree']}}"  type="hidden">
    <input id="presse_calibre" name="presse_calibre" value="{{ $defaultvalue["presse_calibre"] }}" type="hidden">
    <input id="presse_couleur" name="presse_couleur" value="{{ $defaultvalue["presse_couleur"] }}" type="hidden">
    <input  id="profil_mobile" name="profil_mobile" value="{{ $defaultvalue["profil_mobile"] }}" type="hidden">
    <input  id="internet_dimension" name="internet_dimension" value="{{ $defaultvalue["internet_dimension"] }}" type="hidden">
    <input  id="affichage_dimension" name="affichage_dimension" value="{{ $defaultvalue["affichage_dimension"] }}" type="hidden">
</div>
