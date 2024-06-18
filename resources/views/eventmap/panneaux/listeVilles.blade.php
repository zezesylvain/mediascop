<label class="tcocher" style="">
    @php
        $j = 0;
		$nbrAnn = count ($villes);
    @endphp
    <input type="checkbox" onclick="checkboxChecker('ville',this.checked,'{{$nbrAnn}}')"> <strong>cocher tout</strong>
</label>
@foreach($villes as $ville)
    <?php $j++; ?>
    <label class="">
        <input name="ville[]" id="ville[{{$j}}]" value="{{$ville['id']}}" type="checkbox"  onchange="sendData('ville='+this.value+'&on='+this.checked,'{{route ('ajax.bbordmapLocalite')}}','communeitem')"> {{$ville['name']}}
    </label><br>
@endforeach
