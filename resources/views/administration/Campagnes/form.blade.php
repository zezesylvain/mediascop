@extends('layouts.admin')
@section('container')
    @inject("saisie","App\Http\Controllers\Administration\SaisieController")
    @inject("campagne","App\Http\Controllers\Administration\CampagnesController")
    {!! $titreHtml("Campagnes") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! $campagne->makeFormFilterCampagne ($debutop,$ddfinop,$secteurfilteroperation,
                    $annonceurfilteroperation,$action) !!}
                </div>
                {!! $saisie->menuDeModificationDesSaisies () !!}
                <form method="post" action="{{route('saisie.storeCampTitle')}}" autocomplete="on">
                    {!! csrf_field() !!}
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! $listeDesOperations !!}
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="opitem"></div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="btnitem"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
