<fieldset>
    <legend><h4>Communication</h4></legend>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <label class="section-name" for="secteur">Secteur</label>
                    <div class="boxform form-control">
                        <label class="tcocher" style="">
                            <input type="checkbox" onclick="checkboxChecker('secteur',this.checked,'{{$nbrsecteur}}');sendData('val='+this.checked+'&var=all&donnee=secteurs','{{route ('ajax.chercherDonnees')}}','annonceuritem')"> <strong>cocher tout</strong>
                        </label>
                        <?php $j = 0; ?>
                        @foreach($secteurs as $secteur)
                            <?php $j++; ?>
                            <label class="">
                                <input name="secteur" id="secteur[{{$j}}]" value="{{$secteur['id']}}" onclick="sendData('on='+this.checked+'&secteur='+ this.value, '{{route ('ajax.bbordmapComm')}}', 'annonceuritem')" type="radio"> {{$secteur['name']}}
                            </label><br>
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="section-name" for="annonceur">Annonceur</label><br>
                    <div class="boxform form-control">
                        <div id="annonceuritem"></div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END row -->
<!--    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <label class="section-name" for="campagne">Campagne</label><br>
                    <div class="boxform form-control" id="">
                        <div id="campagneitemlist"></div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <label class="section-name" for="nature">Durée</label><br>
                    <div class="boxform form-control" id=""></div>
                </div>
            </div>
        </div>
    </div>-->
</fieldset>
