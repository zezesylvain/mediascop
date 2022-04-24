@extends("layouts.admin")
@inject('admin', "App\Http\Controllers\Client\AdminController")
@section("container")
    {!! $titreHtml("Tableau recapitulatif des speednews") !!}
    @include("administration.reportings.filtreSecteur")
    {!! $admin->listeDernieresCampagnes() !!}
@endsection
