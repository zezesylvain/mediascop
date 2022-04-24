<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div class="chosen-select-single mg-b-20 form-group">
        <label  for="{{$champ}}">{{$libelle}}</label>
        <select name="{{$champ}}" id="{{$champ}}" data-placeholder="" class="chosen-select form-control" tabindex="-1">
            {!! $option !!}
        </select>
    </div>
</div>
