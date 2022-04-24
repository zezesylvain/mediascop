<label class="tcocher" style="">
    @php
        $j = 0;
		$nbrPann = count ($panneaux);
    @endphp
    <input type="checkbox" onclick="checkboxChecker('panneaux',this.checked,'{{$nbrPann}}')"> <strong>cocher tout</strong>
</label>
<?php $j = 0; ?>

@foreach($panneaux as $r)
    <?php $j++; ?>
    <label class="">
        <input name="panneaux[]" id="panneaux[{{$j}}]" value="{{$r['id']}}" type="checkbox"> {{$r['code']}}
    </label><br>
@endforeach
