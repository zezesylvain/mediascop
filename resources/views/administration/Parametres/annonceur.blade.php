@extends('layouts.admin')
@section('container')
{!! $titreHtml("Annonceurs") !!}
<div class="sparkline16-list responsive-mg-b-30 def-form">
    <div class="sparkline16-graph">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="all-form-element-inner">
                    <form class="form-group-inner" role="form" method="POST" enctype="multipart/form-data" action="{{ route('parametre.storeAnnonceurs') }}">
                        {{ csrf_field() }}
                        @isset($annonceur)
                            <input type="hidden" name="id" value="{{$annonceur[0]['id']}}">
                        @endisset
                        <div class="col-sm-6 form-group{{ $errors->has('raisonsociale') ? ' has-error' : '' }}">
                            <label for="raisonsociale">Raisonsociale</label>
                            <input id="raisonsociale" placeholder="Raisonsociale" type="text" class="form-control" name="raisonsociale" value="{{ $annonceur[0]['raisonsociale'] ?? old('raisonsociale') }}"  autofocus>
                            @if ($errors->has('raisonsociale'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('raisonsociale') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-sm-6 form-group{{ $errors->has('secteur') ? ' has-error' : '' }}">
                            <div class="chosen-select-single mg-b-20">
                                    <label for="secteur">Secteur</label>
                                    <select id="secteur" class="chosen-select form-control" name="secteur" tabindex="-1">
                                        <option value="">Choisir un secteur</option>
                                        @foreach($secteurs as $r)
                                            @php($selected = isset($annonceur) && $annonceur[0]['secteur'] == $r['id'] ? "selected='selected'" : "")
                                            <option value="{{$r['id']}}" {{$selected}}>{{$r['name']}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('secteur'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('secteur') }}</strong>
                                    </span>
                                    @endif
                            </div>
                        </div>

                        <div class="col-sm-6 form-group{{ $errors->has('logo') ? ' has-error' : '' }}" id="logoInputItem">
                            @if($logo !== '')
                                {!! \App\Http\Controllers\Administration\ParametreController::getLogoAnnonceur ($annonceur[0]['id'],"form") !!}
                            @else
                                <label for="logo">Logo</label>
                                <div class="file-upload-inner ts-forms">
                                    <div class="input prepend-big-btn">
                                        <label class="icon-right" for="prepend-big-btn">
                                            <i class="fa fa-download"></i>
                                        </label>
                                        <div class="file-button">
                                            Browse
                                            <input type="file" name="logo" id="logo" onchange="document.getElementById('prepend-big-btn').value = this.value;" value="{{ $annonceur[0]['logo'] ?? old('logo') }}">
                                        </div>
                                        <input type="text" id="prepend-big-btn" placeholder="no file selected">
                                    </div>
                                </div>

                            @if ($errors->has('logo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('logo') }}</strong>
                                </span>
                            @endif
                        @endif
                        </div>

                        <div class="col-sm-6 form-group{{ $errors->has('couleur') ? ' has-error' : '' }} ">
                                <div class="colorpicker-inner ts-forms mg-b-23">
                                    <label class="label">Couleur</label>
                                    <input type="text" name="couleur" placeholder="#ff0000" class="jscolor" value="{{ $annonceur[0]['couleur'] ?? old('couleur') }}">
                                </div>
                                @if ($errors->has('couleur'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('couleur') }}</strong>
                                </span>
                                @endif
                        </div>

                        {!! \App\Http\Controllers\core\FormController::champSubmit() !!}
                        </form>
                </div>
            </div>
        </div>
        <div class="row">
            {!! $tableau !!}
        </div>
    </div>
</div>


@endsection
