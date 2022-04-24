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
    
        <?php 
            /*
            $item = 'format'; //'media', 'cible', 'nature'
            $tab =  $parAnnonceur[$item];
            $parAnnonceur = [$item => $tab] ;
            //dd($tab); 0708909708
            //*/
            
        ?>
    @foreach($parAnnonceur AS $item => $tab)
        <div class="col-xs-12 bordered">
            <div class="row">
                <div class="col-xs-12 p-b-25">
                    <div class="content-panel">
                        <h3 style="text-transform:uppercase;">
                            <i class="fa fa-angle-right"></i>
                            Insertion et Investissement par Annonceur et par  {{$item}}
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
    @endforeach
    @php($tabInvParAnn = $investParAnnonceur)
    @inject("highChart", "\App\Http\Controllers\Charts\HighchartController")
    @inject("reporting", "\App\Http\Controllers\Client\ReportingController")
    {!! $highChart::makeHighchartJSColor("parAnnonceur", "Investissement par Annonceur", $investParAnnonceur, $listDesCouleursDesAnnonceur) !!}
    @foreach($parAnnonceur AS $item => $tab1)
        @php($donneesChart = $highChart::transformTripleDataForChart($tab1))
        @php($tab = $donneesChart['donnees']['invest'] ?? [])
        @php($title = "Investissement par Annonceur et par $item")
        @php($titre2 = "Investissement par $item")
        @php($tab3 = $highChart::BarChartDataForPieChartData($tab))
        {!! $highChart::makeHighchartJSBar("investParAnnonceurEtPar$item", $title, $tab, $listDesCouleursDesAnnonceur) !!}
        {!! $highChart::makeHighchartJS("pieInvestParAnnonceurEtPar$item", $titre2, $tab3) !!}
        @php($tabPdi = $reporting::transformTripleDataForBarChart($tab1, 'insertion'))
        @php($tabPdi = $donneesChart['donnees']['insertion'] ?? [])
        @php($titlePdi = "Insertion par Annonceur et par $item")
        @php($titre2Pdi = "Insertion par $item")
        @php($tab3Pdi = $highChart::BarChartDataForPieChartData($tabPdi))
        {!! $highChart::makeHighchartJSBar("pdiInvestParAnnonceurEtPar$item", $titlePdi, $tabPdi, $listDesCouleursDesAnnonceur) !!}
        {!! $highChart::makeHighchartJS("piePdiInvestParAnnonceurEtPar$item", $titre2Pdi, $tab3Pdi) !!}
    @endforeach
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