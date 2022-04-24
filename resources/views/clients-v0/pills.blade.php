    <div class="tab-pane fade" id="list-pills">
        <div class="timeline-heading">
            <h3 class="timeline-title">Liste campagnes</h3>
        </div>
        <div class="" id="sidebar" role="navigation">
            @include("clients.resultats.listecampagne")
        </div>
    </div>
    <div class="tab-pane fade" id="graphique-pills">
        <div class="timeline-heading">
            <h3 class="timeline-title">Les graphiques</h3>
        </div>
        <div class="" id="sidebar" role="navigation">
            @include("clients.graphiquemedia")
        </div>
    </div>
    <div class="tab-pane fade" id="tableau-pills">
        <div class="timeline-heading">
            <h3 class="timeline-title">Les tableaux</h3>
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
            <h3 class="timeline-title">Rapport</h3>
        </div>
        <div class="panel panel-default">
            @include("clients.resultats.rapports")
        </div>
    </div>
