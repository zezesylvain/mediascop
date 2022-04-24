<div class="col-sm-12">
    <label class="section-name" for="support">Support</label><br>
    <div class="boxform form-control" id="">
        <div id="supportitemlist">
            @if($nbrSupports > 0)
                <label class="tcocher" style="">
                    <input type="checkbox" onclick="checkboxChecker('support',this.checked,'{{$nbrSupports}}');sendData('val='+this.checked+'&var=all&donnee=supports','{{route ('ajax.chercherDonnees')}}','supportitemlist')"> <strong>cocher tout</strong>
                </label>
                <?php $j = 0;?>
                @foreach($supports as $r)
                    <?php
                    $j++;
                    $checked = in_array ($r['id'],$supportsSession) ? "checked" : "";
                    ?>
                    <label class="">
                        <input name="support[]" id="support[{{$j}}]" value="{{$r['id']}}"
                               type="checkbox" onclick="sendData('val='+this.checked+'&donnee=supports&var='+this.value, '{{route ('ajax.chercherDonnees')}}', 'supportitemlist')"  {{$checked}} > {{$r['name']}}
                    </label><br>
                @endforeach
            @endif
        </div>
    </div>
</div>