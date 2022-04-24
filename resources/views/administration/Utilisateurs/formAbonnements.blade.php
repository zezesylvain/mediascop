<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="messageAbonnementItem" class="alert "></div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="admintab-wrap mg-t-30">
            <ul class="nav nav-tabs custom-menu-wrap custon-tab-menu-style1">
                    <li class="active">
                        <a data-toggle="tab" href="#Tab1" title="Cliquer pour actualiser!"><span class="adminpro-icon
                        adminpro-analytics tab-custon-ic"></span>Abonnement secteur d'activité <i class="fa
                        fa-refresh"></i> </a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" href="#Tab2" title="Cliquer pour actualiser!"><span class="adminpro-icon
                        adminpro-analytics tab-custon-ic"></span>Abonnement entreprise <i class="fa
                        fa-refresh"></i>
                        </a>
                    </li>
            </ul>
            <div class="tab-content">
                    <div id="Tab1" class="tab-pane in active animated flipInX custon-tab-style1"
                         style="">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 list-pub-saisie">
                                {{--{!! $userSecteur !!}--}}
                                @include("administration.Utilisateurs.formAbonnerSecteur")
                            </div>
                        </div>
                    </div>
                    <div id="Tab2" class="tab-pane in animated flipInX custon-tab-style1"
                         style="">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 list-pub-saisie">
                                {{--{!! $userSociete !!}--}}
                                @include("administration.Utilisateurs.formAbonnerSociete")
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
