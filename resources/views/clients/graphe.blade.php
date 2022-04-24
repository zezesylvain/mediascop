@extends("layouts.client")
@section("content")
    <div class="tab-content">
        <div class="tab-pane fade active in" id="graphique-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Les graphiques</h3>
            </div>
            <div class="" id="sidebar" role="navigation">
                @include("clients.resultats.graphique")
            </div>
        </div>

        <div class="tab-pane fade " id="recherche-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Selection des parametres de recherche</h3>
            </div>
            <div class="" id="sidebar" role="navigation">
                @include("clients.form")
            </div> <!-- END .col .sidebar -->
        </div>

        <div class="tab-pane fade" id="list-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Liste campagnes</h3>
            </div>
            <div class="" id="sidebar" role="navigation">
                @include("clients.resultats.listecampagne")
            </div>
        </div>

        <div class="tab-pane fade" id="tableau-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Les tableaux</h3>
            </div>
            <div class="row">
                @include("clients.resultats.tableaux")
            </div>
        </div>
        <div class="tab-pane fade" id="speednews-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">SpeedNews</h3>
            </div>
            @include("clients.resultats.speednews")
        </div>
        <div class="tab-pane fade" id="rapport-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Rapports</h3>
            </div>
            <div class="panel panel-default">
                @include("clients.resultats.rapports")
            </div>
        </div>
    </div> <!-- END .tab-content -->

@stop

@section("menu")
    @include("clients.menuchart")
@stop

@section("selection")
    @include("clients.selectionbox")
@stop

@section("pills")
    @include("clients.pills")
@stop