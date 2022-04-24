<div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
    <label for="format"> Format</label>
    <select class="form-control" name="format" id="format">
        <option value="">Choisir un format</option>
        @foreach($listeDesFormats as $row)
            <option value="{{$row['id']}}">{{$row['name']}}</option>
        @endforeach
    </select>
</div>

<div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
    <label for="nature"> Nature </label>
    <select class="chosen-select form-control" name="nature" id="nature">
        <option value="">Choisir une nature</option>
        @foreach($listeDesNatures as $row)
            <option value="{{$row['id']}}">{{$row['name']}}</option>
        @endforeach
    </select>
</div>

<div class="col-xs-6 col-sm-3 col-lg-2 form-group-inner">
    <label for="cible"> Cible </label>
    <select class="chosen-select form-control" name="cible" id="cible">
        <option value="">Choisir une cible</option>
        @foreach($listeDesCibles as $row)
            <option value="{{$row['id']}}">{{$row['name']}}</option>
        @endforeach
    </select>
</div>


