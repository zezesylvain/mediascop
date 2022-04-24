@extends('layouts.client')
@php( extract($dataCampagneForPM))
@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            
                <div class="timeline-heading">
                    <h3 class="timeline-title"> 
                        D&eacute;tails de la campagne
                    </h3>
                </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
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
                            {{ $date2Fr ($periode['debut']['date']) }}
                            ({{ $lesSupports[$periode['debut']['support']] }})
                            
                        </td>
                    </tr>
                    <tr>
                        <th>Derni&egrave;re diffusion</th>
                        <td>
                            {{ $date2Fr ($periode['fin']['date']) }}
                            ({{ $lesSupports[$periode['fin']['support']] }})
                            
                        </td>
                    </tr>
            </table>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            @include('clients-v2.tableaux.planMediaCampagneBilan')
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            @include('clients-v2.campagnes.docampagne')
           
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            
                <div class="timeline-heading">
                    <h3 class="timeline-title"> 
                        Plan Media de la campagne
                    </h3>
                </div>
            @include('clients-v2.tableaux.planMediaCampagne')
        </div>
    </div>
@endsection