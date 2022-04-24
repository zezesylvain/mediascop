<fieldset class="box-com">
    <legend><h4>Communication</h4></legend>
    <div class="row">
        <div class="col-sm-3">
            <label class="section-name" for="secteur">Secteur</label>
            <div class="boxform form-control">
                <label class="tcocher" style="">
                    <input type="checkbox" onclick="checkboxChecker('secteur',this.checked,'{{count($secteurs)}}');
                        sendData('val='+this.checked+'&var=all&donnee=secteurs',
                        '{{route ('ajax.chercherAnnonceursBySecteur')}}','annonceuritem')"> 
                        <strong>cocher tout</strong>
                </label>
                <?php $j = 0; ?>
                @foreach($secteurs as $sect)
                    <?php $j++; ?>
                    <label class="">
                        <input name="secteur[]" id="secteur[{{$j}}]" value="{{$sect['id']}}" 
                        onclick="sendData('val='+this.checked+'&donnee=secteurs&var='+ this.value, 
                        '{{route ('ajax.chercherAnnonceursBySecteur')}}', 'annonceuritem')" type="checkbox"> 
                        {{ $sect['name'] }}
                    </label><br>
                @endforeach
            </div>
        </div>
        <div class="col-sm-3">
            <label class="section-name" for="annonceur">Annonceur</label><br>
            <div class="boxform form-control">
                <div id="annonceuritem"></div>
            </div>
        </div>
        <div class="col-sm-3">
            <label class="section-name" for="media">Médias</label><br>
            <div class="boxform form-control">
                <label class="tcocher" style="">
                    <input type="checkbox" onclick="checkboxChecker('media',this.checked,'{{ count($medias) }}');
                            sendData('val='+this.checked+'&var=all&donnee=supports','{{route ('ajax.chercherDonneesMediaSupport')}}','supportitemlist')"> 
                            <strong>cocher tout</strong>
                </label>
            <?php $i= 0;?>
                @foreach($medias as $medi)
                        <?php $i++;?>
                        <label>
                        <input name="media[]" id="media[{{$i}}]" type="checkbox" value="{{$medi['id']}}"
                                onclick="sendData('val='+this.checked+'&donnee=medias&var='+ this.value, '{{route ('ajax.chercherDonneesMediaSupport')}}', 'supportitemlist')">
                            {{$medi['name']}}
                    </label><br />
                @endforeach
            </div>
        </div>
        <div class="col-sm-3">
            <div class="row" id="supportitemlist">
                <div class="col-sm-12">
                    <label class="section-name" for="support">Support</label><br>
                    <div class="boxform form-control" id="">
                        <div id=""></div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END row -->
</fieldset>
