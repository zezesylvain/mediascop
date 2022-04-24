<fieldset>
    <legend><h4>Support</h4></legend>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
<!--
                <div class="col-sm-6">
                    <label class="dimension-name" for="dimension">Dimension</label><br>
                    <div class="boxform form-control">
                        <label class="tcocher" style="">
                            @php
                                $j = 0;
                                $nbrDim = count ($dimensions);
                            @endphp
                            <input type="checkbox" onclick="checkboxChecker('dimension',this.checked,'{{$nbrDim}}')"> <strong>cocher tout</strong>
                        </label>
                        <?php $j = 0; ?>
                        @foreach($dimensions as $r)
                            <?php $j++; ?>
                            <label class="">
                                <input name="dimension[]" id="dimension[{{$j}}]" value="{{$r['id']}}" type="checkbox"> {{$r['name']}}
                            </label><br>
                        @endforeach
                    </div>
                </div>
-->
            </div>  
        </div>
    </div> <!-- END row -->
    <div class="row">
        <div class="col-sm-6">
            <label class="regie-name" for="regie">Régie</label><br>
            <div class="boxform form-control" id="">
                <label class="tcocher" style="">
                    @php
                        $j = 0;
						$nbrRegie = count ($regies);
                    @endphp
                    <input type="checkbox" onclick="checkboxChecker('regies',this.checked,'{{$nbrRegie}}')"> <strong>cocher tout</strong>
                </label>
                <?php $j = 0; ?>
                @foreach($regies as $r)
                    <?php $j++; ?>
                    <label class="">
                        <input name="regies[]" id="regies[{{$j}}]" value="{{$r['id']}}" type="checkbox" onchange="sendData('regie='+this.value+'&on='+this.checked,'{{route('ajax.bbordmapSupport')}}','panneauxitem')"> {{$r['name']}}
                    </label><br>
                @endforeach
            </div>
        </div>
        <div class="col-sm-6">
            <label class="denomination-name" for="denomination">Panneaux</label><br>
            <div class="boxform form-control" id="">
                <div id="panneauxitem"></div>
            </div>
        </div>
    </div>
</fieldset>
