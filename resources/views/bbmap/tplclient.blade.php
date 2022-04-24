@extends("layouts.bbmap")
@section("menu")
    @include("bbmap.menu")
@stop
@section("content")
    <div class="tab-content">
        <div class="tab-pane fade" id="recherche-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Recherche</h3>
            </div>
            <div role="navigation">
                @include("bbmap.selection.form2")
                <div class="timeline-heading">
                    <h3 class="timeline-title">Votre selection</h3>
                </div>
                <div role="navigation">
                    {!! $selection ?? '' !!}
                </div>
            </div>
        </div>
        <!--<div class="tab-pane fade" id="selecetion-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">Votre selection</h3>
            </div>
            <div role="navigation">
                { !! $selection ?? '' !! }
            </div>
        </div>-->
        <div class="tab-pane fade active in" id="carte-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title"> CARTE </h3>
            </div>
            
            <div class="" id="carteItem" role="navigation"  xmlns="http://www.w3.org/1999/html">
                @if(isset($listeDesMap) && !empty($listeDesMap))
                  @includeIf('bbmap.carto.carto2')
                @endif
            </div>
        </div>
        <div class="tab-pane fade" id="liste-pills">
            <div class="timeline-heading">
                <h3 class="timeline-title">LISTE</h3>
            </div>
            <div class="" id="listeItem" role="navigation">
                @if(isset($listeDesMap) && !empty($listeDesMap))
                    @includeIf("bbmap.panneaux.tableauDonnees")
                @endisset
            </div>
        </div>
    </div> <!-- END .tab-content -->
    @if(isset($listeDesMap) && !empty($listeDesMap))
        {!! $map['js'] ?? ''!!}
    @endisset
@stop
