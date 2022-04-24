<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <label for="support">Supports</label>
        <select name="support" id="support" data-placeholder="Choisissez un support" class="chosen-select form-control" tabindex="-1" required>
            <option value="">Choisir un support</option>
            @foreach($supports as $row)
                <option value="{{$row['id']}}">{{$row['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <label for="emplacement">Emplacement</label>
        <select name="emplacement" id="emplacement" data-placeholder="Choisissez un emplacement" class="chosen-select form-control" tabindex="-1" required>
            <option value="">Choisir un emplacement</option>
            @foreach($emplacements as $row)
                <option value="{{$row['id']}}">{{$row['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <label for="format">Formats</label>
        <select name="format" id="format" data-placeholder="Choisissez un format" class="chosen-select form-control" tabindex="-1" required>
            <option value="">Choisir un format</option>
            @foreach($formats as $row)
                <option value="{{$row['id']}}">{{$row['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <label for="tarif">Tarif</label>
        <input type="number" min="0" name="tarif" id="tarif" class="form-control" required>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <label for="coef">Coef</label>
        <input type="text" pattern="[+]?[0-9]*[.,]?[0-9]+" name="coef" id="coef" class="form-control" required>
    </div>
</div>
