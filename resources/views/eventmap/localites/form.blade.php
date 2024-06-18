@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Localités") !!}
    <?php
    $latDefault =  isset($localite['latitude']) ? $localite['latitude'] : 5.356149786699246;
    $lngDefault = isset($localite['longitude']) ? $localite['longitude'] :-4.007166835937483;
    ?>

    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" xmlns="http://www.w3.org/1999/html">
                    <div id="mapCanvas" style="width:100%; height:500px"></div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="all-form-element-inner">
                        <form class="form-group-inner" role="form" method="POST" action="{{ route('storeLieuLocalites') }}">
                            {{ csrf_field() }}
                            @isset($lalocalite)
                                <input type="hidden" name="id" value="{{$localite[0]['id']}}">
                            @endisset
                            <div class="col-sm-6 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Nom Lieu</label>
                                <input id="name" placeholder="" type="text" class="form-control" name="name" value="{{ $lalocalite[0]['name'] ?? old('name') }}"  autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-sm-6 form-group{{ $errors->has('parent') ? ' has-error' : '' }}">
                                <div class="chosen-select-single mg-b-20">
                                    <label for="selectItem">Filtre sur Localité</label>
                                    <select  class="form-control" name="localite" id="selectItem" tabindex="-1" onchange="sendData('localite='+this.value,'{{route('ajax.getFilsLocalite')}}','selectItem');getLatLngLocalite(this.value)">
                                        <option value="0">Choisir une localite</option>
                                        @foreach($localites as $r)
                                            @php($selected = isset($localite) && $localite['parent'] == $r['id'] ? "selected" : "")
                                            <option {{$selected}} value="{{$r['id']}}">{{$r['name']}}</option>
                                        @endforeach
                                        @if(isset($localite) && $localite['parent'] != 0)
                                            <option value="{{$grandPere}}">---Rémonter---</option>
                                        @endif
                                    </select>

                                    @if ($errors->has('parent'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('parent') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 form-group{{ $errors->has('latitude') ? ' has-error' : '' }}">
                                <label for="latItem">Latitude</label>
                                <input placeholder="" type="text" class="form-control" name="latitude" value="{{ $localite[0]['latitude'] ?? $latDefault }}"  autofocus id="latItem" onchange="sendData('lat='+this.value,'{{route('ajax.changerCoord','lat')}}', 'latDivItem')">
                                @if ($errors->has('latitude'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('latitude') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-sm-6 form-group{{ $errors->has('longitude') ? ' has-error' : '' }}">
                                <label for="lngItem">Longitude</label>
                                <input  placeholder="" type="text" class="form-control" name="longitude" value="{{ $localite[0]['longitude'] ?? $lngDefault }}"  autofocus id="lngItem" onchange="sendData('lng='+this.value,'{{route('ajax.changerCoord','lng')}}', 'lngDivItem')">
                                @if ($errors->has('longitude'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('longitude') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-sm-12 form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="description">description</label>
                                <textarea id="description" rows="10" placeholder="" type="text" class="form-control" name="description" >{{ $localite[0]['description'] ?? "" }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                                @endif
                            </div>

                            {!! \App\Http\Controllers\core\FormController::champSubmit() !!}
                        </form>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <hr class="trait-bleu">
                    {!! $tableau !!}
                </div>
            </div>
        </div>
    </div>

@endsection
