<label class="tcocher" style="">
    @php
        $j = 0;
		$nbrCamp = count ($campagnes);
    @endphp
    <input type="checkbox" onclick="checkboxChecker('campagnes',this.checked,'{{$nbrCamp}}')"> <strong>cocher tout</strong>
</label>
<?php $j = 0; ?>

@foreach($campagnes as $r)
    <?php $j++; ?>
    <label class="">
        <input name="campagnes[]" id="campagnes[{{$j}}]" value="{{$r['id']}}" type="checkbox"  title="{{$r['title']}}"> {{$r['title']}}
    </label><br>
@endforeach
