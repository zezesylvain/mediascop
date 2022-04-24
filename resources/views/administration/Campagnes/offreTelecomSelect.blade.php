<div class="row" id="campagnefilter"> </div>
<hr class="trait-bleu">
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-sm-11 form-group">
                <label for="title"> Titre (250 caractères maximum)</label>
                <input class="form-control" type="text" id="title" onchange="sendData('name=' + this.value+'&v=1', '{{route('ajax.campagne')}}','verifcampagne');sendData('name=' + this.value+'&v=2', '{{route('ajax.campagne')}}', 'btnitem')" name="title" value="" required maxlength="250"/>
            </div>
            <div class="col-sm-1" style="padding-top: 10px;"><span id="verifcampagne"></span></div>
        </div>
    </div>
<!--
    @if(count ($offretelecom))
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="chosen-select-single mg-b-20 form-group">
                <label for="offretelecom"> Offre t&eacute;l&eacute;com</label>
                <select name="offretelecom" id="offretelecom" data-placeholder="Choisir un secteur" class="chosen-select form-control" tabindex="1" required>
                    <option value="">Choisir un secteur</option>
                    @foreach($offretelecom as $rows)
                        <?php $selected = $rows['id'] == 1 ? "selected='selected'" : ""; ?>
                        <option value="{{$rows['id']}}" {{$selected}}>{{$rows['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <input type="hidden" name="offretelecom" value="1">
    @endif
-->
</div>
