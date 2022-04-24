<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div class="chosen-select-single mg-b-20">
          <label for="{{$champ}}"> {{$libelle}}</label>
          <textarea name="{{$champ}}" class="form-control {{$editeur ?? ''}}" id="{{$champ}}">{!! $valeur ?? '' !!}</textarea>
    </div>
</div>
