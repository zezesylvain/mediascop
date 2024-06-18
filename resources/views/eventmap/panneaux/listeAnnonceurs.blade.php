<label class="tcocher" style="">
    @php
        $j = 0;
		$nbrAnn = count ($annonceurs);
    @endphp
    <input type="checkbox" onclick="checkboxChecker('annonceurs',this.checked,'{{$nbrAnn}}')"> <strong>cocher tout</strong>
</label>
<?php $j = 0; ?>

@foreach($annonceurs as $r)
    <?php $j++; ?>
    <label class="">
        <input name="annonceurs[]" id="annonceurs[{{$j}}]" value="{{$r['id']}}" type="checkbox" onclick="sendData('on='+this.checked+'&annonceur='+ this.value, '{{route ('ajax.bbordmapComm')}}', 'campagneitemlist')"> {{$r['name']}}
    </label><br>
@endforeach
