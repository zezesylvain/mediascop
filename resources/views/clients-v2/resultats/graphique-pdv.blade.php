
    @foreach($parAnnonceur AS $item => $tab)
        <div class="col-xs-12 bordered">
            <h3 style="text-transform:uppercase;">Insertion par Annonceur et par {{$item}}</h3>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="piePdiInvestParAnnonceurEtPar{{$item}}" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto;">
                        
                    </div>
                </div>
                <div class="col-xs-12 col-md-7">
                    <div id="pdiInvestParAnnonceurEtPar{{$item}}" style="min-width: 310px; height: {{$hauteur}}px; margin: 0 auto;">
                        
                    </div>
                </div>
            </div>
                    
        </div>
        <div class="col-xs-12 p-b-25">&nbsp;</div>
        <hr>
    @endforeach
    @inject("highChart", "\App\Http\Controllers\Charts\HighchartController")
    @inject("reporting", "\App\Http\Controllers\Client\ReportingController")
    @foreach($parAnnonceur AS $item => $tab1)
        @php($tab = $reporting::transformTripleDataForBarChart($tab1, 'insertion'))
        @php($title = "Insertion par Annonceur et par $item")
        @php($titre2 = "Insertion par $item")
        @php($tab3 = $highChart::BarChartDataForPieChartData($tab))
        {!! $highChart::makeHighchartJSBar("pdiInvestParAnnonceurEtPar$item", $title, $tab, $listDesCouleursDesAnnonceur) !!}
        {!! $highChart::makeHighchartJS("piePdiInvestParAnnonceurEtPar$item", $titre2, $tab3) !!}
    @endforeach
    <script type="text/javascript">
    / Radialize the colors
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