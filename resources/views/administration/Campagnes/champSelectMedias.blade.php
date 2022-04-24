@if(!isset($lavaleur))
    <select class="form-control" name="{{$col}}" onchange="sendData('id={{$id}}&col={{$col}}&table={{$table}}&datable={{$datable}}&colname={{$colname}}&pid={{$pid}}&cond={{$cond}}&value='+this.value,'{{$route}}','{{$pid}}')">
        @foreach ($re as $row)
            @php($selected = $row['id'] == $value ? 'selected="selected"' : '' )
            <option value="{{$row['id']}}" {{$selected}}>{{$row[$colname]}}</option>
        @endforeach
    </select>
@else
    <a href="#" ondblclick="sendData('id={{$id}}&value={{$value}}&col={{$col}}&table={{$table}}&colname={{$colname}}&datable={{$datable}}&pid={{$pid}}&cond={{$cond}}','{{$route}}', '{{$pid.$id}}')">
        {{$lavaleur}}
    </a>
@endif
