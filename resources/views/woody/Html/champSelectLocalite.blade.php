<select  name="{{$chpname}}" id="selectItem" class="form-control chosen-select " tabindex="-1" onchange="sendData('localite='+this.value,'{{route('ajax.getFilsLocalite')}}','selectItem')" required>
    @if($grandPere == -1)
        <option value = "0">----------</option>
    @endif
    @foreach($localites as $t)
        @php($selected = $t['id'] == $pere ? "selected='selected'" : "")
        <option value="{{$t['id']}}" {{$selected}}>{{$t['name']}}</option>
    @endforeach
    @if($grandPere != -1)
        <option value="{{$grandPere}}">---Rémonter---</option>
    @endif
</select>
