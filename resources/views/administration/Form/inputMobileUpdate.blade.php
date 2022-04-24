<div class="col-xs-4 col-sm-4 col-md-4 form-group-inner">
    <label for="heure"> Heure <span>(hh:mm:ss)</span></label>
    <input size="20" class="form-control" type="text" id="heure" name="heure" value="{{$heure}}" required pattern="[0-9]{1,2}(:{1}[0-9]{1,2}){1,2}" title="l\'heure sous la forme: 12:34:23 ou 13:48" />
</div>
{!! $support !!}
{!! $inputPubs !!}
<div class="col-xs-4 col-sm-4 col-md-4 form-group-inner" id="mobileprofilitem"></div>
