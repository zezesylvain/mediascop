<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
        <label for="support">Supports</label>
        <select name="support" id="support" data-placeholder="Choisissez un support" class="chosen-select form-control" tabindex="-1" required>
            <option value="">Choisir un support</option>
            @foreach($supports as $row)
                <option value="{{$row['id']}}">{{$row['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
        <label for="jour">Jour</label>
        <select name="support" id="support" data-placeholder="Choisissez un support" class="chosen-select form-control" tabindex="-1" required>
            <option value="">Choisir un jour</option>
            <option value="1">SEMAINE</option>
            <option value="2">WEEK END</option>
        </select>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
        <label for="heuredebut">Heure début</label>
        <div class="input-mark-inner mg-b-22">
            <input name="heuredebut" id="heuredebut" type="text" class="form-control" data-mask="99:99" placeholder="" required >
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
        <label for="heurefin">Heure fin</label>
        <div class="input-mark-inner mg-b-22">
            <input name="heurefin" id="heurefin" type="text" class="form-control" data-mask="99:99" placeholder="" required>
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
        <label for="duree">Durée</label>
        <input type="number" min="5" max="60" step="5" name="duree" id="duree" class="form-control" required>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
        <label for="tarif">Tarif</label>
        <input type="number" min="0" name="tarif" id="tarif" class="form-control" required>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
        <label for="coef">Coef</label>
        <input type="text" pattern="[+]?[0-9]*[.,]?[0-9]+" name="coef" id="coef" class="form-control" required>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group" style="padding-top: 30px;">
        <label class="check-label">
            <input type="checkbox" class="" onclick="sendData('coupure='+this.checked,'{{route ('ajax.coupureTarif')}}','coupureInputItem')">
            Coupure
        </label>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
        <div id="coupureInputItem"></div>
    </div>
</div>
