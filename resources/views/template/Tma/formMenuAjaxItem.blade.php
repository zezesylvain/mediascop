<div class="row">
    <input type="hidden" value="9" name="icone">
    <div class="form-group col-sm-4">
        <label for="nameMenu">Libellé menu</label>
        <input type="text" name="name"  class="form-control" value="{{$data->name ?? ""}}"  required id="nameMenu">
    </div>
    <div class="form-group col-sm-4">
        <label for="level">LEVEL</label>
        <input type="number" name="level_menu" min="0" max="999999" step="10"  class="form-control"  required id="level">
    </div>
    <div class="form-group col-sm-4">
        <label for="target">Target</label>
        <select name="menu_target" id="target" class="form-control">
            @foreach($targetMenu as $r)
               <option value="{{$r['id']}}">{{$r['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-12 btn-group">
        <a href="#" class="btn btn-block btn-primary" onclick="sendData('groupemenu='+$('#groupemenu').val()+'&role={{$roleID}}&level='+$('#level').val()+'&menu='+$('#nameMenu').val()+'&rang={{$rangSuiv}}','{{route('ajax.storeMenuItem')}}','estRoleItem')">Valider</a>
    </div>
</div>

