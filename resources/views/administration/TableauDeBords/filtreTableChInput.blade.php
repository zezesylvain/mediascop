<div class="col-sm-4" style="padding-top: 25px!important;">
    <div class="row">
        <div class="col-sm-12">
            <label  for="Champs_{{$i}}">{{ucfirst ($champ)}}</label>
        </div>
        <div class="col-sm-4">
            <select class="form-control chosen-select" name="operateur[{{$i}}]" id="operateur{{$i}}">
                <option value="LIKE">EGALE</option>
                <option value="LIKE %...%">CONTENANT</option>
                <option value="NOT LIKE">N'EST PAS EGALE</option>
                <option value="NOT LIKE %...%">NE CONTENANT PAS</option>
            </select>
        </div>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="champsValues[{{$i}}]" id="{{$champ}}">
            <input type="hidden" name="champs[{{$i}}]" value="{{$champ}}" id="champs_{{$champ}}">
        </div>
    </div>
</div>
