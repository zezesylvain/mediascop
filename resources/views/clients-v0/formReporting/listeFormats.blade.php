<?php
$ch = $nbrDatas == count ($listeSuivant) ? "checked" : "";
?>
@if($nbrDatas > 0)
    <label class="tcocher" style="">
        <input type="checkbox" onclick="checkboxChecker('format',this.checked,'{{$nbrDatas}}');sendData('val='+this.checked+'&var=all&donnee=formats','{{route ('ajax.chercherDonnees')}}','formatitemlist')" {{$ch}}> <strong>cocher tout</strong>
    </label>
@endif
<?php $j = 0; ?>
@foreach($datas as $r)
    <?php
    $j++;
    $checked = in_array ($r['id'],$listeSuivant) ? "checked" : "";
    $media = $getChampTable ($dbTable ('DBTBL_MEDIAS','db'),$r['media']);
    ?>
    <label class="">
        <input name="format[]" id="format[{{$j}}]" value="{{$r['id']}}" type="checkbox" {{$checked}} onclick="sendData('val='+this.checked+'&donnee=formats&var='+this.value, '{{route ('ajax.chercherDonnees')}}', 'formatitemlist')" >
        {{$r['name']}}  <i style="font-size: 9pt;color: #0B792F;">[{{$media}}]</i>
    </label><br>
@endforeach
