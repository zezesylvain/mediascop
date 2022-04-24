@php
extract($lesDonnees) ;
//dd($lesDonnees);
@endphp
@extends("layouts.client")
@section("menu")
    @include("clients-v2.menu")
@stop
@section("content")
    <div class="tab-content">
        <div class="tab-pane fade" id="recherche-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Selection des parametres de recherche</h3>
            </div>
            <div role="navigation">
                @include("clients-v2.form")
            </div> <!-- END .col .sidebar -->
        </div>
        <div class="tab-pane fade active in" id="graphique-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Parts de Voix et d'Investissement (Graphiques)</h3>
            </div>
            <div role="navigation">
                @include("clients-v2.resultats.graphique")
            </div>
            
            
        </div>
        <div class="tab-pane fade" id="list-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Plan M&eacute;dia (Campagnes)</h3>
            </div>
            <div role="navigation">
                @include("clients-v2.resultats.listecampagne")
            </div>
        </div>
        <div class="tab-pane fade" id="tableau-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Parts de voix et d'Investissement (Tableaux)</h3>
            </div>
            <div class="row">
                @include("clients-v2.resultats.tableaux")
            </div>
        </div>
        <div class="tab-pane fade" id="planmedia-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Plan M&eacute;dia (Annonceurs)</h3>
            </div>
            <div class="row">
                @include("clients-v2.resultats.planmedia")
            </div>
        </div>
        <div class="tab-pane fade" id="speednews-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title"> Alerte M&eacute;dia (SpeedNews)</h3>
            </div>
            @include("clients-v2.resultats.speednews", compact('lesSpeednews'))
        </div>
        <div class="tab-pane fade" id="rapport-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Rapports (Archives)</h3>
            </div>
            <div class="panel panel-default">
                @include("clients-v2.resultats.rapports")
            </div>
        </div>
    </div> <!-- END .tab-content -->

@stop

@section("selection")
    @include("clients-v2.selectionbox")
@stop
