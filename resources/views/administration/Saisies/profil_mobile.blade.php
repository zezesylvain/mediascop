<label for="mobile"> Profil </label>
<select class="form-control" name="mobile" id="mobile">
    <option value="">Choisir un profil</option>
    @foreach($dt as $rows)
        @if($rows['id'] != config('constantes.defaultvalue.mobileprofil'))
            <option  value="{{$rows['id']}}">{{$rows['name']}}</option>
        @endif
    @endforeach
</select>
