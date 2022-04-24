<div class="col-sm-4" style="padding-top: 25px!important;">
    <div class="row">
        <div class="col-sm-12">
            <label  for="Champs_{{$i}}">{{ucfirst ($champ)}}</label>
        </div>
        <div class="col-sm-4">
            <select class="form-control chosen-select" name="operateur[{{$i}}]" id="operateur{{$i}}" onchange="operateurChamps(this.value,'{{$champ.'s'}}','Champs_{{$i}}')">
                <option value="=">EGALE</option>
                <option value="IN">DANS</option>
                <option value="NOT IN">N'EST PAS DANS</option>
            </select>
        </div>
        <div class="col-sm-8">
            <select name="champsValues[{{$i}}]" id="Champs_{{$i}}" data-placeholder="Choisir {{$champ}}" class="form-control chosen-select" tabindex="-1">
                <option value=""></option>
                @foreach($datas as $r)
                    <option value="{{$r['id']}}">{{$r['name']}}</option>
                @endforeach
            </select>
            <input type="hidden" name="champs[{{$i}}]" value="{{$champ}}" id="champs_{{$champ}}">
        </div>
    </div>
</div>
