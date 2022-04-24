    <?php
        $ch = $nbrDatas == count ($listeSuivant) ? "checked" : "";
    ?>
    @if($nbrDatas > 0)
        <label class="tcocher" style="">
            <input type="checkbox" onclick="checkboxChecker('support',this.checked,'{{$nbrDatas}}');sendData('val='+this.checked+'&var=all&donnee=supports','{{route ('ajax.chercherDonnees')}}','supportitemlist')" {{$ch}}> <strong>cocher tout</strong>
        </label>
    @endif
    <?php $j = 0;?>
    @foreach($datas as $r)
        <?php
        $j++;
        $checked = in_array ($r['id'],$listeSuivant) ? "checked" : "";
        ?>
        <label class="">
            <input name="support[]" id="support[{{$j}}]" value="{{$r['id']}}"
                   type="checkbox" onclick="sendData('val='+this.checked+'&donnee=supports&var='+this.value, '{{route ('ajax.chercherDonnees')}}', 'supportitemlist')"  {{$checked}} > {{$r['name']}}
        </label><br>
    @endforeach
