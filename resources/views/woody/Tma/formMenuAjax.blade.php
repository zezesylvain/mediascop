<div class="row">
    <input type="hidden" value="{{$rangSuiv}}" name="rang">
    <input type="hidden" value="9" name="icone">
    <div class="form-group col-sm-4">
        <label for="role">Roles</label>
        <select  name="role"  class="form-control"  required id="role">
            <option>-------</option>
            @foreach($roles as $v)
                <option value="{{$v['id']}}">{{$v['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-4">
        <label for="name">Libellé</label>
        @php($libelle = "")
        @isset($data)
            @php($libelle = $data->name)
        @endisset
        <input type="text" name="name"  class="form-control" value="{{$libelle}}"  required id="name">
    </div>
    <div class="form-group col-sm-4">
        <label for="level">LEVEL</label>
        <input type="number" name="level_menu" min="0" max="999999" step="10"  class="form-control"  required id="level">
    </div>

    {!! \App\Http\Controllers\core\FormController::champSubmit() !!}
</div>

