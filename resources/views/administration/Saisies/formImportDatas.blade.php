@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Saisies") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <form method="post" action="{{route('saisie.storeDonneesImportees')}}" autocomplete="on" enctype="multipart/form-data">
                    {!! csrf_field () !!}
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label class="login2 pull-right pull-right-pro">Charger votre fichier ici</label>
                    </div>
                    <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                        <div class="file-upload-inner ts-forms {{ $errors->has('fichier') ? ' has-error' : '' }}">
                            <div class="input prepend-big-btn">
                                <label class="icon-right" for="prepend-big-btn">
                                    <i class="fa fa-download"></i>
                                </label>
                                <div class="file-button">
                                    Browse
                                    <input type="file" name="fichier" onchange="document.getElementById('prepend-big-btn').value = this.value;">
                                </div>
                                <input type="text" id="prepend-big-btn" placeholder="no file selected">
                            </div>
                            @if ($errors->has('fichier'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('fichier') }}</strong>
                                </span>
                            @endif
                        </div>
                        <br>
                        <button class="btn btn-sm btn-primary login-submit-cs" type="submit">Valider</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
