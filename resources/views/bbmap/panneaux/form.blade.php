@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Panneaux d'affichage") !!}
    <?php
    $latDefault =  isset($panneau['latitude']) ? $panneau['latitude'] : 5.356149786699246;
    $lngDefault = isset($panneau['longitude']) ? $panneau['longitude'] :-4.007166835937483;
    ?>

    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" xmlns="http://www.w3.org/1999/html">
                    <div id="mapCanvas" style="width:100%; height:600px"></div>
                </div>
    
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="all-form-element-inner">
                        <form class="form-group-inner form-panneaux" role="form" method="POST" action="{{ route('storePanneaux') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @isset($panneau)
                                <input type="hidden" name="pid" value="{{$panneau[0]['id']}}">
                            @endisset
                            <fieldset>
                                <legend>Information Panneau</legend>
                                <div class="col-sm-6 form-group{{ $errors->has('localite') ? ' has-error' : '' }}">
                                    <div class="chosen-select-single mg-b-20">
                                        <label for="ville">Ville</label>
                                        <select class="form- chosen-select" name="localite" id="ville" required onchange="sendData('localite='+this.value,'{{route('ajax.getCommune')}}','selectItem')">
                                            <option value="">Choisir une ville</option>
                                            @foreach($localites as $r)
                                                @php($selected = isset($panneau) && $parentLocalite == $r['id'] ? "selected" : "")
                                                <option {{$selected}} value="{{$r['id']}}">{{$r['name']}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('localite'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('localite') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 form-group{{ $errors->has('commune') ? ' has-error' : '' }}" id="selectItem">
                                    <div class="chosen-select-single mg-b-20">
                                        <label for="commune">Commune</label>
                                        <select class="form-control chosen-select" name="commune" id="commune" required >
                                            <option value="">Choisir une commune</option>
                                            @if(isset($panneau))
                                                @foreach($communes as $r)
                                                    @php($selected = $panneau[0]['localite'] == $r['id'] ? "selected" : "")
                                                    <option value="{{$r['id']}}" {{$selected}}>{{$r['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('commune'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('commune') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
    
                                <div class="col-sm-4 form-group{{ $errors->has('regie') ? ' has-error' : '' }}">
                                    <div class="chosen-select-single mg-b-20">
                                        <label for="regie">Régie</label>
                                        <select  class="form-control chosen-select" name="regie" id="regie"  required>
                                            <option value="">Choisir une régie</option>
                                            @foreach($regies as $r)
                                                @php($selected = isset($panneau) && $panneau[0]['regie'] == $r['id'] ? "selected" : "")
                                                <option {{$selected}} value="{{$r['id']}}">{{$r['name']}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('regie'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('regie') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
    
                                <div class="col-sm-4 form-group{{ $errors->has('format') ? ' has-error' : '' }}">
                                    <div class="chosen-select-single mg-b-20">
                                        <label for="format">Format</label>
                                        <select  class="form-control chosen-select" name="format" id="format" tabindex="-1" required >
                                            <option value="">Choisir un format</option>
                                            @foreach($formats as $r)
                                                @php($selected = isset($panneau) && $panneau[0]['format'] == $r['id'] ? "selected" : "")
                                                <option {{$selected}} value="{{$r['id']}}">{{$r['name']}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('format'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('format') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
    
                                <div class="col-sm-4 form-group{{ $errors->has('nature') ? ' has-error' : '' }}">
                                    <div class="chosen-select-single mg-b-20">
                                        <label for="nature">Nature</label>
                                        <select  class="form-control chosen-select" name="nature" id="nature" tabindex="-1"
                                                 required >
                                            <option value="">Choisir une nature</option>
                                            @foreach($natures as $r)
                                                @php($selected = isset($panneau) && $panneau[0]['nature'] == $r['id'] ? "selected" : "")
                                                <option {{$selected}} value="{{$r['id']}}">{{$r['name']}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('nature'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('nature') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4 form-group{{ $errors->has('latitude') ? ' has-error' : '' }}">
                                    <label for="latItem">Latitude</label>
                                    <input placeholder="" type="text" class="form-control" name="latitude" value="{{ $panneau[0]['latitude'] ?? $latDefault }}"  autofocus id="latItem" onchange="sendData('lat='+this.value,'{{route('ajax.changerCoord','lat')}}', 'latDivItem')" required>
                                    @if ($errors->has('latitude'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('latitude') }}</strong>
                                </span>
                                    @endif
                                </div>
                                <div class="col-sm-4 form-group{{ $errors->has('longitude') ? ' has-error' : '' }}">
                                    <label for="lngItem">Longitude</label>
                                    <input  placeholder="" type="text" class="form-control" name="longitude" value="{{ $panneau[0]['longitude'] ?? $lngDefault }}"  autofocus id="lngItem" onchange="sendData('lng='+this.value,'{{route('ajax.changerCoord','lng')}}', 'lngDivItem')" required>
                                    @if ($errors->has('longitude'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('longitude') }}</strong>
                                </span>
                                    @endif
                                </div>
    
                                <div class="col-sm-3 form-group">
                                    <div class="touchspin-inner">
                                        <label for="nbface">Nombre de face</label>
                                        <input class="touchspin1" type="text" value="{{$panneau[0]['nbface'] ?? 1}}" name="nbface" id="nbface" min="1" required>
                                    </div>
                                </div>
    
                                <div class="col-sm-8">
                                    <div class="row">
                                        @php($checkPan1 = "checked")
                                        @php($checkPan2 = "")
                                        @if(isset($panneau))
                                            <?php
                                                if ($panneau[0]['anime'] == 1):
                                                   $checkPan1 = "";
                                                   $checkPan2 = "checked";
                                                endif;
                                            ?>
                                        @endif
                                        <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6" >
                                            <label>Panneau animé</label>
                                            <div class="row">
                                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                                    <div class="inline-radio-cs">
                                                        <label class="radio-inline i-checks pull-left">
                                                            <input type="radio" value="0" id="inlineCheckbox1" name="anime" {{$checkPan1}} >Non</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                                    <div class="inline-radio-cs">
                                                        <label class="radio-inline i-checks pull-left">
                                                            <input type="radio" value="1" id="inlineCheckbox1" name="anime" {{$checkPan2}} required >Oui</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6" >
                                            <label>Eclairage</label>
                                            <div class="row">
                                                @php($checkEcl1 = "checked")
                                                @php($checkEcl2 = "")
                                                @if(isset($panneau))
                                                    <?php
                                                    if ($panneau[0]['eclairage'] == 1):
                                                        $checkEcl1 = "";
                                                        $checkEcl2 = "checked";
                                                    endif;
                                                    ?>
                                                @endif
    
                                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                                    <div class="inline-radio-cs">
                                                        <label class="radio-inline i-checks pull-left">
                                                            <input type="radio" value="0" id="inlineCheckbox1" name="eclairage" {{$checkEcl1}} >Non</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-xs-12 col-sm-6 col-md-6">
                                                    <div class="inline-radio-cs">
                                                        <label class="radio-inline i-checks pull-left">
                                                            <input type="radio" value="1" id="inlineCheckbox1" name="eclairage" {{$checkEcl2}}>Oui</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4 form-group{{ $errors->has('emplacement') ? ' has-error' : '' }}">
                                    <label for="emplacement">Emplacement</label>
                                    <textarea id="emplacement" rows="2" placeholder="" type="text" class="form-control" name="emplacement" required>{{ $panneau[0]['emplacement'] ?? "" }}</textarea>
                                    @if ($errors->has('emplacement'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('emplacement') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @if(isset($panneau))
                                    <div class="col-sm-6 form-group{{ $errors->has('code') ? ' has-error' : '' }}" id="inputCodeItem">
                                        <label for="code">Code</label>
                                        <input id="code" placeholder="" type="text" class="form-control" name="code" value="{{ $panneau[0]['code'] ?? old('code') }}"  autofocus>
                                        @if ($errors->has('code'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('code') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
                            </fieldset>
                            @if(!isset($panneau))
                                <fieldset>
                                <legend>Information Code</legend>
                                <div class="col-sm-2 form-group{{ $errors->has('zone') ? ' has-error' : '' }}">
                                    <label for="zone">Zone</label>
                                    <div class="input-mark-inner mg-b-22">
                                        <input id="zone" type="text" class="form-control" name="zone" data-mask="99" placeholder="" required>
                                        <span class="help-block">99</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 form-group{{ $errors->has('type_support') ? ' has-error' : '' }}">
                                    <label for="type_support">Type du support</label>
                                    <select id="type_support" class="form-control chosen-select" required >
                                        <option value="">Choisir le type de support</option>
                                        <option value="SP">Support Publicitaire</option>
                                        <option value="MU">Mobilier Urbain</option>
                                    </select>
                                </div>
                                <div class="col-sm-4 form-group{{ $errors->has('numero_serie') ? ' has-error' : '' }}">
                                    <label for="numero_serie">Numéro de série</label>
                                    <div class="input-mark-inner mg-b-22">
                                        <input id="numero_serie" type="text" class="form-control" name="numero_serie" data-mask="99999" placeholder="" required>
                                        <span class="help-block">99999</span>
                                    </div>
    
                                    @if ($errors->has('numero_serie'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('numero_serie') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-sm-4" style="padding-top: 1.5rem;">
                                    <a class="btn btn-danger btn-block"
                                      onclick="sendData('nature='+$('#nature').val()+
                                      '&localite='+$('#commune').val()+
                                      '&zone='+$('#zone').val()+
                                      '&type_support='+$('#type_support').val()+
                                      '&numero_serie='+$('#numero_serie').val(),
                                      '{{route('ajax.makeCodePanneau')}}','inputCodeItem')"><i class="fa fa-code" title="Cliquer pour Générer le code"></i> Générer le code</a>
                                </div>
                                <div class="col-sm-6 form-group{{ $errors->has('code') ? ' has-error' : '' }}" id="inputCodeItem">
                                    <label for="code">Code</label>
                                    <input id="code" placeholder="" type="text" class="form-control" name="code" value="{{ $panneau[0]['code'] ?? old('code') }}"  autofocus>
                                    @if ($errors->has('code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </fieldset>
                            @endif
                            {!! \App\Http\Controllers\core\FormController::champSubmit() !!}
                        </form>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <hr class="trait-bleu">
                    {!! $listeDesPanneaux !!}
                </div>
            </div>
        </div>
    </div>
@endsection
