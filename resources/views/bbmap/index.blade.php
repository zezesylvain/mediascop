@extends("layouts.billbordmap")
@section("content")
    <div class="col-lg-12">
        <div class="panel panel-warning panel-selection">
            <div class="panel-heading">
                VOTRE SELECTION :
            </div>
            <div class="panel-body">
                <div class="col-sm-3">
                    <div id="answer"></div>
                    <div class="chart"></div>
                </div>
                <div class="col-sm-9">
                    <div class="infos" style="max-height: 175px!important;overflow: auto;">
                       {!! $selection ?? '' !!}
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="col-sm-3">
                    <b>Changer d'aperçu :</b>
                </div>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-4">
                            <ul class="nav nav-pills nav-secondary">
                                <li class="carte active">
                                    <a class="btn btn-default btn-xs" href="#carte-pills" data-toggle="tab">
                                        <i class="fa fa-search"></i> CARTE
                                    </a>
                                </li>
                                <li class="liste">
                                    <a class="btn btn-default btn-xs" href="#liste-pills" data-toggle="tab">
                                        <i class="fa fa-area-chart"></i> LISTE
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-6">
                            <div class="">
                                <button class="btn btn-warning">IMPRIMER</button>
                                <button class="btn btn-warning">SAUVEGARDER</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-warning panel-selection">
            <div class="panel-body panel-resultat">
                <div class="col-sm-12" style="margin-top: 0;padding-top: 0;">
                    <div class="tab-content">
                        <div class="tab-pane fade in active carte" id="carte-pills">
                            <div class="timeline-heading">
                                <h3 class="timeline-title">Carte</h3>
                            </div>
                            <div class="" id="carteItem" role="navigation"  xmlns="http://www.w3.org/1999/html">
                                @if(isset($listeDesMap) && !empty($listeDesMap))
                                  @includeIf('bbmap.carto.carto2')
                                @endif
                            </div> <!-- END .col .sidebar -->
                        </div>
                        <div class="tab-pane fade liste" id="liste-pills">
                            <div class="timeline-heading">
                                <h3 class="timeline-title">Liste</h3>
                            </div>
                            <div class="" id="listeItem" role="navigation">
                                @if(isset($listeDesMap) && !empty($listeDesMap))
                                    @includeIf("bbmap.panneaux.tableauDonnees")
                                @endisset
                            </div>
                        </div>
                        <div class="tab-pane fade liste" id="info-pills">
                            <div class="timeline-heading">
                                <h3 class="timeline-title">Info</h3>
                            </div>
                            <div class="" id="infoPanneauItem" role="navigation"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($listeDesMap) && !empty($listeDesMap))
        {!! $map['js'] ?? ''!!}
    @endisset
@endsection
