{{--@php(dump ($indicateurs->mesIndicateurs()))--}}
@if(count ($indicateurs->mesIndicateurs()))
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="panel panel-primary hpanel responsive-mg-b-30">
            <div class="panel-body">
                <table class="table table-responsive table-striped table-bordered">
                        @if(in_array ('nbreMesNouvelleCampagneDuJour',$indicateurs->mesIndicateurs()))
                            {!! $indicateurs->mesNouvelleCampagneDuJour(date ("Y-m-d")) !!}
                        @endif
                        @if(in_array ('nbreNouvelleCampagneDuJour',$indicateurs->mesIndicateurs()))
                            {!! $indicateurs->lesNouvelleCampagneDuJour(date ("Y-m-d")) !!}
                        @endif
                        @if(in_array ('nbreMesCampagnesNonValidees',$indicateurs->mesIndicateurs()))
                            {!! $indicateurs->nbreMesCampagnesNonValidees() !!}
                        @endif
                        @if(in_array ('nbreMesSaisiesNonValidees',$indicateurs->mesIndicateurs()))
                            {!! $indicateurs->nbreMesSaisiesNonValidees(date ('Y-m-d')) !!}
                        @endif
                        @if(in_array ('nbreMesSaisiesValidees',$indicateurs->mesIndicateurs()))
                            {!! $indicateurs->nbreMesSaisiesValidees(date ('Y-m-d')) !!}
                        @endif
                        @if(in_array ('nbreCampagnesNonValidees',$indicateurs->mesIndicateurs()))
                            {!! $indicateurs->nbreCampagnesNonValidees() !!}
                        @endif
                        @if(in_array ('nbreSaisiesNonValidees',$indicateurs->mesIndicateurs()))
                            {!! $indicateurs->nbreSaisiesNonValidees() !!}
                        @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <div class="row">
            @if(in_array ('tableauRecapDesNouvellesCampagnesDuJour',$indicateurs->mesIndicateurs()))
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-primary hpanel responsive-mg-b-30">
                        <div class="panel-heading">
                            Tableau récapitulatif des nouvelles campagnes du jour
                        </div>
                        <div class="panel-body">
                            {!! $indicateurs->tableauRecapDesNouvellesCampagnesDuJour() !!}
                        </div>
                    </div>
                </div>
            @endif
            @if(in_array ('nbreTotalDeLigneDeSaisieParMedia',$indicateurs->mesIndicateurs()))
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-primary hpanel responsive-mg-b-30">
                        <div class="panel-heading">
                            Nombre total de ligne de saisies par média
                        </div>
                        <div class="panel-body">
                            {!! $indicateurs->nbreTotalDeLigneDeSaisieParMedia() !!}
                        </div>
                    </div>
                </div>
            @endif
            @if(in_array ('nbreTotalDeLigneDeSaisieParMediaNonValider',$indicateurs->mesIndicateurs()))
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-primary hpanel responsive-mg-b-30">
                        <div class="panel-heading">
                            Nombre total de ligne de saisies par média en attente de validation
                        </div>
                        <div class="panel-body">
                            {!! $indicateurs->nbreTotalDeLigneDeSaisieParMediaNonValider() !!}
                        </div>
                    </div>
                </div>
            @endif
            @if(in_array ('tableauRecapDesSaisies',$indicateurs->mesIndicateurs()))
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-primary hpanel responsive-mg-b-30">
                        <div class="panel-heading">
                            Tableau récapitulatif des saisies
                        </div>
                        <div class="panel-body">
                            {!! $indicateurs->tableauRecapDesSaisies() !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(in_array ('nbreMesSaisiesDuJour',$indicateurs->mesIndicateurs()))

    @endif
    @if(in_array ('dateHeure',$indicateurs->mesIndicateurs()))

    @endif
    
    
@endif
