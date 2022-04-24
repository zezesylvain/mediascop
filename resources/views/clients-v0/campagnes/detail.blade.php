@extends('layouts.client')
@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <table class="table table-responsive table-bordered table-hover">
                <tr>
                    <th style="width: 25%;">Titre</th>
                    <td style="width: 75%;">{!! $res['Titre'] !!}</td>
                </tr>
                <tr>
                    <th>Opération</th>
                    <td>{!! $res['Operation'] !!}</td>
                </tr>
                <tr>
                    <th>Secteur d'activit&eacute;</th>
                    <td>{!! $res['Secteur'] !!}</td>
                </tr>
                <tr>
                    <th>Annonceur</th>
                    <td>{!! $res['Annonceur'] !!}</td>
                </tr>
                    <tr>
                        <th>Premi&egrave;re diffusion</th>
                        <td>
                            {{$date2Fr ($res['Premiere Diffusion']['date'])}}
                            ({{$res['Premiere Diffusion']['media']}}
                            sur @if($res['Premiere Diffusion']['media'] != "AFFICHAGE")
                                    {{$res['Premiere Diffusion']['support']}}
                                @endif)
                        </td>
                    </tr>
                    <tr>
                        <th>Derni&egrave;re diffusion</th>
                        <td>
                            {{$date2Fr ($res['Derniere Diffusion']['date'])}}
                            ({{$res['Derniere Diffusion']['media']}}
                            sur @if($res['Derniere Diffusion']['media'] != "AFFICHAGE")
                                    {{$res['Derniere Diffusion']['support']}}
                                @endif)
                        </td>
                    </tr>
            </table>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            {!! $docCamp !!}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            @include('clients.tableaux.planMediaCampagne')
        </div>
    </div>
@endsection