<div class="col-xs-6 col-sm-6 col-md-4 form-group-inner">
    <label for="nombre"> nombre <span></span></label>
    <input  class="form-control" type="number" min="0" id="nombre" name="nombre" pattern="\d*" title="Mettez un nombre" required value="{{$pub[0]['nombre']}}"/>
</div>
<div class="col-xs-6 col-sm-6 col-md-4 form-group-inner">
    <label for="investissement"> Investissement <span></span></label>
    <input id="investissement" class="form-control" type="number" name="investissement" pattern="\d*" title="Mettez un nombre" min="0" required value="{{$pub[0]['investissement']}}"/>
</div>
