@extends('layouts.admin')
@section('container')
    {!! $titreHtml("Saisies") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        @inject('saisie', '\App\Http\Controllers\Administration\SaisieController')
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                   @include("administration.Saisies.selectmedia")
                </div>
                {!! $saisie->menuDeModificationDesSaisies () !!}
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <hr class="trait-bleu">
                    {!! $saisie->makeFormFilterCampagne ($debutop,$ddfinfop,$secteurfilteroperation,$annonceurfilteroperation,$opfilteroperation,$action) !!}
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! $listeDesCampagnes !!}
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="opitem"></div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!!  $saisie->utilisateurSaisie () !!}
                </div>
            </div>
        </div>
    </div>
@endsection
