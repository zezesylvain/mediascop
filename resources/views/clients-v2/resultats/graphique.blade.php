<?php
//dd($parAnnonceur);
?>
        <div class="col-xs-12 bordered">
            <div class="row">
                <div class="col-xs-12 p-b-25">
                    <div class="content-panel">
                        <h3 style="text-transform:uppercase;">
                            <i class="fa fa-angle-right"></i>
                            Investissement par Annonceur
                        </h3>
                        <section id="parAnnonceur">
                            <div class="table-responsive">
                                
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <div id="investParAnnonceur" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto;">
                                            
                                        </div>
                                    </div>
                                </div>
                    
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>    
    @php($lesItems = ['media', 'typecom', 'typeservice', 'format', 'cible'])
    @foreach($lesItems AS $item)
        @php($tab = $parAnnonceur[$item] ?? [])
        @if(!empty($tab))
            <div class="col-xs-12 bordered">
            <div class="row">
                <div class="col-xs-12 p-b-25">
                    <div class="content-panel">
                        <h3 style="text-transform:uppercase;">
                            <i class="fa fa-angle-right"></i>
                            Insertion et Investissement par Annonceur et par  {{ $parametres[$item]['libelle'] }}
                        </h3>
                        <section id="unseen{{str_replace(' ', '', $item)}}">
                            <div class="table-responsive">
                                
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <div id="piePdiInvestParAnnonceurEtPar{{$item}}" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto;">
                                            
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <div id="pdiInvestParAnnonceurEtPar{{$item}}" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto;">
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-12 p-b-25">&nbsp;</div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-xs-12 p-b-25">&nbsp;</div>
                                    <div class="col-xs-12 col-md-5">
                                        <div id="pieInvestParAnnonceurEtPar{{$item}}" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto;">
                                            
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <div id="investParAnnonceurEtPar{{$item}}" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto;">
                                            
                                        </div>
                                    </div>
                                </div>
                    
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>  
        @endif
    @endforeach
    @php($tabInvParAnn = $investParAnnonceur)
    @inject("highChart", "\App\Http\Controllers\Charts\HighchartController")
    {!! $highChart::makeHighchartJSColor("parAnnonceur", "Investissement par Annonceur", $investParAnnonceur, $listDesCouleursDesAnnonceur) !!}
<?php //* ?>
    @foreach($parAnnonceur AS $item => $tab1)
    
        @if(!empty($tab1))
            @php($rubrique = $parametres[$item]['libelle'])
            @php($donneesChart = $highChart::transformTripleDataForChart($tab1))
            @php($tab = $donneesChart['donnees']['invest'] ?? [])
            @php($title = "Investissement par Annonceur et par  $rubrique ")
            @php($titre2 = "Investissement par $rubrique")
            @php($tab3 = $highChart::BarChartDataForPieChartData($tab))
            {!! $highChart::makeHighchartJSBar("investParAnnonceurEtPar$item", $title, $tab, $listDesCouleursDesAnnonceur) !!}
            {!! $highChart::makeHighchartJS("pieInvestParAnnonceurEtPar$item", $titre2, $tab3) !!}
            @php($tabPdi = $highChart::transformTripleDataForBarChart($tab1, 'insertion'))
            @php($tabPdi = $donneesChart['donnees']['insertion'] ?? [])
            @php($titlePdi = "Insertion par Annonceur et par  $rubrique ")
            @php($titre2Pdi = "Insertion par  $rubrique ")
            @php($tab3Pdi = $highChart::BarChartDataForPieChartData($tabPdi))
            {!! $highChart::makeHighchartJSBar("pdiInvestParAnnonceurEtPar$item", $titlePdi, $tabPdi, $listDesCouleursDesAnnonceur) !!}
            {!! $highChart::makeHighchartJS("piePdiInvestParAnnonceurEtPar$item", $titre2Pdi, $tab3Pdi) !!}
        @endif
    @endforeach
    <? //*/ ?>
    <script type="text/javascript">
    // Radialize the colors
        Highcharts.setOptions({
            colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
                return {
                    radialGradient: {
                        cx: 0.5,
                        cy: 0.3,
                        r: 0.7
                    },
                    stops: [
                        [0, color],
                        [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
                    ]
                };
            })
        });
    </script>