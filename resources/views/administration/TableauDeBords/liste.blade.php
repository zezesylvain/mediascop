@extends("layouts.admin")
@section("container")
    @php($titre = $valide == 1 ? "Tableau de bord" : "Saisie et Validation")
    {!! $titreHtml($titre) !!}
    <div class="sparkline16-list responsive-mg-b-30 def-form">
        <div class="sparkline16-graph">
            @include("administration.TableauDeBords.selectmedia")
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h3>{{ucfirst ($action)}}</h3>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <hr class="trait-bleu">
                    {!! \App\Http\Controllers\Administration\CampagnesController::makeFormFilterCampagne($debutop,$ddfinop,$secteurfilteroperation,$annonceurfilteroperation,$action,$mod,$valide) !!}
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    {!! $listeDonnees !!}
                </div>
            </div>
        </div>
    </div>
@endsection
