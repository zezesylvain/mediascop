<fieldset>
    <legend><h4>Localisation</h4></legend>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <label class="section-name" for="regions">Region</label>
                    <div class="boxform form-control">
                        <label class="tcocher" style="">
                            @php
                                $j = 0;
                                $nbrRegions = count ($regions);
                                @endphp
                            <input type="checkbox" onclick="checkboxChecker('region',this.checked,'{{$nbrRegions}}')"> <strong>cocher tout</strong>
                        </label>
                        <?php $j = 0; ?>
                        @foreach($regions as $region)
                            <?php $j++; ?>
                            <label class=""  onchange="sendData('localite='+this.value,'{{route('ajax.getFilsLocalite')}}','selectItem')">
                                <input name="region[]" id="region[{{$j}}]" value="{{$region['id']}}" type="radio" onchange="sendData('region='+this.value+'&on='+this.checked,'{{route ('ajax.bbordmapLocalite')}}','villeitem')"> {{$region['name']}}
                            </label><br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- END row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <label class="section-name" for="communes">Commune</label><br>
                    <div class="boxform form-control" id="">
                        <div id="communeitem"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>

<script type="text/javascript">

</script>
