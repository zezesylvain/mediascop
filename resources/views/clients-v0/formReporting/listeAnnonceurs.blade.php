<div style="overflow-x: auto;max-width: 326px;">
    @if($nbrDatas > 0)
    <label class="tcocher" style="">
        <input type="checkbox" onclick="checkboxChecker('annonceur',this.checked,'{{$nbrDatas}}');sendData('val='+this.checked+'&annonceur=all','{{route ('ajax.chercherListeCampagnes')}}','')"> <strong>cocher tout</strong>
    </label>
    @endif
    <?php $j = 0; ?>
    @foreach($datas as $r)
        <?php
        $j++;
        $checked = in_array ($r['id'],$listeSuivant) ? "checked" : "";
        ?>
        <label class="">
            <input name="annonceur[]" id="annonceur[{{$j}}]" value="{{$r['id']}}"
                   onclick="sendData('val='+this.checked+'&annonceur='+ this.value, '{{route ('ajax.chercherListeCampagnes')}}', 'campagneitemlist')"
                   type="checkbox" {{$checked}}>
            {{$r['name']}}
        </label><br>
    @endforeach
</div>

