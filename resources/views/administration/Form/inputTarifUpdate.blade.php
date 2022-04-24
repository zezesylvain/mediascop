<div class="col-xs-4 col-sm-3 col-md-3 form-group-inner">
    <label for="tarif"> Tarif</label>
    <span id="tarif">
        <input name="tarif" id="tarif" type="number" size="10" pattern="\d*" class="form-control" min="0" value="{{$tarif}}">
    </span>
</div>
<div class="col-xs-2 col-sm-2 col-md-2 form-group-inner">
    <label for="coeff"> Coeff</label><span id="coef">
        <input name="coeff" id="coeff" type="number" size="10" pattern="\d*"  class="form-control" value="{{$coef}}">
    </span>
</div>
