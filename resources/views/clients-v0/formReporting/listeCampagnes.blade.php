@if($nbrDatas > 0)
    <label class="tcocher" style="">
        <input type="checkbox" onclick="checkboxChecker('campagne',this.checked,'{{$nbrDatas}}')"> <strong>cocher tout</strong>
    </label>
@endif
<?php $j = 0; ?>
@foreach($listedescampagnes as $r)
    <?php $j++; ?>
    <label class="">
        <input name="campagne[]" id="campagne[{{$j}}]" value="{{$r['id']}}" type="checkbox">
        {{$r['title']}}
    </label><br>
@endforeach
