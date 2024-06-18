<fieldset>
    <legend><h4>ANNONCEUR, LOCALISATION </h4></legend>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-3 col-md-3">
                    <label class="section-name" for="secteur">Secteur</label>
                    <div class="boxform form-control">
                        <!--<label class="tcocher" style="">
                            <input type="checkbox" onclick="checkboxChecker('secteur',this.checked,'{{$nbrsecteur}}');sendData('val='+this.checked+'&var=all&donnee=secteurs','{{route ('ajax.chercherDonnees')}}','annonceuritem')"> <strong>cocher tout</strong>
                        </label>-->
                        <?php $j = 0; ?>
                        @foreach($secteurs as $secteur)
                            <?php $j++; ?>
                            <label class="">
                                <input name="secteur[]" id="secteur[{{$j}}]" value="{{$secteur['id']}}" onclick="sendData('on='+this.checked+'&secteur='+ this.value, '{{route ('ajax.bbordmapComm')}}', 'annonceuritem')" type="radio"> {{$secteur['name']}}
                            </label><br>
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-3 col-md-3">
                    <label class="section-name" for="annonceur">Annonceur</label><br>
                    <div class="boxform form-control">
                        <div id="annonceuritem"></div>
                    </div>
                </div>
                <div class="col-sm-2 col-md-2">
                    <label class="section-name" for="annonceur">Format</label><br>
                    <div class="boxform form-control">
                        <label class="tcocher" style="">
                            <input type="checkbox" onclick="checkboxChecker('format',this.checked,'{{ $nbrformat }}')">
                            <strong>cocher tout</strong>
                        </label>
                        <?php $j = 0;?>
                        @foreach($formats as $format)
                            <?php $j++; ?>
                            <label class="">
                                <input name="format[]" id="format[{{$j}}]" value="{{$format['id']}}" type="checkbox"> {{$format['name']}}
                            </label><br>
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-2 col-md-2">
                    <label class="section-name" for="regions">Villes</label>
                    <div class="boxform form-control">
                        <label class="tcocher" style="">
                            @php
                                $j = 0;
                                $nbrvilles = count ($villes);
                                @endphp
                            <input type="checkbox" onclick="checkboxChecker('ville',this.checked,'{{$nbrvilles}}')"> <strong>cocher tout</strong>
                        </label>
                        <?php $j = 0; ?>
                        @foreach($villes as $ville)
                            <?php $j++; ?>
                            <label class="">
                                <input name="ville[]" id="ville[{{$j}}]" value="{{$ville['id']}}" type="checkbox" onchange="sendData('ville='+this.value+'&on='+this.checked,'{{route ('ajax.bbordmapLocalite')}}','communeitem')"> {{$ville['name']}}
                            </label><br>
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-2 col-md-2">
                    <label class="section-name" for="villes">Commune</label><br>
                    <div class="boxform form-control">
                        <div id="communeitem"></div>
                    </div>
                </div>
                <!--
                <div class="col-sm-4 col-md-3">
                    <label class="section-name" for="communes">Commune</label><br>
                    <div class="boxform form-control" id="">
                        <div id="communeitem"></div>
                    </div>
                </div>
                  -->
            </div>
        </div>
    </div>
</fieldset>
