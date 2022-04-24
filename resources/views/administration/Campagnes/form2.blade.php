@extends('layouts.admin')
@section('container')
    @inject("cpg","App\Http\Controllers\Administration\CampagnesController")
    {!! $titreHtml("Campagnes") !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! $cpg->makeTableTitle ($idcamptitle) !!}
                    <hr class="trait-bleu">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! $cpg->createChampsInputCampagne ($idcamptitle) !!}
                    <hr class="trait-bleu">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline10-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @include("administration.Campagnes.boxAnnonceurSponsors")
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="trait-bleu">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! \App\Http\Controllers\Administration\CampagnesController::makeListeCampagnes ($idcamptitle) !!}
                    <hr class="trait-bleu">
                </div>
                @if(!empty($docampagnes))
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {!! \App\Http\Controllers\Administration\AdminController::getVisuelCampagne ($docampagnes,$idcamptitle) !!}
                        <hr class="trait-bleu">
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>Gestion des visuèls  <a href="" onclick=""><i class="fa fa-refresh"></i> </a> </h3>
                </div>
                {!! \App\Http\Controllers\Administration\CampagnesController::getDocCampagnes ($idcamptitle) !!}
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="dropzone-pro">
                        <div id="dropzone">
                            <form action="{{route ('saisie.uploadFiles')}}" class="dropzone dropzone-custom needsclick" id="demo-upload">
                                {!! csrf_field () !!}
                                <input type="hidden" name="uploadDir" value="{{$uploadDir}}">
                                <div class="dz-message needsclick download-custom">
                                    <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    <h2>Déposer les fichiers ici ou cliquer pour charger.</h2>
                                    <p><span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
