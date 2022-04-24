<div class="col-xs-6 col-sm-4 col-md-3 form-group">
<label class='checkbox-inline'>
    <input {{$checked}}  name="role[]" id="role[{{$j}}]" type="checkbox" value=" {{$id}}" onclick="sendData('profil={{$profilID}}&key=1&role={{$id}}&bool='+this.checked,'{{$route}}','divInutile')">  {{$name}}</label>
</div>