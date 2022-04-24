<fieldset class="box-com">
    <legend><h4>Communication</h4></legend>
    <div class="row">
        <div class="col-sm-3">
            <label class="section-name" for="secteur">Secteur</label>
            <div class="boxform form-control">
                <label class="tcocher" style="">
                    <input type="checkbox" onclick="checkboxChecker('secteur',this.checked,'{{count($secteurs)}}');sendData('val='+this.checked+'&var=all&donnee=secteurs','{{route ('ajax.chercherDonnees')}}','annonceuritem')"> <strong>cocher tout</strong>
                </label>
                <?php $j = 0; ?>
                @foreach($secteurs as $secteur)
                    <?php $j++; ?>
                    <label class="">
                        <input name="secteur[]" id="secteur[{{$j}}]" value="{{$secteur['id']}}" onclick="sendData('val='+this.checked+'&donnee=secteurs&var='+ this.value, '{{route ('ajax.chercherDonnees')}}', 'annonceuritem')" type="checkbox"> {{$secteur['name']}}
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
            <label class="section-name" for="campagne">Campagne</label><br>
            <div class="boxform form-control" id="">
                <div id="campagneitemlist"></div>
            </div>
        </div>
        <div class="col-sm-3">
            <label class="section-name" for="nature">Nature</label><br>
            <div class="boxform form-control" id="natureitem">
                <label class="tcocher" style="">
                    <input type="checkbox" onclick="checkboxChecker('nature',this.checked,'{{count($natures)}}')"> <strong>cocher tout</strong>
                </label>
                <?php $ji= 0;?>
                @foreach($natures as $nature)
                    <?php $ji++; ?>
                    <label>
                        <input name="nature[]" id="nature[{{$ji}}]" value="{{$nature['id']}}" type="checkbox"> {{$nature['name']}}
                    </label><br>
                @endforeach
            </div>
        </div>
    </div> <!-- END row -->
</fieldset>
