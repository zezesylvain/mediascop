<fieldset>
    <legend><h4>Médias &amp; supports</h4></legend>
    <div class="row">
        <div class="col-sm-4">
            <label class="section-name" for="media">Médias</label><br>
            <div class="boxform form-control">
                <label class="tcocher" style="">
                    <input type="checkbox" onclick="checkboxChecker('media',this.checked,'{{$nbrmedia}}');sendData('val='+this.checked+'&var=all&donnee=supports','{{route ('ajax.chercherDonneesMediaSupport')}}','supportformatitemlist')"> <strong>cocher tout</strong>
                </label>
            <?php $i= 0;?>
                @foreach($medias as $media)
                        <?php $i++;?>
                        <label>
                        <input name="media[]" id="media[{{$i}}]" type="checkbox" value="{{$media['id']}}"                                    onclick="sendData('val='+this.checked+'&donnee=medias&var='+ this.value, '{{route ('ajax.chercherDonneesMediaSupport')}}', 'supportformatitemlist')">
                            {{$media['name']}}
                    </label><br />
                @endforeach
            </div>
        </div>
        <div class="col-sm-8">
            <div class="row" id="supportformatitemlist">
                <div class="col-sm-6">
                    <label class="section-name" for="support">Support</label><br>
                    <div class="boxform form-control" id="">
                        <div id=""></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="section-name" for="format">Format</label><br>
                    <div class="boxform form-control" id="">
                        <div id=""></div>
                    </div>
                </div>
            </div>
        </div>
     </div> <!-- END row -->
</fieldset>
