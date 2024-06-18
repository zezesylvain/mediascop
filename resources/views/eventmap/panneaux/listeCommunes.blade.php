<label class="tcocher" style="">
    @php
        $j = 0;
		$nbrAnn = count ($communes);
    @endphp
    <input type="checkbox" onclick="checkboxChecker('commune',this.checked,'{{$nbrAnn}}')"> <strong>cocher tout</strong>
</label>

@foreach($communes as $commune)
    <?php $j++; ?>
    <label class="">
        <input name="commune[]" id="commune[{{$j}}]" value="{{$commune['id']}}" type="checkbox"> {{$commune['name']}}
    </label><br>
@endforeach
