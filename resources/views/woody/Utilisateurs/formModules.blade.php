<select name="" class="form-control" onchange="javascript:location.href=this.value;" style="border: 2px solid #FF0000;border-radius: 0;font-weight: 700;">
	@foreach($modulesUser as $v)
		@php($select = $v == $moduleCourant ? "selected='selected'" : "")
		<option value="{{route('moduleHome',[$v])}}" {{$select}}>{{$getChampTable($moduleTable,$v)}}</option>
	@endforeach
</select>