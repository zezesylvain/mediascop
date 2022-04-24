<fieldset class="box-com">
    <legend><h4>Communication</h4></legend>
    <div class="row">
        <div class="col-sm-4">
            <label class="section-name" for="secteur">Secteur</label>
            <div class="boxform form-control">
                <label class="tcocher" style="">
                    <input type="checkbox" onclick="checkboxChecker('secteur',this.checked,'{{$nbrsecteur}}');sendData('val='+this.checked+'&var=all&donnee=secteurs','{{route ('ajax.chercherDonnees')}}','annonceuritem')"> <strong>cocher tout</strong>
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
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-6">
                    <label class="section-name" for="annonceur">Annonceur</label><br>
                    <div class="boxform form-control">
                        <div id="annonceuritem"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="section-name" for="nature">Type de services</label><br>
                    <div class="boxform form-control" id="type_serviceitem">
                        <label class="tcocher" style="">
                            <input type="checkbox" onclick="checkboxChecker('type_service',this.checked,'{{$nbrtype_service}}')"> <strong>cocher tout</strong>
                        </label>
                        <?php $ji= 0;?>
                        @foreach($type_services as $type_service)
                            <?php $ji++; ?>
                            <label>
                                <input name="type_service[]" id="type_service[{{$ji}}]" value="{{$type_service['id']}}" type="checkbox"> {{$type_service['name']}}
                            </label><br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END row -->
</fieldset>
