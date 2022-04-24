@inject("saisie", "\App\Http\Controllers\Administration\SaisieController")
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="admintab-wrap mg-t-30">
            <ul class="nav nav-tabs custom-menu-wrap custon-tab-menu-style1">
                <li class="active"><a data-toggle="tab" href="#TabSaisieJour" title="Cliquer pour actualiser!"  onclick="sendData('d=0','{{route ('ajax.getUserSaisie')}}','saisieNonValideItem')"><span class="adminpro-icon adminpro-analytics tab-custon-ic"></span>Mes saisies du jours <i class="fa fa-refresh"></i> </a>
                </li>
                <li><a data-toggle="tab" href="#TabSaisieJourValide" title="Cliquer pour actualiser!"  onclick="sendData('d=1','{{route ('ajax.getUserSaisie')}}','saisieValideItem')"><span class="adminpro-icon adminpro-analytics-arrow tab-custon-ic"></span>Mes saisies du jour validées <i class="fa fa-refresh"></i></a>
                </li>
                <li><a data-toggle="tab" href="#TabSaisieAutre" title="Cliquer pour actualiser!"  onclick="sendData('d=0&k=autre','{{route ('ajax.getUserSaisie')}}','saisieAutreNonValideItem')"><span class="adminpro-icon adminpro-analytics-bridge tab-custon-ic"></span>Autre saisie du jours non validée <i class="fa fa-refresh"></i></a>
                </li>
                <li><a data-toggle="tab" href="#TabSaisieAutreValider" title="Cliquer pour actualiser!" onclick="sendData('d=1&k=autre','{{route ('ajax.getUserSaisie')}}','saisieAutreValideItem')"><span class="adminpro-icon adminpro-analytics-bridge tab-custon-ic"></span>Autre saisie du jours validée <i class="fa fa-refresh"></i></a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="TabSaisieJour" class="tab-pane in active animated flipInX custon-tab-style1">
                    <hr>
                    {!! $titreHtml ("Pubs non validés",4) !!}
                    <div id="saisieNonValideItem">
                        {!! $saisie->showSaisie (date ("Y-m-d"),$userID) !!}
                    </div>
                </div>
                <div id="TabSaisieJourValide" class="tab-pane animated flipInX custon-tab-style1">
                    <hr>
                    {!! $titreHtml ("Pubs validés",4) !!}
                    <div id="saisieValideItem">
                        {!! $saisie->showSaisie (date ("Y-m-d"),$userID,1) !!}
                    </div>
                </div>
                <div id="TabSaisieAutre" class="tab-pane animated flipInX custon-tab-style1">
                    <hr>
                    {!! $titreHtml ("Pubs non validés",4) !!}
                    <div id="saisieAutreNonValideItem">
                        {!! $saisie->showSaisie (date ("Y-m-d")) !!}
                    </div>
                </div>
                <div id="TabSaisieAutreValider" class="tab-pane animated flipInX custon-tab-style1">
                    <hr>
                    {!! $titreHtml ("Pubs validés",4) !!}
                    <div id="saisieAutreValideItem">
                        {!! $saisie->showSaisie (date ("Y-m-d"),0,1) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
