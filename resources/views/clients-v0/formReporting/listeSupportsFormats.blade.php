<div class="col-sm-6">
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
<div class="col-sm-6">
    <label class="section-name" for="format">Format</label><br>
    <div class="boxform form-control" id="">
        <div id="formatitemlist">
            @if($nbrFormats > 0)
                <label class="tcocher" style="">
                    <input type="checkbox" onclick="checkboxChecker('format',this.checked,'{{$nbrFormats}}');sendData('val='+this.checked+'&var=all&donnee=formats','{{route ('ajax.chercherDonnees')}}','formatitemlist')"> <strong>cocher tout</strong>
                </label>
                <?php $j = 0; ?>
                @foreach($formats as $r)
                    <?php
                    $j++;
                    $checked = in_array ($r['id'],$formatsSession) ? "checked" : "";
                    $media = $getChampTable ($dbTable ('DBTBL_MEDIAS','db'),$r['media']);
                    ?>
                    <label class="">
                        <input name="format[]" id="format[{{$j}}]" value="{{$r['id']}}" type="checkbox" {{$checked}} onclick="sendData('val='+this.checked+'&donnee=formats&var='+this.value, '{{route ('ajax.chercherDonnees')}}', 'formatitemlist')" >
                        {{$r['name']}} <i style="font-size: 9pt;color: #0B792F;">[{{$media}}]</i>
                    </label><br>
                @endforeach
            @endif
        </div>
    </div>
</div>
