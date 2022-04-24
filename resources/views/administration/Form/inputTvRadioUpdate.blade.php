{!! $support !!}
<div class="col-xs-4 col-sm-3 col-md-3 form-group-inner">
    <label for="heure">Heure</label>
    <input id="heure" name="heure" type="text" min="0" step="5" placeholder="hh:mm" size="5" pattern="[0-9]{1,2}(:{1}[0-9]{1,2}){1,2}" class="form-control" onchange="sendData('var=heure&val='+this.value+'&action=cid', '{{$r}}', 'cid'); sendData('heure='+this.value+'&action=getTarif', '{{$r1}}', 'tarif'); sendData('heure='+this.value+'&action=getCoef', '{{$r2}}', 'coef');" required value="{{$heure}}">
</div>
{!! $TarifCoefBox !!}
