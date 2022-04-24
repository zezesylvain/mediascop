<div class="col-sm-10 form-group">
    <input type="text" class="form-control" id="password"  style="font-size:1.1em" value="{{$newMDP}}" disabled>
    <input type="hidden" name="password" value="{{$newMDP}}">
</div>
<div class="col-sm-2 form-group">
    <a href="#" style="color: #FF0000;"  onclick="sendData('v=no','{{route('ajax.reinitPassword')}}', 'formPasswordItem')">Annuler</a>
</div>
