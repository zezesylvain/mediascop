@if($si_form_valide):
@inject("chart", "\App\Http\Controllers\core\FunctionHighchartController")
        <div class="col-xs-12 bordered">
            <div id="partInvestParSecteur" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto"></div>
        </div>
        <div class="col-xs-12 bordered">
            <div id="partInvestParAnnonceur" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto"></div>
        </div>
        <div class="col-xs-12 bordered">
            <div id="partInvestParMedia" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto"></div>
        </div>
        <div class="col-xs-12 bordered">
            <div id="partInvestParMediaEtParAnnonceur" style="min-width: 310px; height: <?php echo $hauteur2; ?>px; margin: 0 auto"></div>
        </div>
        <div class="col-xs-12 bordered">
            <div id="partInvestParCible" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto"></div>
        </div>
        <div class="col-xs-12 bordered">
            <div id="partInvestParNature" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto"></div>
        </div>
        <div class="col-xs-12 bordered">
            <div id="partInvestParOffre" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto"></div>
        </div>

        {!! $chart->makeHighchartJS("partInvestParSecteur", "PART D'INVESTISSEMENT PAR SECTEUR", $ParSecteur) !!}
        {!! $chart->makeHighchartJSColor("partInvestParAnnonceur", "INVESTISSEMENT PAR ANNONCEUR", $investParAnnonceur, $listDesCouleursDesAnnonceur) !!}
        {!! $chart->makeHighchartJS("partInvestParCible", "INVESTISSEMENT PAR CIBLE", $investParCible) !!}
        {!! $chart->makeHighchartJS("partInvestParNature", "INVESTISSEMENT PAR NATURE", $investParNature) !!}
        {!! $chart->makeHighchartJS("partInvestParOffre", "INVESTISSEMENT PAR OFFRE TELECOM", $investParOffretelecom) !!}
        {!! $chart->makeHighchartJS("partInvestParMedia", "INVESTISSEMENT PAR MEDIA", $investParMedia) !!}
        {!! $chart->makeHighchartJSBar("partInvestParMediaEtParAnnonceur", "INVESTISSEMENT PAR ANNONCEUR ET PAR MEDIA", $investParAnnonceurEtParMedia, $listDesCouleursDesAnnonceur) !!}

        @foreach($partDeVoixParMedia as $KMedia => $row)
            <div class="col-xs-12 col-sm-12 bordered">
                <div id="partdeVoix{{$KMedia}}" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto"></div>
            </div>
            <div class="col-xs-12 col-sm-12 bordered">
                <div id="InvestPar{{$KMedia}}" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto"></div>
            </div>
            {!! $chart->makeHighchartJSColor("partdeVoix$KMedia", "PART DE VOIX sur $KMedia", $row, $listDesCouleursDesAnnonceur) !!}
            {!! $chart->makeHighchartJSColor("InvestPar$KMedia", "INVEST PAR ANNONCEUR SUR $KMedia", $investParMediaParAnnonceur[$KMedia], $listDesCouleursDesAnnonceur) !!}
        @endforeach

@endif
